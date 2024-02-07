<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Userlog extends Model
{
    protected $fillable = [
        'userId', 'ip'
    ];

    public function userInfo()
	{
	    return $this->belongsTo(User::class, 'userId', 'id');
	}

	
}
