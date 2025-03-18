<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Entry;
use App\Models\Expense;
use App\Models\User;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function getMonthlySummary(Request $request)
    {
        $userId = $request->input('user_id');

        // Obter o mês e ano corrente
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        // Calcular o total de entradas do mês corrente
        // $totalEntries = Entry::where('user_id', $userId)
        //                     ->whereYear('created_at', $currentYear)
        //                     ->whereMonth('created_at', $currentMonth)
        //                     ->sum('amount');

        // Recuperar o saldo do usuário
        $totalEntries = User::find($userId);

        // Calcular o total de gastos do mês corrente
        $totalExpenses = Expense::where('user_id', $userId)
            ->whereYear('created_at', $currentYear)
            ->whereMonth('created_at', $currentMonth)
            ->sum('amount');

        return response()->json([
            'total_entries' => $totalEntries->saldo,
            'total_expenses' => $totalExpenses
        ]);
    }
}