<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\SubscriptionPlan;
class Subscription extends Model
{
    use HasFactory;
    protected $table = 'subscriptions';


    public function plan()
    {
        return $this->belongsTo(SubscriptionPlan::class,'stripe_price','stripe_plan_id');
    }

}
