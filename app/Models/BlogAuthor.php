<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogAuthor extends Model
{
    protected $table = 'blog_author';
    protected $connection = 'blogsearch';
    public $timestamps = false;
}
