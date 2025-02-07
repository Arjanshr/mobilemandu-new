<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\Category;
use App\Models\Coupon;
use Illuminate\Validation\Rule;

class CouponForm extends Component
{
    public $coupon;
    public $is_user_specific = false;
    public $is_category_specific = false;
    public $users = [];
    public $categories = [];
    public $user_id;
    public $category_id;

    protected $rules = [
        'coupon.code' => 'required|string|unique:coupons,code,' .  'NULL', // Will handle validation later for updates
        'coupon.discount_value' => 'required|numeric',
        'coupon.discount_type' => 'required',
        'coupon.status' => 'required|boolean',
    ];

    public function mount($coupon = null)
    {
        // Ensure coupon is always an object, even if it's null
        $this->coupon = $coupon ?? new Coupon();

        if ($coupon) {
            $this->is_user_specific = $coupon->is_user_specific;
            $this->is_category_specific = $coupon->is_category_specific;
            $this->user_id = $coupon->user_id;
            $this->category_id = $coupon->category_id;
        }

        // Load users and categories
        $this->users = User::all();
        $this->categories = Category::all();
    }

    // Adjust the rules dynamically based on conditions
    public function getRules()
    {
        $rules = $this->rules;

        // Apply 'required' for user_id only if 'is_user_specific' is checked
        if ($this->is_user_specific) {
            $rules['user_id'] = 'required|exists:users,id';
        }

        // Apply 'required' for category_id only if 'is_category_specific' is checked
        if ($this->is_category_specific) {
            $rules['category_id'] = 'required|exists:categories,id';
        }

        return $rules;
    }

    public function save()
    {
        $this->validate($this->getRules());

        if ($this->coupon->exists) {
            // Update existing coupon
            $this->coupon->update([
                'user_id' => $this->user_id,
                'category_id' => $this->category_id,
                'is_user_specific' => $this->is_user_specific,
                'is_category_specific' => $this->is_category_specific,
            ]);
        } else {
            // Create new coupon
            $this->coupon = Coupon::create([
                'code' => $this->coupon['code'],
                'discount_value' => $this->coupon['discount_value'],
                'discount_type' => $this->coupon['discount_type'],
                'status' => $this->coupon['status'],
                'user_id' => $this->is_user_specific ? $this->user_id : null,
                'category_id' => $this->is_category_specific ? $this->category_id : null,
                'is_user_specific' => $this->is_user_specific,
                'is_category_specific' => $this->is_category_specific,
            ]);
        }

        session()->flash('message', $this->coupon->exists ? 'Coupon updated successfully!' : 'Coupon created successfully!');
    }

    public function render()
    {
        return view('admin.livewire.coupon-form');
    }
}
