<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;
use Illuminate\Support\Facades\DB;

class ChartController extends Controller
{
    public function index()
    {
        // Query the 'appointments' table and count how many times each procedure appears
        $procedures = Appointment::select('procedure', DB::raw('count(*) as count'))
            ->groupBy('procedure')
            ->get();

        // Prepare data for the chart
        $data = [];
        $labels = [];

        // Loop through the procedures and prepare the data for the chart
        foreach ($procedures as $procedure) {
            $data[] = $procedure->count;
            $labels[] = $procedure->procedure;
        }

        // Pass the data and labels to the view
        return view('chart', compact('data', 'labels'));
    }
}
