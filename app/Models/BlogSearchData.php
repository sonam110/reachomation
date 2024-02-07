<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogSearchData extends Model
{
    protected $table = 'blog_search_data';
    protected $connection = 'blogsearch';
    public $timestamps = false;
}
