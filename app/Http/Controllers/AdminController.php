<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\User;
use App\Models\Inventory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Import Log facade

class   AdminController extends Controller
{
    
        public function index()
        {
            // Initialize an empty array for procedure counts
            $proceduresCount = [];
            $procedures = [
                'Pasta',
                'Root Canal',
                'Teeth Whitening',
                'Fillings',
                'Extraction',
                'Cleaning',
                'Checkup'
            ];
        
            // Loop through each procedure and count appointments
            foreach ($procedures as $procedure) {
                $proceduresCount[$procedure] = Appointment::where('procedure', $procedure)->count();
            }
        
            // Count other data
            $appointmentsCount = Appointment::count();
            $pendingAppointmentsCount = Appointment::where('status', 'pending')->count();
        
            // Get the current month, previous month, and next month
            $currentMonth = Carbon::now()->month;
            $currentYear = Carbon::now()->year;
            $previousMonth = Carbon::now()->subMonth()->month;
            $previousYear = Carbon::now()->subMonth()->year;
            $nextMonth = Carbon::now()->addMonth()->month;
            $nextYear = Carbon::now()->addMonth()->year;
        
            // Get the number of days in each month
            $daysInCurrentMonth = Carbon::now()->daysInMonth;
            $daysInPreviousMonth = Carbon::now()->subMonth()->daysInMonth;
            $daysInNextMonth = Carbon::now()->addMonth()->daysInMonth;
        
            // Initialize arrays for appointment data
            $appointmentsThisMonth = [];
            $appointmentsPreviousMonth = [];
            $appointmentsNextMonth = [];
            $acceptedThisMonth = [];
            $declinedThisMonth = [];
            $acceptedPreviousMonth = [];
            $declinedPreviousMonth = [];
            $acceptedNextMonth = [];
            $declinedNextMonth = [];
        
            // Retrieve appointment counts using `start` column instead of `created_at`
            for ($day = 1; $day <= $daysInCurrentMonth; $day++) {
                $appointmentsThisMonth[$day] = Appointment::whereDay('start', $day)
                                                          ->whereMonth('start', $currentMonth)
                                                          ->whereYear('start', $currentYear)
                                                          ->count();
        
                $acceptedThisMonth[$day] = Appointment::whereDay('start', $day)
                                                      ->whereMonth('start', $currentMonth)
                                                      ->whereYear('start', $currentYear)
                                                      ->where('status', 'accepted')
                                                      ->count();
        
                $declinedThisMonth[$day] = Appointment::whereDay('start', $day)
                                                      ->whereMonth('start', $currentMonth)
                                                      ->whereYear('start', $currentYear)
                                                      ->where('status', 'declined')
                                                      ->count();
            }
        
            for ($day = 1; $day <= $daysInPreviousMonth; $day++) {
                $appointmentsPreviousMonth[$day] = Appointment::whereDay('start', $day)
                                                              ->whereMonth('start', $previousMonth)
                                                              ->whereYear('start', $previousYear)
                                                              ->count();
        
                $acceptedPreviousMonth[$day] = Appointment::whereDay('start', $day)
                                                          ->whereMonth('start', $previousMonth)
                                                          ->whereYear('start', $previousYear)
                                                          ->where('status', 'accepted')
                                                          ->count();
        
                $declinedPreviousMonth[$day] = Appointment::whereDay('start', $day)
                                                          ->whereMonth('start', $previousMonth)
                                                          ->whereYear('start', $previousYear)
                                                          ->where('status', 'declined')
                                                          ->count();
            }
        
            for ($day = 1; $day <= $daysInNextMonth; $day++) {
                $appointmentsNextMonth[$day] = Appointment::whereDay('start', $day)
                                                          ->whereMonth('start', $nextMonth)
                                                          ->whereYear('start', $nextYear)
                                                          ->count();
        
                $acceptedNextMonth[$day] = Appointment::whereDay('start', $day)
                                                      ->whereMonth('start', $nextMonth)
                                                      ->whereYear('start', $nextYear)
                                                      ->where('status', 'accepted')
                                                      ->count();
        
                $declinedNextMonth[$day] = Appointment::whereDay('start', $day)
                                                      ->whereMonth('start', $nextMonth)
                                                      ->whereYear('start', $nextYear)
                                                      ->where('status', 'declined')
                                                      ->count();
            }
        
            // Count pending appointments
            $pendingCount = Appointment::where('status', 'pending')->count();
        
            // Get inventory data
            $inventories = Inventory::all();
        
            // Get the total quantity of each category
            $categoryQuantities = DB::table('inventories')
                ->select('category', DB::raw('SUM(quantity) as total_quantity'))
                ->groupBy('category')
                ->get();
        
            // Return the view with all data
            return view('admin.dashboard', compact(
                'proceduresCount',
                'appointmentsThisMonth',
                'appointmentsPreviousMonth',
                'appointmentsNextMonth',
                'acceptedThisMonth',
                'declinedThisMonth',
                'acceptedPreviousMonth',
                'declinedPreviousMonth',
                'acceptedNextMonth',
                'declinedNextMonth',
                'inventories',
                'categoryQuantities',
                'pendingCount'
            ));
        }

    public function getPendingCount(Request $request) 
    {
        $pendingCount = Appointment::where('status', 'pending')->count();
        return response()->json(['pendingCount' => $pendingCount]);
    }
    
    public function patientmanagement()  
    {
        // Count the number of pending appointments
        $pendingCount = Appointment::where('status', 'pending')->count();
    
        // Count the number of upcoming appointments (accepted appointments with a start time greater than the current time)
        $upcomingCount = Appointment::where('status', 'accepted')->count(); 
        
        $userCount = User::count();

        // Store the upcoming count in the session (optional)
        session(['upcoming_count' => $upcomingCount]);
        session(['userCount' => $userCount]);
        
        // Pass both counts to the view
        return view('admin.patient-management', compact('pendingCount', 'upcomingCount','userCount')); 
    }
    
    
    
    

    public function upcomingAppointments()
    {
        // Fetch all appointments ordered by created_at ascending (or descending if you prefer)
        $appointments = Appointment::orderBy('created_at', 'asc')->get();
    
        // Get the count of appointments with 'pending' status
        $pendingCount = Appointment::where('status', 'pending')->count();
    
        // Count of all upcoming appointments
        $upcomingCount = $appointments->count();
    
        // Check if there are new appointments and create a notification message
        $recentlyBooked = $appointments->first(); // Get the most recent appointment if it exists
        if ($recentlyBooked) {
            session()->flash('notification', "A new appointment has been booked by user ID: {$recentlyBooked->user_id}");
        }
    
        // Set the session variable for upcoming appointments count (optional)
        session(['upcoming_count' => $upcomingCount]);
    
        // Log all appointments for debugging
        logger()->info('All appointments:', $appointments->toArray());
    
        // Pass both pendingCount and upcomingCount to the view
        return view('admin.upcoming_appointments', [
            'appointments' => $appointments,
            'pendingCount' => $pendingCount,  // Include pending appointments count
            'upcomingCount' => $upcomingCount // Include upcoming appointments count
        ]);
    }
    

    public function bookAppointment($request)
{
    // Validate and create a new appointment
    $appointment = Appointment::create($request->all());

    // Flash a success message (optional)
    session()->flash('success', 'Appointment booked successfully!');

    // Redirect to the upcoming appointments page to refresh the view
    return redirect()->route('admin.upcoming_appointments');
}


public function checkNotifications()
{
    // Count appointments that are new (e.g., added in the last few minutes)
    $newAppointmentsCount = Appointment::where('is_seen', false)->count();

    return response()->json(['newAppointmentsCount' => $newAppointmentsCount]);
}

public function patientInformation(Request $request)
{
    // Initialize the query builder
    $query = User::query();

    // If there's a search query, filter the users by the specified fields
    if ($request->has('search') && $request->search != '') {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->where('id', 'like', "%$search%")
              ->orWhere('name', 'like', "%$search%")
              ->orWhere('email', 'like', "%$search%")
              ->orWhere('usertype', 'like', "%$search%")
              ->orWhere('created_at', 'like', "%$search%")
              ->orWhere('updated_at', 'like', "%$search%");
        });
    }

    // Fetch the filtered users, ordered by created_at in descending order
    $user = $query->latest()->get();

    // Log the filtered data (optional)
    logger()->info('Filtered Data:', $user->toArray());

    $pendingCount = Appointment::where('status', 'pending')->count();

    // Return the view with the filtered users
    return view('admin.patient_information', compact('user','pendingCount'));
}

     public function inventoryAdmin()
     {
        return view('admin.inventory_admin');
     }

     public function realupcomingAppointments()
     {
         // Fetch all appointments ordered by created_at descending
         $appointments = Appointment::orderBy('created_at', 'desc')->get();
     
         // Count of upcoming appointments
         $upcomingCount = $appointments->count();
     
         // Check if there are new appointments and create a notification message
         $recentlyBooked = $appointments->first(); // Get the most recent appointment if it exists
         if ($recentlyBooked) {
             session()->flash('notification', "A new appointment has been booked by user ID: {$recentlyBooked->user_id}");
         }
     
         // Reset notification count only if the admin has viewed the notifications
         session(['upcoming_count' => $upcomingCount]);
     
         // Log all appointments for debugging
         logger()->info('All appointments:', $appointments->toArray());
     
         return view('admin.appointments', ['appointments' => $appointments]);
     }
  
     public function acceptedAppointments() 
{
    // Fetch all accepted appointments ordered by start date
    $acceptedAppointments = Appointment::where('status', 'accepted')
                                       ->orderBy('start', 'asc') // Sort by start time
                                       ->get();
    
    // Count the number of pending appointments
    $pendingCount = Appointment::where('status', 'pending')->count();

    // Count of accepted appointments
    $upcomingCount = $acceptedAppointments->count();
    
    // Check if there are any upcoming appointments and create a notification message
    $recentlyBooked = $acceptedAppointments->first(); // Get the most recent accepted appointment if it exists
    if ($recentlyBooked) {
        session()->flash('notification', "A new appointment has been accepted for user ID: {$recentlyBooked->user_id}");
    }

    // Pass accepted appointments and counts to the view
    return view('admin.appointments', [
        'acceptedAppointments' => $acceptedAppointments, // Passed accepted appointments
        'upcomingCount' => $upcomingCount, // Passed upcoming count
        'pendingCount' => $pendingCount, // Passed pending count
    ]);
}

     
}
