<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProcedurePrice;

class ProcedurePriceController extends Controller
{
    // Show the form with existing prices
    public function index()
    {
        $procedures = ProcedurePrice::all();  // Get all procedure prices
        return view('admin.procedure_prices', compact('procedures'));
    }

    public function store(Request $request)
{
    // Validate the request
    $validated = $request->validate([
        'procedure_name' => 'required|string|max:255',  // Procedure name must be a string and not too long
        'price' => 'required|numeric|min:0',  // Price must be a number and not negative
        'duration' => 'required|string',  // Duration must be a string
    ]);

    // Create a new ProcedurePrice record
    ProcedurePrice::create([
        'procedure_name' => $validated['procedure_name'],
        'price' => $validated['price'],
        'duration' => $validated['duration'],
    ]);

    // Redirect back with success message
    return redirect()->route('admin.procedure_prices')->with('success', 'New procedure price added successfully.');
}


    // Handle the update request
    public function update(Request $request, $id)
    {
        // Validate the request
        $validated = $request->validate([
            'price' => 'required|numeric|min:0', // Price must be a number and not negative
            'duration' => 'required|string',    // Duration must be a string
        ]);

        // Find the procedure by ID and update the price and duration
        $procedure = ProcedurePrice::findOrFail($id);
        $procedure->price = $validated['price'];
        $procedure->duration = $validated['duration'];
        $procedure->save(); // Save the updated record

        // Redirect back with a success message
        return redirect()->route('admin.procedure_prices')->with('success', 'Procedure price updated successfully.');
    }
}
