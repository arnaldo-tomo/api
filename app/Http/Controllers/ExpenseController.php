<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExpenseController extends Controller
{
    public function createExpense(Request $request)
    {

        $expense = Expense::create([
            'user_id' => $request->user_id,
            'category_id' => $request->category,
            'amount' => $request->expenseValue,
            'expense_date' => Carbon::now(),
        ]);

         // Encontrar o usuário
         $user = User::find($request->user_id);
         $user->saldo -= $request->expenseValue;
         $user->save();

        return response()->json($expense, 201);
    }

    public function getCurrentMonthExpenses(Request $request)
    {
        $expenses = Expense::where('user_id', $request->user_id)
                           ->whereMonth('expense_date', Carbon::now())
                           ->sum('amount');

        return response()->json(['current_month_expenses' => $expenses], 200);
    }

    public function getHighestExpenses(Request $request)
    {
        $highestExpenses = Expense::where('user_id', $request->user_id)
                                  ->whereMonth('expense_date', Carbon::now())
                                  ->orderBy('amount', 'desc')
                                //   ->take(6)
                                  ->get();

        return response()->json($highestExpenses, 200);
    }
}