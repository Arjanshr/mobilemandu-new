<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CouponApplyResource;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CouponController extends BaseController
{
    public function applyCoupon(Request $request)
    {
        // return $request;
        $data = $request->validate([
            'coupon_code'  => 'required|string',
            'cart_total'   => 'required|numeric|min:0',
            'items'        => 'required|array',
            'items.*.id'   => 'required|integer',
            'items.*.rate' => 'required|numeric|min:0',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        $coupon = Coupon::where('code', $data['coupon_code'])->where('status', 1)->first();
        // return $coupon;

        if (!$coupon || !$this->isValidForUser($coupon)) {
            return $this->sendError('Invalid coupon', 'This coupon is either invalid, expired, or restricted.', 400);
        }

        $valid_category_ids = $coupon->categories->pluck('id')->toArray();
        $is_category_specific = !empty($valid_category_ids);

        $total_discount = 0;
        $updated_items = [];

        foreach ($data['items'] as $item) {
            $product = Product::with('categories')->find($item['id']);
            if (!$product) continue;

            $product_category_ids = $product->categories->pluck('id')->toArray();
            $is_eligible = !$is_category_specific || array_intersect($product_category_ids, $valid_category_ids);

            $item['discount'] = $is_eligible ? $this->calculateDiscount($coupon, $item) : 0;
            $item['total'] = ceil(($item['rate'] * $item['quantity']) - $item['discount']);
            
            $total_discount += $item['discount'];
            $updated_items[] = $item;
        }
        return $this->sendResponse(
            new CouponApplyResource((object) [
                'total_discount' => $total_discount,
                'new_total'      => ceil($data['cart_total'] - $total_discount),
                'updated_items'  => $updated_items
            ]),
            'Coupon applied successfully.'
        );
    }

    private function isValidForUser($coupon)
    {
        $user_id = Auth::id();

        if ($coupon->is_user_specific && $coupon->users()->exists() && !$coupon->users->contains($user_id)) {
            return false;
        }
        if (Order::where('user_id', $user_id)->where('coupon_code', $coupon->code)->exists()) {
            return false;
        }

        return $coupon->uses < $coupon->max_uses;
    }

    private function calculateDiscount($coupon, $item)
    {
        $item_total = $item['rate'] * $item['quantity'];
        $discount = ($coupon->type === 'fixed') ? min($coupon->discount, $item_total) : ($item_total * $coupon->discount) / 100;

        return ceil(min($discount, $coupon->max_discount ?? $discount));
    }
}
