<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;


class Coupon extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'code',
        'type',
        'discount',
        'max_uses',
        'uses',
        'expires_at',
        'is_user_specific',
        'is_category_specific',
        'status'
    ];
    protected $casts = [
        'expires_at' => 'datetime', // Make sure 'expires_at' is cast to a Carbon instance
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable();
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'coupon_category');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'coupon_user');
    }

    public function isValidForUser($userId)
    {
        if (!$this->is_user_specific) return true;
        return $this->users()->where('user_id', $userId)->exists();
    }
}
