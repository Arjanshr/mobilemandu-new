<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\Category;
use App\Models\Coupon;

class CouponForm extends Component
{
    public $coupon;
    public $code;
    public $type;
    public $discount;
    public $max_uses;
    public $expires_at;
    public $status;
    public $is_user_specific = false;
    public $is_category_specific = false;

    public $users = [];
    public $categories = [];
    public $user_ids = [];
    public $category_ids = [];

    protected $rules = [
        'code' => 'required|string|unique:coupons,code',
        'type' => 'required|in:fixed,percentage',
        'discount' => 'required|numeric|min:0',
        'max_uses' => 'nullable|integer|min:1',
        'expires_at' => 'nullable|date',
        'status' => 'required|integer|in:0,1',
        'user_ids' => 'nullable|array',
        'category_ids' => 'nullable|array',
    ];

    public function mount($coupon = null)
    {
        $this->users = User::all();
        $this->categories = Category::all();

        if ($coupon && $coupon->exists) {
            $this->coupon = $coupon;
            $this->code = $coupon->code;
            $this->type = $coupon->type;
            $this->discount = $coupon->discount;
            $this->max_uses = $coupon->max_uses;
            $this->expires_at = optional($coupon->expires_at)->format('Y-m-d\TH:i');
            $this->status = $coupon->status;
            $this->is_user_specific = $coupon->is_user_specific == 1 ? true : false;
            $this->is_category_specific = $coupon->is_category_specific == 1 ? true : false;
            $this->user_ids = $coupon->users->pluck('id')->toArray();
            $this->category_ids = $coupon->categories->pluck('id')->toArray();
        }

        $this->dispatch('select2Hydrate'); // Ensure Select2 is initialized
    }

    // public function save()
    // {

    //     $data = [
    //         'code' => $this->code,
    //         'type' => $this->type,
    //         'discount' => $this->discount,
    //         'max_uses' => $this->max_uses,
    //         'expires_at' => $this->expires_at,
    //         'status' => $this->status,
    //         'is_user_specific' => $this->is_user_specific,
    //         'is_category_specific' => $this->is_category_specific,
    //     ];
    //     if ($this->coupon) {
    //         $this->coupon->update($data);
    //     } else {
    //         $this->coupon = Coupon::create($data);
    //     }

    //     // Sync relationships if needed
    //     $this->coupon->users()->sync($this->is_user_specific ? $this->user_ids : []);
    //     $this->coupon->categories()->sync($this->is_category_specific ? $this->category_ids : []);

    //     session()->flash('message', 'Coupon saved successfully!');
    //     toastr()->success('Coupon saved Successfully!');

    //     $this->dispatch('select2Hydrate'); // Refresh Select2 after save
    // }

    public function updatedIsUserSpecific($value)
    {
        if (!$value) {
            $this->user_ids = [];
        }
        $this->dispatch('select2Hydrate');
    }

    public function updatedIsCategorySpecific($value)
    {

        if (!$value) {
            $this->category_ids = [];
        }
        $this->dispatch('select2Hydrate');
    }

    public function updatedUserIds($value)
    {
        $this->user_ids = $value;
    }

    public function updatedCategoryIds($value)
    {
        $this->category_ids = $value;
    }

    public function render()
    {
        return view('admin.livewire.coupon-form');
    }
}
