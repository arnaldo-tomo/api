<?php

namespace App\Http\Controllers;

use App\Models\FinancialGoal;
use App\Models\GoalContribution;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class FinancialGoalController extends Controller
{
    /**
     * Obter todas as metas financeiras do usuário
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
            $goals = FinancialGoal::where('user_id', $request->user_id)
                ->orderBy('target_date', 'asc')
                ->get();

            return response()->json($goals);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao buscar metas financeiras',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Criar uma nova meta financeira
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'title' => 'required|string|min:3',
            'target_amount' => 'required|numeric|min:0',
            'initial_amount' => 'nullable|numeric|min:0',
            'target_date' => 'required|date|after:today',
            'description' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Dados inválidos',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $goal = FinancialGoal::create([
                'user_id' => $request->user_id,
                'title' => $request->title,
                'description' => $request->description,
                'target_amount' => $request->target_amount,
                'initial_amount' => $request->initial_amount ?? 0,
                'current_amount' => $request->initial_amount ?? 0,
                'target_date' => $request->target_date
            ]);

            return response()->json([
                'message' => 'Meta financeira criada com sucesso',
                'goal' => $goal
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao criar meta financeira',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remover uma meta financeira
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            $goal = FinancialGoal::findOrFail($id);

            // Primeiro remover todas as contribuições associadas
            GoalContribution::where('goal_id', $id)->delete();

            // Depois remover a meta
            $goal->delete();

            return response()->json([
                'message' => 'Meta financeira removida com sucesso'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao remover meta financeira',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Adicionar uma contribuição a uma meta financeira
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function addContribution(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:0.01'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Dados inválidos',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Encontrar a meta
            $goal = FinancialGoal::findOrFail($id);

            // Verificar se o usuário da requisição é o dono da meta
            if ($goal->user_id != $request->user_id) {
                return response()->json([
                    'message' => 'Você não tem permissão para adicionar contribuições a esta meta'
                ], 403);
            }

            // Iniciar uma transação para garantir consistência
            DB::beginTransaction();

            // Adicionar a contribuição
            $contribution = GoalContribution::create([
                'goal_id' => $id,
                'user_id' => $request->user_id,
                'amount' => $request->amount,
                'contribution_date' => now()
            ]);

            // Atualizar o valor atual da meta
            $goal->current_amount += $request->amount;
            $goal->save();

            DB::commit();

            return response()->json([
                'message' => 'Contribuição adicionada com sucesso',
                'contribution' => $contribution,
                'goal' => $goal
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Erro ao adicionar contribuição',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obter todas as contribuições de uma meta financeira
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getContributions($id)
    {
        try {
            $goal = FinancialGoal::findOrFail($id);
            $contributions = GoalContribution::where('goal_id', $id)
                ->orderBy('contribution_date', 'desc')
                ->get();

            return response()->json($contributions);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao buscar contribuições',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}