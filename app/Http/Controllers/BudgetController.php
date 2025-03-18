<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use App\Models\Category;
use App\Models\Expense;
use App\Models\Entry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class BudgetController extends Controller
{
    /**
     * Obter todos os orçamentos do usuário com os gastos atuais
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Dados inválidos',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Obter o mês atual
            $currentMonth = date('m');
            $currentYear = date('Y');

            // Consultar todos os orçamentos do usuário
            $budgets = Budget::where('user_id', $request->user_id)
                ->orderBy('created_at', 'desc')
                ->get();

            $result = [];

            foreach ($budgets as $budget) {
                // Calcular quanto foi gasto na categoria deste orçamento no mês atual
                $spent = Expense::where('user_id', $request->user_id)
                    ->where('category_id', $budget->category_id)
                    ->whereMonth('expense_date', $currentMonth)
                    ->whereYear('expense_date', $currentYear)
                    ->sum('amount');

                // Obter o nome da categoria
                $category = Category::findOrFail($budget->category_id);

                $result[] = [
                    'id' => $budget->id,
                    'category_id' => $budget->category_id,
                    'category_name' => $category->name,
                    'amount' => $budget->amount,
                    'spent' => $spent,
                    'created_at' => $budget->created_at
                ];
            }

            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao buscar orçamentos',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Criar um novo orçamento
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'category_id' => 'required',
            'amount' => 'required'
        ]);
        // return $request->all();

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Dados inválidos',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Verificar se já existe um orçamento para esta categoria
            $existingBudget = Budget::where('user_id', $request->user_id)
                ->where('category_id', $request->category_id)
                ->first();

            if ($existingBudget) {
                // Atualizar o orçamento existente
                $existingBudget->amount = $request->amount;
                $existingBudget->save();

                return response()->json([
                    'message' => 'Orçamento atualizado com sucesso',
                    'budget' => $existingBudget
                ]);
            }

            // Criar um novo orçamento
            $budget = Budget::create([
                'user_id' => $request->user_id,
                'category_id' => $request->category_id,
                'amount' => $request->amount
            ]);

            return response()->json([
                'message' => 'Orçamento criado com sucesso',
                'budget' => $budget
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao criar orçamento',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remover um orçamento
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            $budget = Budget::findOrFail($id);
            $budget->delete();

            return response()->json([
                'message' => 'Orçamento removido com sucesso'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao remover orçamento',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obter análise de gastos por categoria
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function expensesByCategory(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'period' => 'nullable|in:week,month,year'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Dados inválidos',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $period = $request->period ?? 'month';

            $query = DB::table('expenses')
                ->join('categories', 'expenses.category_id', '=', 'categories.id')
                ->select(
                    'categories.id as category_id',
                    'categories.name as category_name',
                    DB::raw('SUM(expenses.amount) as total_amount')
                )
                ->where('expenses.user_id', $request->user_id)
                ->groupBy('categories.id', 'categories.name')
                ->orderBy('total_amount', 'desc');

            // Filtrar por período
            if ($period === 'week') {
                $query->whereRaw('expenses.expense_date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)');
            } elseif ($period === 'month') {
                $query->whereMonth('expenses.expense_date', date('m'))
                    ->whereYear('expenses.expense_date', date('Y'));
            } elseif ($period === 'year') {
                $query->whereYear('expenses.expense_date', date('Y'));
            }

            $result = $query->get();

            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao buscar gastos por categoria',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obter tendências mensais de entradas e saídas
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function monthlyTrends(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'period' => 'nullable|in:week,month,year'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Dados inválidos',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $period = $request->period ?? 'month';
            $userId = $request->user_id;

            // Definir intervalo de datas com base no período
            $startDate = null;
            $endDate = Carbon::now();
            $groupBy = '';
            $dateFormat = '';

            if ($period === 'week') {
                $startDate = Carbon::now()->subDays(7);
                $groupBy = 'day';
                $dateFormat = 'd/m'; // Formato do dia
            } elseif ($period === 'month') {
                $startDate = Carbon::now()->startOfMonth();
                $groupBy = 'day';
                $dateFormat = 'd/m'; // Formato do dia
            } elseif ($period === 'year') {
                $startDate = Carbon::now()->startOfYear();
                $groupBy = 'month';
                $dateFormat = 'M'; // Formato do mês
            }

            // Obter dados de entradas
            $entries = $this->getDateRangeData(
                $userId,
                'entries',
                'entry_date',
                $startDate,
                $endDate,
                $groupBy,
                $dateFormat
            );

            // Obter dados de despesas
            $expenses = $this->getDateRangeData(
                $userId,
                'expenses',
                'expense_date',
                $startDate,
                $endDate,
                $groupBy,
                $dateFormat
            );

            // Combinar os resultados em um único formato para o gráfico
            $labels = array_unique(array_merge(array_keys($entries), array_keys($expenses)));
            sort($labels);

            $entriesData = [];
            $expensesData = [];

            foreach ($labels as $label) {
                $entriesData[] = $entries[$label] ?? 0;
                $expensesData[] = $expenses[$label] ?? 0;
            }

            return response()->json([
                'labels' => $labels,
                'entries' => $entriesData,
                'expenses' => $expensesData
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao buscar tendências mensais',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Função auxiliar para obter dados agrupados por data
     */
    private function getDateRangeData($userId, $table, $dateColumn, $startDate, $endDate, $groupBy, $dateFormat)
    {
        $query = DB::table($table)
            ->select(
                DB::raw("DATE_FORMAT($dateColumn, '$dateFormat') as date"),
                DB::raw('SUM(amount) as total')
            )
            ->where('user_id', $userId)
            ->whereBetween($dateColumn, [$startDate, $endDate])
            ->groupBy(DB::raw("DATE_FORMAT($dateColumn, '$dateFormat')"))
            ->orderBy(DB::raw("MIN($dateColumn)"));

        $results = $query->get();

        // Transformar resultados em um array associativo
        $data = [];
        foreach ($results as $row) {
            $data[$row->date] = $row->total;
        }

        return $data;
    }
}