<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionBenefit extends Model
{
    use HasFactory;
    protected $fillable = ['description', 'subscription_id'];




    public function scopeOfSubscription($query, $subscriptionId)
    {
        return $query->where('subscription_id', $subscriptionId);
    }
}
