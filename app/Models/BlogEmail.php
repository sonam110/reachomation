<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogEmail extends Model
{
    protected $table = 'blog_emails';
    protected $connection = 'blogsearch';
    public $timestamps = false;
}
