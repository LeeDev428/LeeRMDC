<?php
// filepath: /c:/Users/grafr/RMDC/app/Http/Controllers/AdminAppointment.php
namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\Appointment;
use App\Models\DeclinedAppointment;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Events\AppointmentStatusChanged;

class AdminAppointment extends Controller
{

    
    public function handleAction(Request $request, $id, $action)
    {
        // Find the appointment by its ID
        $appointment = Appointment::findOrFail($id);
    
        if ($action === 'decline') {
            // Create a record in the declined_appointments table
            DeclinedAppointment::create([
                'appointment_id' => $appointment->id,
                'user_id' => $appointment->user_id,
                'decline_reason' => $request->input('decline_reason', 'No reason provided'), // Optional decline reason
            ]);
    
            // Update the appointment status to 'declined'
            $appointment->status = 'declined';
    
            // Set a specific date (April 28, 2003) with any time (e.g., 12:00:00)
            $appointment->time = '12:00:00'; // Default time value
            $appointment->start = '2003-04-28 12:00:00';  // Set specific date and time for start
            $appointment->end = '2003-04-28 13:00:00';    // Set specific date and time for end (e.g., 1 hour later)
    
            // Save the updated appointment with cleared fields
            $appointment->save();
    
            // Set a message for notification
            $message = "Your appointment has been declined.";
    
            // Create a notification for the user (optional)
            Notification::create([
                'user_id' => $appointment->user_id,
                'message' => $message,
            ]);
    
            // Broadcast the status change (optional)
            broadcast(new AppointmentStatusChanged($appointment));
    
            // Return with success message
            return redirect()->back()->with('success', "Appointment has been declined and moved to declined appointments.");
        }
    
        // Handle other actions (like accept)
        if ($action === 'accept') {
            $appointment->status = 'accepted';
            $message = "Your appointment has been accepted.";
        } else {
            return redirect()->back()->with('error', 'Invalid action.');
        }
    
        // Save the updated appointment status (if needed)
        $appointment->save();
    
        // Create a notification for the user
        Notification::create([
            'user_id' => $appointment->user_id,
            'message' => $message,
        ]);
    
        // Broadcast the status change (optional)
        broadcast(new AppointmentStatusChanged($appointment));
    
        // Return a success message
        return redirect()->back()->with('success', "Appointment has been {$action}ed.");
    }
    



    public function markNotificationsAsRead(Request $request)
    {
        Notification::where('user_id', Auth::id())
                    ->where('status', 'unread')
                    ->update(['status' => 'read']);
    
        return response()->json(['success' => true]);
    }

    public function fetchNotifications()
    {
        $notifications = Notification::where('user_id', Auth::id())
            ->where('status', 'unread') // Ensure it only counts unread notifications
            ->latest()
            ->take(10)
            ->get();
    
        $unreadCount = Notification::where('user_id', Auth::id())
            ->where('status', 'unread')
            ->count();
    
        return response()->json([
            'notifications' => $notifications,
            'unreadCount' => $unreadCount // Send unread count
        ]);
    }
    
    public function markAllAsRead()
    {
        Notification::where('user_id', Auth::id())
            ->where('status', 'unread')
            ->update(['status' => 'read']);

        return response()->json(['message' => 'All notifications marked as read']);
    }
    

public function unreadNotificationCount()
{
    $unreadCount = Notification::where('user_id', Auth::id())
        ->where('status', 'unread')
        ->count();

    return response()->json(['unreadCount' => $unreadCount]);
}

public function getUnreadCount()
{
    $unreadCount = Notification::where('user_id', Auth::id())
        ->whereNull('read_at') // Only count unread notifications
        ->count();

    return response()->json(['unreadCount' => $unreadCount]);
}

    
}