<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CouponApplyResource;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CouponController extends BaseController
{
    public function applyCoupon(Request $request)
    {
        $request->validate([
            'coupon_code'  => 'required|string',
            'cart_total'   => 'required|numeric|min:0',
            'items'        => 'required|array',
            'items.*.id'   => 'required|integer',
            'items.*.rate' => 'required|numeric|min:0',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        $coupon = Coupon::where('code', $request->coupon_code)
                        ->where('status', 1) // Active coupons only
                        ->first();

        if (!$coupon) {
            return $this->sendError('Invalid or expired coupon', 'The provided coupon code is not valid.', 400);
        }

        // Check if coupon is user-specific
        if ($coupon->users()->exists() && !$coupon->users->contains(Auth::id())) {
            return $this->sendError('Unauthorized coupon', 'This coupon is not valid for your account.', 403);
        }

        // Get eligible categories for the coupon
        $valid_category_ids = $coupon->categories->pluck('id')->toArray();
        $is_category_specific = !empty($valid_category_ids);

        $total_discount = 0;
        $updated_items = [];

        foreach ($request->items as $item) {
            $product = \App\Models\Product::with('categories')->find($item['id']);
        
            if (!$product) {
                continue;
            }
        
            // Get all category IDs the product belongs to
            $product_category_ids = $product->categories->pluck('id')->toArray();
        
            // Check if the product has at least one eligible category
            $is_eligible = !$is_category_specific || !empty(array_intersect($product_category_ids, $valid_category_ids));
        
            if ($is_eligible) {
                $item_total = $item['rate'] * $item['quantity'];
                $discount = ($coupon->type === 'fixed') 
                    ? min($coupon->discount, $item_total) 
                    : ($item_total * $coupon->discount) / 100;
        
                if ($coupon->max_discount && $discount > $coupon->max_discount) {
                    $discount = $coupon->max_discount;
                }
        
                // Round values to 2 decimal places
                $item['discount'] = round($discount, 2);
                $item['total'] = round($item_total - $discount, 2);
        
                $total_discount += $item['discount'];
            } else {
                $item['discount'] = 0;
                $item['total'] = round($item['rate'] * $item['quantity'], 2);
            }
        
            $updated_items[] = $item;
        }
        
        $new_total = round($request->cart_total - $total_discount, 2);
        
        return $this->sendResponse(
            new CouponApplyResource((object) [
                'total_discount' => $total_discount,
                'new_total'      => $new_total,
                'updated_items'  => $updated_items
            ]),
            'Coupon applied successfully.'
        );        
    }
}
