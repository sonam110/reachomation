<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Campaign;

class Inbox extends Model
{
    use HasFactory;
    public function campiagn()
    {
        return $this->belongsTo(Campaign::class,'camp_id','id');
    }
}
