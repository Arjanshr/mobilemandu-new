<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'total_price',
        'discount',
        'grand_total',
        'payment_type',
        'payment_status',
        'status',
        'shipping_address',
        'coupon_code',
        'coupon_discount'
    ];

    public function customer()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function address()
    {
        return $this->belongsTo(Address::class);
    }
    public function order_items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
