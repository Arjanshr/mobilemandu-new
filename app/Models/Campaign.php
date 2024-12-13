<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    use HasFactory;

    public function products()
    {
        return $this->belongsToMany(Product::class)->withPivot('campaign_price');;
    }

    public function scopeNotStarted($query)
    {
        return $query->where('start_date', '>', Carbon::now());
    }

    public function scopeRunning($query)
    {
        return $query->where('status', 1)
            ->where('start_date', '<=', Carbon::now())
            ->where('end_date', '>=', Carbon::now());
    }

    public function scopeExpired($query)
    {
        return $query->where('end_date', '<', Carbon::now());
    }

    public function scopeTimeUntilStart($query)
    {
        return $query->selectRaw('TIMESTAMPDIFF(SECOND, NOW(), start_date) AS time_until_start');
    }

    // Scope to calculate how long a campaign has been running
    public function scopeTimeSinceStarted($query)
    {
        return $query->selectRaw('TIMESTAMPDIFF(SECOND, start_date, NOW()) AS time_since_started');
    }

    // Scope to calculate how long until a campaign expires
    public function scopeTimeUntilExpiry($query)
    {
        return $query->selectRaw('TIMESTAMPDIFF(SECOND, NOW(), end_date) AS time_until_expiry');
    }

    // Scope to calculate how long a campaign has been expired
    public function scopeTimeSinceExpired($query)
    {
        return $query->selectRaw('TIMESTAMPDIFF(SECOND, end_date, NOW()) AS time_since_expired');
    }

    public function getTimeUntilStartAttribute()
    {
        return Carbon::now()->diffForHumans($this->start_date, true); // Absolute difference
    }

    public function getTimeSinceStartedAttribute()
    {
        return Carbon::parse($this->start_date)->diffForHumans(null, true);
    }

    public function getTimeUntilExpiryAttribute()
    {
        return Carbon::now()->diffForHumans($this->end_date, true);
    }

    public function getTimeSinceExpiredAttribute()
    {
        return Carbon::parse($this->end_date)->diffForHumans(null, true);
    }
}
