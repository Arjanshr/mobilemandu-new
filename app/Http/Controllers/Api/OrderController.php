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

class OrderController extends BaseController
{
    public function create(OrderRequest $request)
    {
        $shipping_address = "Name: $request->reciever_name";
        $shipping_address .= "<br/>Phone: $request->phone_number";
        $shipping_address .= "<br/>Email: $request->email";
        $area = Area::find($request->area_id);
        $shipping_address .= "<br/><br/>" . $area != null ? $area->name : '' . "(" . (ucfirst($request->address_type)) . ")";
        $shipping_address .= "<br/> $request->location";
        $city = City::find($request->city_id);
        $province = Province::find($request->province_id);
        $shipping_address .= "<br/> $city->name";
        $shipping_address .= "<br/> $province->name";
        if (!$request->customer_id) {
            $customer = User::where('email', $request->email)
                ->orWhere('phone', $request->phone_number)
                ->first();
            if (!$customer) {
                $customer = User::create(
                    [
                        'name' => $request->reciever_name,
                        'email' => $request->email,
                        'phone' => $request->phone,
                        'password' => bcrypt('password'),
                    ]
                );
                $customer->assignRole('customer');
            }
        } else {
            $customer = User::find($request->customer_id);
        }

        if (!$request->address_id) {
            $address = Address::where('user_id', $customer->id ?? $request->customer_id)
                ->where('type', $request->address_type)
                ->where('city_id', $request->city_id)
                ->where('province_id', $request->province_id)
                ->where('area_id', $request->area_id)
                ->where('location', $request->location)
                ->first();
            if (!$address) {
                $address = Address::create(
                    [
                        'user_id' =>  $customer->id ?? $request->customer_id,
                        'type' => $request->address_type,
                        'city_id' => $request->city_id,
                        'province_id' => $request->province_id,
                        'area_id' => $request->area_id,
                        'location' => $request->location,
                        'phone_number' => $request->phone_number,
                    ]
                );
            }
        } else {
            $address = Address::find($request->address_id);
        }
        $order = new Order();
        $order->user_id =  $customer->id;
        $order->address_id =  $address->id;
        $order->total_price = $request->total_amount;
        $order->discount = $request->total_discount;
        $order->shipping_price = $request->shipping_price;
        $order->grand_total = $request->grand_total;
        $order->payment_type = $request->payment_type;
        $order->payment_status = $request->payment_status;
        $order->shipping_address = $shipping_address;
        $order->area_id = $area->id;
        $order->coupon_code = $request->coupon_code;
        $order->coupon_discount = $request->coupon_discount;
        $order->save();
        foreach ($request->items as $item) {
            $order_item = new OrderItem();
            $order_item->order_id = $order->id;
            $order_item->product_id = $item['id'];
            $order_item->variant_id = $item['variant_id'] ?? null;
            $order_item->quantity = $item['quantity'];
            $order_item->price = $item['rate'];
            $order_item->discount = $item['discount']??0;
            $order_item->save();
        }
        Coupon::where('code', $request->coupon_code)->increment('uses');
        return $this->sendResponse(null, 'Order created successfully.');
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
