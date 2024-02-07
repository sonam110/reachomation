<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class google_auth_user extends Model
{
    use HasFactory;
    protected $table = 'google_auth_user';
}
