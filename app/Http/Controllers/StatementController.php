<?php

namespace App\Http\Controllers;

use App\Models\Entry;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StatementController extends Controller
{
    public function getStatement()
    {
        $entries = Entry::where('user_id', 1)
                        ->whereMonth('entry_date', now()->month)
                        ->sum('amount');

        $expenses = Expense::where('user_id', 1)
                           ->whereMonth('expense_date', now()->month)
                           ->sum('amount');

        $balance = $entries - $expenses;

        return response()->json([
            'current_month_entry' => $entries,
            'current_month_expenses' => $expenses,
            'balance' => $balance,
        ], 200);
    }
}