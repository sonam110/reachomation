<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Iksaku\Laravel\MassUpdate\MassUpdatable;
use App\Models\EmailCollection;
use App\Models\Campaign;
use App\Models\User;
use App\Models\BlackListEmail;
class SendCampaignMail extends Model
{
    use HasFactory;
    use MassUpdatable;

    public function fromUser()
    {
        return $this->belongsTo(EmailCollection::class, 'from_email', 'email');
    }

    public function sendMails()
    {
        return $this->hasMany(SendCampaignMail::class, 'camp_id', 'camp_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function campData()
    {
        return $this->belongsTo(Campaign::class, 'camp_id', 'id');
    }
    

    
}
