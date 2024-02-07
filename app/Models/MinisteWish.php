<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MinisteWish extends Model
{
    use HasFactory;
    protected $table = 'miniter_wishes';
    protected $connection = 'mysql2';
    public $timestamps = false;
}
