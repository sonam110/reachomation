<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appsetting extends Model
{
    protected $fillable = [
        'app_name', 'app_logo', 'email', 'address', 'mobilenum','tax_percentage','seo_keyword', 'seo_description', 'google_analytics','batch_create'
    ];
}
