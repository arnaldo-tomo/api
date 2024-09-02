<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Entry;
use App\Models\Expense;
use Carbon\Carbon;

class TransactionController extends Controller
{
    public function getTransactions(Request $request)
    {
        $userId = $request->input('user_id');

        // Obter o mês e ano corrente
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        // Buscar as entradas (entries) do mês corrente para o usuário, com base no campo created_at
        $entries = Entry::where('user_id', $userId)
                        ->whereYear('created_at', $currentYear)
                        ->whereMonth('created_at', $currentMonth)
                        ->get();

        // Buscar os gastos (expenses) do mês corrente para o usuário, com base no campo created_at
        $expenses = Expense::where('user_id', $userId)
                           ->whereYear('created_at', $currentYear)
                           ->whereMonth('created_at', $currentMonth)
                           ->get();

        // Combinar entradas e gastos em um único array
        $transactions = $entries->map(function ($entry) {
            return [
                'id' => $entry->id,
                'type' => 'entrada',
                'amount' => $entry->amount,
                'date' => $entry->created_at->format('d/m/y'), // Usando created_at
            ];
        })->merge($expenses->map(function ($expense) {
            return [
                'id' => $expense->id,
                'type' => 'gasto',
                'amount' => $expense->amount,
                'date' => $expense->created_at->format('d/m/y'), // Usando created_at
            ];
        }))->sortBy('date')->values()->take(6);

        return response()->json($transactions);
    }

    public function getFilteredTransactions(Request $request)
    {
        $userId = $request->input('user_id');
        $category = $request->input('category');
        $month = $request->input('month', Carbon::now()->month);
        $year = $request->input('year', Carbon::now()->year);

        // Filtrar as entradas (entries)
        $entriesQuery = Entry::where('user_id', $userId)
                             ->whereYear('created_at', $year)
                             ->whereMonth('created_at', $month);

        // Filtrar as despesas (expenses)
        $expensesQuery = Expense::with('category') // Carrega a relação com categoria
                                ->where('user_id', $userId)
                                ->whereYear('created_at', $year)
                                ->whereMonth('created_at', $month);

        // Se uma categoria foi selecionada, filtrar despesas por categoria
        if ($category) {
            $expensesQuery->whereHas('category', function ($query) use ($category) {
                $query->where('name', $category);
            });
        }

        $entries = $entriesQuery->get();
        $expenses = $expensesQuery->get();

        // Combinar entradas e despesas em um único array de transações
        $transactions = $entries->map(function ($entry) {
            return [
                'id' => $entry->id,
                'type' => 'entrada',
                'amount' => $entry->amount,
                'date' => $entry->created_at->format('d/m/y'),
                'category' => 'Entrada', // Categoria fixa para entradas
            ];
        })->merge($expenses->map(function ($expense) {
            return [
                'id' => $expense->id,
                'type' => 'gasto',
                'amount' => $expense->amount,
                'date' => $expense->created_at->format('d/m/y'),
                'category' => $expense->category ? $expense->category->name : 'Sem Categoria',
            ];
        }))->sortBy('date')->values();

        return response()->json($transactions);
    }
}