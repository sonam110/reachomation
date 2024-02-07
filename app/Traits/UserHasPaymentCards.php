<?php

namespace App\Traits;

use App\Models\PaymentCard;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Config;

trait UserHasPaymentCards
{
    /**
     * One-to-Many relation with the payment card model
     * @return mixed
     */
    public function payment_cards()
    {
        return $this->hasMany( PaymentCard::class, 'user_id', 'id');
    }

    public function isPaymentCardOwner()
    {
        return ( PaymentCard::where( "user_id", "=", $this->getKey() )->first() ) ? true : false;
    }

    protected function getPaymentCardId( $card )
    {
        if ( is_object( $card ) )
        {
            $card = $card->getKey();
        }
        if ( is_array( $rcard ) && isset( $card[ "id" ] ) )
        {
            $card = $card[ "id" ];
        }
        return $card;
    }

}