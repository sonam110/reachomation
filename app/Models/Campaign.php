<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\EmailCollection;
use App\Models\User;
use App\Models\SendCampaignMail;
use App\Models\BlackListEmail;
use Illuminate\Support\Str;
use Iksaku\Laravel\MassUpdate\MassUpdatable;

class Campaign extends Model
{
    use HasFactory;
        use MassUpdatable;
    protected $table = 'campaigns';

    public static function boot() {
        parent::boot();
        self::creating(function ($model) {
            $model->uuid = (string) Str::uuid();
        });
    }

     public function fromUser()
    {
        return $this->belongsTo(EmailCollection::class, 'mail_account_id', 'id');
    }
    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function parent()
    {
         return $this->belongsTo(Campaign::class,'is_parent', 'id');
    }


    public function children()
    {
        return $this->hasMany(self::class, 'is_parent');
    }

    public function grandchildren()
    {
        return $this->children()->with('grandchildren');
    }
    public function sendMails()
    {
         return $this->hasMany(SendCampaignMail::class, 'camp_id', 'id');
    }
    public function blackList()
    {
        return $this->hasMany(BlackListEmail::class, 'from', 'from_email');
    }
}
