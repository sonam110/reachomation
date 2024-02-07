<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogjudgementPool extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'blogjudgement_pool';
    protected $connection = 'pool';
}
