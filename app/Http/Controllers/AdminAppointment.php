<?php
// filepath: /c:/Users/grafr/RMDC/app/Http/Controllers/AdminAppointment.php
namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\Appointment;
use App\Models\DeclinedAppointment;
use App\Models\Message;

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

public function declinedAppointments()
{
    $declinedAppointments = Appointment::where('status', 'declined')
    ->select('user_id', 'title', 'procedure', 'created_at', 'updated_at')
    ->get();
    return view('admin.declined_appointments', compact('declinedAppointments'));
}

public function deleteAllDeclined() {
    Appointment::where('status', 'declined')->delete();
    DeclinedAppointment::truncate(); // Delete all from the table
    return redirect()->back()->with('success', 'All declined appointments deleted.');
}


public function messageFromAdmin(Request $request, $id, $action)
{
    $appointment = Appointment::findOrFail($id); // Find the appointment by ID

    if ($action == 'decline') {
        $request->validate([
            'message' => 'required|string|max:255' // Validate the reason for declining
        ]);

        // Save the decline message
        Message::create([
            'user_id' => $appointment->user_id,
            'message' => $request->message,
            'is_admin' => true,
            'status' => 'unread'
        ]);

        // Create a record in the declined_appointments table
        DeclinedAppointment::create([
            'appointment_id' => $appointment->id,
            'user_id' => $appointment->user_id,
            'decline_reason' => $request->message, // Using message as decline reason
        ]);

        // Update the appointment status to "declined" and adjust the times
        $appointment->status = 'declined';
        $appointment->start = '2003-04-28 23:59'; // Set a default end time (if necessary)
        $appointment->end = '2003-04-28 23:59';
        $appointment->save(); // Save changes

        // Create a notification for the user (optional)
        Notification::create([
            'user_id' => $appointment->user_id,
            'message' => "Your appointment has been declined."
        ]);

        // Broadcast the status change (optional)
        broadcast(new AppointmentStatusChanged($appointment));

        return response()->json([
            'message' => 'Appointment declined successfully and message sent.'
        ]);
    }

    if ($action === 'accept') {
        $appointment->status = 'accepted';
        $appointment->save();

        // Create a notification for the user
        Notification::create([
            'user_id' => $appointment->user_id,
            'message' => "Your appointment has been accepted."
        ]);

        // Broadcast the status change (optional)
        broadcast(new AppointmentStatusChanged($appointment));

        return response()->json([
            'message' => 'Appointment accepted successfully.'
        ]);
    }

    return response()->json(['message' => 'Invalid action.'], 400);
}


}