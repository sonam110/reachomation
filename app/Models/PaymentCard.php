<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
class PaymentCard extends Model
{
    use HasFactory;
    protected $fillable = ['token', 'details', 'is_default'];

    protected $casts = [
        'is_default' => 'boolean',
        'details' => 'array'
    ];

    public function __construct( array $attributes = [ ] )
    {
      parent::__construct( $attributes );
      $this->table = 'payment_cards';
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
