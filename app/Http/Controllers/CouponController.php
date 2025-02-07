<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\CouponRequest;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\User;

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
        // return $request;
        // If the request passes validation, it will automatically proceed to the next step
        Coupon::create($request->validated());

        return redirect()->route('coupons')->with('success', 'Coupon created successfully.');
    }

    // Update a coupon
    public function update(CouponRequest $request, Coupon $coupon)
    {
        // Use validated data to update the coupon
        $coupon->update($request->validated());

        return redirect()->route('coupons')->with('success', 'Coupon updated successfully.');
    }

    // Delete a coupon
    public function delete(Coupon $coupon)
    {
        $coupon->delete();
        return redirect()->route('coupons')->with('success', 'Coupon deleted successfully.');
    }
}
