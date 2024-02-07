<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Iksaku\Laravel\MassUpdate\MassUpdatable;
class EmailCollection extends Model
{
    use HasFactory ;
    use MassUpdatable ;
}
