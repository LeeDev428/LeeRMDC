<?php

// app/Models/Notification.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'message', 'status'];

    // Scope to filter unread notifications
    public function scopeUnread($query)
    {
        return $query->where('status', 'unread');
    }

    // Mark the notification as read
    public function markAsRead()
    {
        $this->status = 'read';
        $this->save();
    }
}
