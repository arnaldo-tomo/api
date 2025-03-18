<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BudgetController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use Illuminate\Http\Request;

use App\Http\Controllers\EntryController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\FinancialGoalController;
use App\Http\Controllers\GoalContributionController;
use App\Http\Controllers\StatementController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

// Route::middleware('auth:sanctum')->group(function () {
Route::post('/entries', [EntryController::class, 'createEntry']);
Route::get('/entries/current-month', [EntryController::class, 'getCurrentMonthEntry']);

Route::post('/expenses', [ExpenseController::class, 'createExpense']);
Route::get('/expenses/current-month', [ExpenseController::class, 'getCurrentMonthExpenses']);
Route::get('/expenses/highest', [ExpenseController::class, 'getHighestExpenses']);

Route::get('/statement', [StatementController::class, 'getStatement']);

Route::get('/categories', [CategoryController::class, 'index']);
Route::post('/categories', [CategoryController::class, 'store']);
Route::put('/categories/{id}', [CategoryController::class, 'update']);
Route::delete('/categories/{id}', [CategoryController::class, 'destroy']);


Route::get('/transacoes', [TransactionController::class, 'getTransactions']);
Route::get('/filtered-transactions', [TransactionController::class, 'getFilteredTransactions']);


// Route::middleware('web')->group(function () {
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);
// Rotas para recuperação de senha com OTP
Route::post('/send-otp', [AuthController::class, 'sendOtp']);
Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);
Route::post('/reset-password-with-token', [AuthController::class, 'resetPasswordWithToken']);

// Rotas para o perfil do usuário
Route::get('/user/{id}', [AuthController::class, 'getUser']);
Route::put('/user/{id}', [AuthController::class, 'updateUser']);
// Rotas protegidas (requerem autenticação)

// Rotas para orçamentos
Route::get('/budgets', [BudgetController::class, 'index']);
Route::post('/budgets', [BudgetController::class, 'store']);
Route::delete('/budgets/{id}', [BudgetController::class, 'destroy']);

// Rotas para análise financeira
Route::get('/expenses-by-category', [BudgetController::class, 'expensesByCategory']);
Route::get('/monthly-trends', [BudgetController::class, 'monthlyTrends']);


// Rotas para metas financeiras
Route::get('/financial-goals', [FinancialGoalController::class, 'index']);
Route::post('/financial-goals', [FinancialGoalController::class, 'store']);
Route::delete('/financial-goals/{id}', [FinancialGoalController::class, 'destroy']);
Route::post('/financial-goals/{id}/contributions', [FinancialGoalController::class, 'addContribution']);
Route::get('/financial-goals/{id}/contributions', [FinancialGoalController::class, 'getContributions']);

// Rotas para contribuições às metas financeiras
Route::get('/financial-goals/{goalId}/contributions', [GoalContributionController::class, 'index']);
Route::post('/financial-goals/{goalId}/contributions', [GoalContributionController::class, 'store']);
Route::delete('/contributions/{id}', [GoalContributionController::class, 'destroy']);
Route::middleware('auth:sanctum')->group(function () {
    // Rota para obter dados do usuário logado
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Rota para logout
    Route::post('/logout', [AuthController::class, 'logout']);
});
Route::post('/logout', [AuthController::class, 'logout']);
Route::get('/user', [AuthController::class, 'user'])->middleware('auth');
// });

Route::get('/monthly-summary', [DashboardController::class, 'getMonthlySummary']);
