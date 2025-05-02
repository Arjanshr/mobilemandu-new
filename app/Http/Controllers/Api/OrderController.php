<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderRequest;
use App\Http\Resources\ProvinceResource;
use App\Http\Resources\ShippingPriceResource;
use App\Models\Address;
use App\Models\Area;
use App\Models\City;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Province;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\ProductVariant;

class OrderController extends BaseController
{
    public function create(OrderRequest $request)
    {
        DB::beginTransaction();
        try {
            $customer = $this->getOrCreateCustomer($request);
            if ($this->hasExceededOrderLimit($customer)) {
                return $this->sendError('You have reached the limit of 3 orders per hour.');
            }

            $address = $this->getOrCreateAddress($request, $customer);
            $area = Area::findOrFail($request->area_id);
            $city = City::findOrFail($request->city_id);
            $province = Province::findOrFail($request->province_id);

            $shipping_address = $this->formatShippingAddress($request, $area, $city, $province);

            [$total_price, $total_discount, $final_items] = $this->calculateOrderItems($request->items);

            [$coupon_discount, $shipping_price, $updated_items] = $this->applyCouponLogic(
                $request->coupon_code, $customer, $area, $final_items
            );

            // Recalculate total_discount after coupon logic
            $total_discount = array_sum(array_column($updated_items, 'discount'));

            $grand_total = $total_price - $total_discount + $shipping_price;

            $order = Order::create([
                'user_id' => $customer->id,
                'address_id' => $address->id,
                'total_price' => $total_price,
                'discount' => $total_discount,
                'shipping_price' => $shipping_price,
                'grand_total' => $grand_total,
                'payment_type' => $request->payment_type,
                'payment_status' => $request->payment_status,
                'shipping_address' => $shipping_address,
                'area_id' => $area->id,
                'coupon_code' => $request->coupon_code,
                'coupon_discount' => $coupon_discount,
            ]);

            foreach ($updated_items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'variant_id' => $item['variant_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'discount' => $item['discount'] ?? 0,
                    'coupon_discount' => $item['coupon_discount'] ?? 0,
                ]);
            }

            DB::commit();
            return $this->sendResponse(null, 'Order created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->sendError('Failed to create order', ['error' => $e->getMessage()]);
        }
    }

    private function getOrCreateCustomer($request)
    {
        if (!$request->customer_id) {
            $customer = User::where('email', $request->email)
                ->orWhere('phone', $request->phone_number)
                ->first();
            if (!$customer) {
                $customer = User::create([
                    'name' => $request->reciever_name,
                    'email' => $request->email,
                    'phone' => $request->phone_number,
                    'password' => bcrypt('password'),
                ]);
                $customer->assignRole('customer');
            }
        } else {
            $customer = User::findOrFail($request->customer_id);
        }
        return $customer;
    }

    private function hasExceededOrderLimit($customer)
    {
        $orderCountLastHour = Order::where('user_id', $customer->id)
            ->where('created_at', '>=', now()->subHour())
            ->count();
        return $orderCountLastHour >= 10;
    }

    private function getOrCreateAddress($request, $customer)
    {
        if (!$request->address_id) {
            return Address::firstOrCreate([
                'user_id' => $customer->id,
                'type' => $request->address_type,
                'city_id' => $request->city_id,
                'province_id' => $request->province_id,
                'area_id' => $request->area_id,
                'location' => $request->location,
            ], [
                'phone_number' => $request->phone_number,
            ]);
        } else {
            return Address::findOrFail($request->address_id);
        }
    }

    private function formatShippingAddress($request, $area, $city, $province)
    {
        $address_type = ucfirst($request->address_type);
        $shipping_address  = "Name: {$request->reciever_name}";
        $shipping_address .= "<br/>Phone: {$request->phone_number}";
        $shipping_address .= "<br/>Email: {$request->email}";
        $shipping_address .= "<br/><br/>{$area->name} ({$address_type})";
        $shipping_address .= "<br/> {$request->location}";
        $shipping_address .= "<br/> {$city->name}";
        $shipping_address .= "<br/> {$province->name}";
        return $shipping_address;
    }

    private function calculateOrderItems($items)
    {
        $total_price = 0;
        $total_discount = 0;
        $final_items = [];
        foreach ($items as $item) {
            $product = Product::findOrFail($item['id']);
            $variant = isset($item['variant_id']) ? ProductVariant::find($item['variant_id']) : null;
            $price = $variant ? $variant->price : $product->discounted_price;
            $quantity = $item['quantity'];
            $item_discount = $product->discount ?? 0;
            $total_price += $price * $quantity;
            $total_discount += $item_discount * $quantity;
            $final_items[] = [
                'product_id' => $product->id,
                'variant_id' => $variant?->id,
                'quantity' => $quantity,
                'price' => $price,
                'discount' => $item_discount,
            ];
        }
        return [$total_price, $total_discount, $final_items];
    }

    private function applyCouponLogic($coupon_code, $customer, $area, $final_items)
    {
        $coupon_discount = 0;
        $shipping_price = $area->shipping_price ?? 0;
        $updated_items = $final_items;

        if (!$coupon_code) {
            return [$coupon_discount, $shipping_price, $updated_items];
        }

        $coupon = Coupon::where('code', $coupon_code)
            ->where('status', 1)
            ->where(function ($query) {
                $query->whereNull('expires_at')->orWhere('expires_at', '>=', now());
            })
            ->first();

        $user_id = $customer->id;
        $coupon_valid = $coupon ? true : false;
        if ($coupon && $coupon->max_uses !== null && $coupon->uses >= $coupon->max_uses) {
            $coupon_valid = false;
        }
        if ($coupon && $coupon->is_user_specific) {
            if ($coupon->users()->exists() && !$coupon->users->contains($user_id)) {
                $coupon_valid = false;
            }
            if (Order::where('user_id', $user_id)->where('coupon_code', $coupon->code)->exists()) {
                $coupon_valid = false;
            }
        }

        if (!$coupon || !$coupon_valid) {
            return [$coupon_discount, $shipping_price, $updated_items];
        }

        if ($coupon->specific_type === 'free_delivery') {
            $shipping_price = 0;
            $free_delivery_discount_applied = false;
            foreach ($final_items as $idx => $item) {
                if (!$free_delivery_discount_applied) {
                    $updated_items[$idx]['coupon_discount'] = $area->shipping_price ?? 0;
                    $updated_items[$idx]['discount'] = 0;
                    $free_delivery_discount_applied = true;
                } else {
                    $updated_items[$idx]['coupon_discount'] = 0;
                    $updated_items[$idx]['discount'] = 0;
                }
            }
            $coupon_discount = $area->shipping_price ?? 0;
        } else {
            $valid_specific_ids = [];
            if ($coupon->specific_type == 'category') {
                $valid_specific_ids = $coupon->categories->pluck('id')->toArray();
            } elseif ($coupon->specific_type == 'brand') {
                $valid_specific_ids = $coupon->brands->pluck('id')->toArray();
            } elseif ($coupon->specific_type == 'product') {
                $valid_specific_ids = $coupon->products->pluck('id')->toArray();
            }

            $total_coupon_discount = 0;
            foreach ($final_items as $idx => $item) {
                $product = Product::with('categories', 'brand')->find($item['product_id']);
                $is_eligible = false;
                if ($coupon->specific_type === 'normal') {
                    $is_eligible = true;
                } elseif ($coupon->specific_type == 'category') {
                    $product_category_ids = $product->categories->pluck('id')->toArray();
                    $is_eligible = !empty(array_intersect($product_category_ids, $valid_specific_ids));
                } elseif ($coupon->specific_type == 'brand') {
                    $is_eligible = in_array($product->brand_id, $valid_specific_ids);
                } elseif ($coupon->specific_type == 'product') {
                    $is_eligible = in_array($product->id, $valid_specific_ids);
                }
                $item_total = $item['price'] * $item['quantity'];
                if ($is_eligible) {
                    if ($coupon->type === 'fixed') {
                        $discount = min($coupon->discount, $item_total);
                    } else {
                        $discount = ($item_total * $coupon->discount) / 100;
                    }
                    $discount = ceil(min($discount, $coupon->max_discount ?? $discount));
                } else {
                    $discount = 0;
                }
                $updated_items[$idx]['coupon_discount'] = $discount;
                $updated_items[$idx]['discount'] = $discount;
                $total_coupon_discount += $discount;
            }
            $coupon_discount = $total_coupon_discount;
        }
        $coupon->increment('uses');
        return [$coupon_discount, $shipping_price, $updated_items];
    }

    public function getProvinces()
    {
        $provinces = Province::get();
        return $this->sendResponse(ProvinceResource::collection($provinces), 'Provinces Retrived successfully.');
    }

    public function getCities($province_id = null)
    {
        if (!$province_id) {
            $cities = City::get();
        } else {
            $cities = City::where('province_id', $province_id)->get();
        }
        return $this->sendResponse(ProvinceResource::collection($cities), 'Cities Retrived successfully.');
    }
    public function getAreas($city_id = null)
    {
        if (!$city_id) {
            $areas = Area::get();
        } else {
            $areas = Area::where('city_id', $city_id)->get();
        }
        return $this->sendResponse(ProvinceResource::collection($areas), 'Cities Retrived successfully.');
    }

    public function getShippingPrice(Area $area)
    {
        return $this->sendResponse(ShippingPriceResource::make($area), 'Shipping Price retrived successfully.');
    }

    public function applyCoupon(Request $request)
    {
        $coupon = Coupon::where('code', $request->code)->first();

        if (!$coupon || !$coupon->isValid()) {
            return response()->json(['message' => 'Invalid or expired coupon'], 400);
        }

        if (!$coupon->isValidForUser($request->user_id)) {
            return response()->json(['message' => 'This coupon is not available for you'], 400);
        }

        $cartItems = $request->items;
        $couponCategories = $coupon->categories->pluck('id')->toArray();

        $discount = 0;
        foreach ($cartItems as $item) {
            if (empty($couponCategories) || in_array($item['category_id'], $couponCategories)) {
                $discount += $coupon->type === 'fixed'
                    ? min($coupon->discount, $item['price'] * $item['quantity'])
                    : ($item['price'] * $item['quantity']) * ($coupon->discount / 100);
            }
        }

        if ($discount == 0) {
            return response()->json(['message' => 'No eligible items for this coupon'], 400);
        }

        return response()->json([
            'discount' => $discount,
            'new_total' => $request->cart_total - $discount
        ]);
    }
}
