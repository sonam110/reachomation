<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionPlan extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'slug',
		'price',
		'credits',
		'parallel_users',
		'size_limit',
		'templates',
		'description',
		'stripe_plan_id',
		'plan_type',
		'site_features',
		'geo_locations',
		'customer_support',
		'chrome_addon',
		'traffic_history',
		'automation',
		'mail_connect',
		'reporting',
		'buy_more_credit',
		'status',
    ];
}
