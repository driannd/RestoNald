<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Reservations;

class BooktableController extends Controller
{
    public function index() {
        $reservations = Reservations::all();
        return view('reservation', compact('reservations'));
    }
    
    public function bookTable(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|min:4',
            'email' => 'required|email',
            'phone' => 'required|min:4',
            'date_time' => 'required|date',
            'table_number' => 'required|string|in:table1,table2,table3',
            'people' => 'required|integer|min:1',
            'message' => 'nullable',
        ]);
        
        Reservations::create($validatedData);
        return redirect('booking')->with('success', 'Your booking request was sent. We will call back or send and email');

    }

    public function reservasi(){
        $reservations = Reservations::all();
        return view('reservations', compact('reservations'));
    }
}
