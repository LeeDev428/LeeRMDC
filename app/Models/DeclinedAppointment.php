<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeclinedAppointment extends Model
{
    use HasFactory;

    protected $table = 'declined_appointments';

    protected $fillable = ['appointment_id', 'user_id', 'decline_reason'];

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
