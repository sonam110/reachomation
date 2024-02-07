<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SemrushTrafficUS extends Model
{
    protected $table = 'semrush_history_us';
    protected $connection = 'blogsearch';
    public $timestamps = false;
}
