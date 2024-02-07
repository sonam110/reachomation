<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CardController extends Controller
{
    protected $cards;
    protected $customer;
    protected $user;

    public function __construct(Request $request) {

        // Middleware Closure, for loading the authorized user into our class
        $this->middleware(function ($request, $next) {
            // Import authorized user
            $this->user = Auth::user();

            // Set Stripe key
            \Stripe\Stripe::setApiKey(env('STRIPE_SECRET', null));

            if ($this->user) {
                // Import our customer from stripe
                $this->customer = $this->user->stripe_id ? \Stripe\Customer::retrieve($this->user->stripe_id) : null;

                // Load our cards from our imported Stripe user
                $this->cards = $this->user->payment_cards ? $this->user->payment_cards : null;
            }

            // Carry on with our request
            return $next($request);
        });
    }
}
