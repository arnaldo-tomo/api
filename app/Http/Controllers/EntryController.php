<?php

namespace App\Http\Controllers;

use App\Models\Entry;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EntryController extends Controller
{
    public function createEntry(Request $request)
    {


        $request->validate([
            'valor' => 'required|numeric',
            'user_id' => 'required',
        ]);

 $entry = Entry::create([
            'user_id' => $request->user_id,
            'amount' => $request->valor,
            'entry_date' =>  Carbon::now(),
        ]);

        return response()->json($entry, 201);
    }

    public function getCurrentMonthEntry(Request $request)
    {
        $entry = Entry::where('user_id',  $request->user_id)
                      ->whereMonth('entry_date', Carbon::now())
                      ->sum('amount');

        return response()->json(['current_month_entry' => $entry], 200);
    }
}