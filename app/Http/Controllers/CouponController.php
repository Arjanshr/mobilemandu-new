<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\CouponRequest;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\User;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    // Show all coupons
    public function index()
    {
        $coupons = Coupon::latest()->paginate(10);
        return view('admin.coupons.index', compact('coupons'));
    }

    // Show form to create a new coupon
    public function create()
    {
        $users = User::all();
        $categories = Category::all();
        return view('admin.coupons.form', compact('users', 'categories'));
    }

    public function edit(Coupon $coupon)
    {
        $users = User::all();
        $categories = Category::all();
        return view('admin.coupons.form', compact('coupon', 'users', 'categories'));
    }


    // Store a new coupon
    public function insert(CouponRequest $request)
    {
        $coupon = Coupon::create([
            'code' => $request->code,
            'type' => $request->type,
            'discount' => $request->discount,
            'max_uses' => $request->max_uses ?? null,
            'expires_at' => $request->expires_at ?? null,
            'status' => $request->status,
            'is_user_specific' => $request->has('is_user_specific'), // Convert "on" to true/false
            'is_category_specific' => $request->has('is_category_specific'), // Convert "on" to true/false
        ]);
    
        // Attach users if user-specific
        if ($coupon->is_user_specific && $request->user_ids) {
            $coupon->users()->sync($request->user_ids);
        }
    
        // Attach categories if category-specific
        if ($coupon->is_category_specific && $request->category_ids) {
            $coupon->categories()->sync($request->category_ids);
        }
        toastr()->success('Coupon created successfully!');

        return redirect()->route('coupons')->with('message', 'Coupon created successfully!');
    }

    // Update a coupon
    public function update(CouponRequest $request, Coupon $coupon)
    {
        $coupon->update([
            'code' => $request->code,
            'type' => $request->type,
            'discount' => $request->discount,
            'max_uses' => $request->max_uses ?? null,
            'expires_at' => $request->expires_at ?? null,
            'status' => $request->status,
            'is_user_specific' => $request->has('is_user_specific'),
            'is_category_specific' => $request->has('is_category_specific'),
        ]);

        // Sync users if user-specific
        if ($coupon->is_user_specific && $request->user_ids) {
            $coupon->users()->sync($request->user_ids);
        } else {
            $coupon->users()->detach();
        }

        // Sync categories if category-specific
        if ($coupon->is_category_specific && $request->category_ids) {
            $coupon->categories()->sync($request->category_ids);
        } else {
            $coupon->categories()->detach();
        }
        toastr()->success('Coupon updated successfully!');

        return redirect()->route('coupons')->with('message', 'Coupon updated successfully!');
    }

    // Delete a coupon
    public function delete(Coupon $coupon)
    {
        $coupon->delete();
        toastr()->success('Coupon deleted successfully!');
        return redirect()->route('coupons')->with('success', 'Coupon deleted successfully.');
    }
}
