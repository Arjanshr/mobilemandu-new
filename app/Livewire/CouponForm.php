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

    // Define validation rules
    protected $rules = [
        'coupon.code' => 'required|string|unique:coupons,code,' .  ($this->coupon->id ?? 'NULL'),
        'coupon.discount_value' => 'required|numeric',
        'coupon.discount_type' => 'required',
        'coupon.status' => 'required|boolean',
        // Apply conditional required rules based on the checkboxes
        'user_id' => ['nullable', Rule::requiredIf(fn() => $this->is_user_specific)],
        'category_id' => ['nullable', Rule::requiredIf(fn() => $this->is_category_specific)],
    ];

    public function mount($coupon = null)
    {
        if ($coupon) {
            $this->coupon = $coupon;
            $this->is_user_specific = $coupon->is_user_specific;
            $this->is_category_specific = $coupon->is_category_specific;
            $this->user_id = $coupon->user_id;
            $this->category_id = $coupon->category_id;
        }

        // Load users and categories
        $this->users = User::all();
        $this->categories = Category::all();
    }

    // Whenever the checkbox changes, clear related IDs if unchecked
    public function updatedIsUserSpecific($value)
    {
        if (!$value) {
            $this->user_id = null; // Ensure user_id is cleared when unchecked
        }
    }

    public function updatedIsCategorySpecific($value)
    {
        if (!$value) {
            $this->category_id = null; // Ensure category_id is cleared when unchecked
        }
    }

    public function save()
    {
        // Validate the form data
        $this->validate();

        // Save the coupon based on whether it exists or is new
        if ($this->coupon) {
            $this->coupon->update([
                'code' => $this->coupon['code'],
                'discount_value' => $this->coupon['discount_value'],
                'discount_type' => $this->coupon['discount_type'],
                'status' => $this->coupon['status'],
                'user_id' => $this->is_user_specific ? $this->user_id : null,
                'category_id' => $this->is_category_specific ? $this->category_id : null,
                'is_user_specific' => $this->is_user_specific,
                'is_category_specific' => $this->is_category_specific,
            ]);
        } else {
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

        // Flash a success message
        session()->flash('message', $this->coupon ? 'Coupon updated successfully!' : 'Coupon created successfully!');
    }

    // Render the Livewire component view
    public function render()
    {
        return view('admin.livewire.coupon-form');
    }
}
