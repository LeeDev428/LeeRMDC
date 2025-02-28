<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProcedurePrice;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;
use App\Models\Appointment;

class UserController extends Controller
{
    public function index(Request $request, $id = null, $action = null)
{
    $procedurePrices = ProcedurePrice::all(); // Fetch all procedure prices
    $userId = Auth::id();
    
    // Get unread notifications (limit to latest 1)
    $unreadNotifications = Notification::where('user_id', $userId)
        ->where('status', 'unread')
        ->latest()
        ->first();  // Get the most recent unread notification
    
    // Get all notifications
    $allNotifications = Notification::where('user_id', $userId)
        ->latest()
        ->get();

    // Check if an appointment ID and action are passed
    if ($id && $action) {
        // Find the appointment
        $appointment = Appointment::findOrFail($id);
        
        // Handle appointment action (accept/decline)
        if ($action === 'accept') {
            $appointment->status = 'accepted';
            $message = "Appointment Title named {$appointment->procedure} has been accepted.";
        } elseif ($action === 'decline') {
            $appointment->status = 'declined';
            $message = "Appointment Title named {$appointment->procedure} has been declined.";
        } else {
            return redirect()->back()->with('error', 'Invalid action.');
        }
        
        // Save updated appointment status
        $appointment->save();
        
        // Create a notification for the user
        Notification::create([
            'user_id' => $appointment->user_id,
            'message' => $message,
        ]);
        
        // Redirect back with the success message
        return redirect()->back()->with('success', $message);
    }

    // Return the dashboard view and pass data
    return view('dashboard', [
        'unreadNotifications' => $unreadNotifications,
        'allNotifications' => $allNotifications,
        'appointments' => Appointment::where('user_id', $userId)->latest()->first(), // Get the most recent appointment
        'procedurePrices' => $procedurePrices
    ]);
}
}