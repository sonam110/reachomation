<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Cashier\Billable;
use App\Models\SubscriptionPlan;
use App\Traits\UserHasPaymentCards;
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, Billable,UserHasPaymentCards;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'email_verified_at',
        'password',
        'oauth_id',
        'oauth_provider',
        'phone',
        'company',
        'avatar',
        'niches',
        'mailbox_email',
        'mailbox_password',
        'mailbox_type',
        'mailbox_url',
        'is_mailbox_connected',
        'credits',
        'plan',
        'duration',
        'status',
        'plan_started_at',
        'plan_expired_at',
        'country',
        'state',
        'city',
        'postal_code',
        'line1',
        'stripe_id',
        'stripe_customer_id',
        'pm_type',
        'pm_last_four',
        'trial_ends_at',
        'dont_show',
        'is_email_notify',
        'password_token',
        'default_tid',
        'default_card',
        'email_verified_token',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function plan()
    {
        return $this->belongsTo(SubscriptionPlan::class,'plan','id');
    }
}
