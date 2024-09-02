<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use Illuminate\Http\Request;

use App\Http\Controllers\EntryController;
use App\Http\Controllers\ExpenseController;
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
// });

// Route::middleware('web')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user'])->middleware('auth');
// });

Route::get('/monthly-summary', [DashboardController::class, 'getMonthlySummary']);