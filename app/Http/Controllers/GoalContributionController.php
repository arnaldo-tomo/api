<?php

namespace App\Http\Controllers;

use App\Models\FinancialGoal;
use App\Models\GoalContribution;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class GoalContributionController extends Controller
{
    /**
     * Obter todas as contribuições de uma meta financeira
     *
     * @param  int  $goalId
     * @return \Illuminate\Http\JsonResponse
     */
    public function index($goalId)
    {
        try {
            // Verificar se a meta existe
            $goal = FinancialGoal::findOrFail($goalId);

            // Obter todas as contribuições ordenadas pela data mais recente
            $contributions = GoalContribution::where('goal_id', $goalId)
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

    /**
     * Adicionar uma nova contribuição a uma meta financeira
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $goalId
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request, $goalId)
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
            $goal = FinancialGoal::findOrFail($goalId);

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
                'goal_id' => $goalId,
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
     * Remover uma contribuição
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            // Encontrar a contribuição
            $contribution = GoalContribution::findOrFail($id);

            // Encontrar a meta associada
            $goal = FinancialGoal::findOrFail($contribution->goal_id);

            // Iniciar uma transação
            DB::beginTransaction();

            // Atualizar o valor atual da meta
            $goal->current_amount -= $contribution->amount;
            $goal->save();

            // Remover a contribuição
            $contribution->delete();

            DB::commit();

            return response()->json([
                'message' => 'Contribuição removida com sucesso'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Erro ao remover contribuição',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}