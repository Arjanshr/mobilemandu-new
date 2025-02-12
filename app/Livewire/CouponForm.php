<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\Category;
use App\Models\Coupon;

class CouponForm extends Component
{
    public $coupon;

    // Add these missing properties
    public $code;
    public $discount_type;
    public $discount_value;
    public $status;

    public $is_user_specific = false;
    public $is_category_specific = false;
    public $users = [];
    public $categories = [];
    public $user_ids = [];
    public $category_ids = [];

    protected $rules = [
        'code' => 'required|string|unique:coupons,code',
        'discount_value' => 'required|numeric',
        'discount_type' => 'required',
        'status' => 'required|boolean',
        'user_ids' => ['nullable', 'array'],
        'category_ids' => ['nullable', 'array'],
    ];

    public function mount($coupon = null)
    {
        $this->users = User::all();
        $this->categories = Category::all();

        if ($coupon) {
            $this->coupon = $coupon;
            $this->code = $coupon->code;
            $this->discount_type = $coupon->discount_type;
            $this->discount_value = $coupon->discount_value;
            $this->status = $coupon->status;
            $this->is_user_specific = $coupon->is_user_specific;
            $this->is_category_specific = $coupon->is_category_specific;
            $this->user_ids = $coupon->users->pluck('id')->toArray();
            $this->category_ids = $coupon->categories->pluck('id')->toArray();
        }
        // Dispatch 'select2Hydrate' to refresh Select2 fields
        $this->dispatch('select2Hydrate');
    }

    public function save()
    {
        $this->validate();

        if ($this->coupon) {
            $this->coupon->update([
                'code' => $this->code,
                'discount_value' => $this->discount_value,
                'discount_type' => $this->discount_type,
                'status' => $this->status,
                'user_ids' => $this->is_user_specific ? $this->user_ids : [],
                'category_ids' => $this->is_category_specific ? $this->category_ids : [],
                'is_user_specific' => $this->is_user_specific,
                'is_category_specific' => $this->is_category_specific,
            ]);
        } else {
            $this->coupon = Coupon::create([
                'code' => $this->code,
                'discount_value' => $this->discount_value,
                'discount_type' => $this->discount_type,
                'status' => $this->status,
                'is_user_specific' => $this->is_user_specific,
                'is_category_specific' => $this->is_category_specific,
            ]);
        }

        session()->flash('message', 'Coupon saved successfully!');

        // Dispatch 'select2Hydrate' after saving the coupon to update Select2 fields
        $this->dispatch('select2Hydrate');
    }

    public function updatedIsCategorySpecific($value)
    {
        // $this->user_ids = $value;
        $this->dispatch('select2Hydrate');
    }
    public function updateUserIds($value)
    {
        $this->user_ids = $value;
        $this->dispatch('select2Hydrate');
    }
    
    public function updateCategoryIds($value)
    {
        $this->category_ids = $value;
        $this->dispatch('select2Hydrate');
    }

    public function render()
    {
        return view('admin.livewire.coupon-form');
    }
}
