<?php

namespace App\Models;

use App\Models\User;
use App\Models\Appointment\Appointment;
use App\Models\Invoice;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table = 'notifications';

    protected $fillable = [
        'notification_type_id', 
        'title', 
        'data',  
        'from_user', 
        'to_user', 
        'read_at', 
        'is_deleted'
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'from_user');
    }

    public function invoice_user()
    {
        return $this->hasOne(Invoice::class, 'id', 'data');
    }
    
    public function appointment_user()
    {
        return $this->hasOne(Appointment::class, 'id', 'data');
    }

}
