<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Message;
use App\Models\User;  // Import the User model

class MessageController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            // Retrieve all messages for the authenticated user (patient)
            $messages = Message::with('user')  // Assuming the Message model has a user relationship
                ->where('user_id', Auth::id())  // Ensure we're fetching messages for the logged-in user
                ->orderBy('created_at', 'asc')
                ->get();
            
            // Retrieve the logged-in user's details
            $selectedUser = Auth::user();  // Fetch the currently authenticated user (patient)
            
            
            // Pass both messages and the selected user to the view
            return view('messages.index', compact('messages', 'selectedUser'));
        } else {
            return redirect()->route('login')->with('error', 'Please log in to view your messages.');
        }
    }

    public function store(Request $request)
    {
        // Validate message input
        $request->validate([
            'message' => 'required|string|max:255',
        ]);

        // Store the message in the database
        Message::create([
            'user_id' => Auth::id(),
            'message' => $request->message,
            'status' => 'unread'  // Ensure status is set when the message is created
        ]);

        return back()->with('success', 'Message sent successfully.');
    }

    // Send a reply
    public function reply(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'message' => 'required|string',
        ]);

        // Save the admin's reply
        Message::create([
            'user_id' => $request->user_id,
            'message' => $request->message,
            'is_admin' => true,
            'status' => 'unread',
        ]);

        return redirect()->back()->with('success', 'Reply sent successfully!');
    }

 

public function unreadMessagesCount()
{
    $unreadCount = Message::where('user_id', Auth::id())
        ->where('status', 'unread')
        ->count();

    return response()->json(['count' => $unreadCount]);
}

public function markMessagesAsRead()
{
    Message::where('user_id', Auth::id())
        ->where('status', 'unread')
        ->update(['status' => 'read']);

    return response()->json(['success' => true]);
}

}
