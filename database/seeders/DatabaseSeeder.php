<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
    // Seed para a tabela de despesas (expenses) php artisan db:seed

DB::table('expenses')->insert([
    [
        'user_id' => 1, // Relacionado com o primeiro usuário
        'category_id' => 1, // Supondo que '1' é Alimentação, mas a categoria deve existir
        'amount' => 1500.00,
        'expense_date' => Carbon::create(2023, 9, 5),
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'user_id' => 1, // Relacionado com o segundo usuário
        'category_id' => 2, // Supondo que '2' é Transporte, mas a categoria deve existir
        'amount' => 1000.00,
        'expense_date' => Carbon::create(2023, 9, 10),
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'user_id' => 1, // Relacionado com o segundo usuário
        'category_id' => 1, // Supondo que '2' é Transporte, mas a categoria deve existir
        'amount' => 100.00,
        'expense_date' => Carbon::create(2023, 9, 10),
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'user_id' => 1, // Relacionado com o segundo usuário
        'category_id' => 3, // Supondo que '2' é Transporte, mas a categoria deve existir
        'amount' => 1400.00,
        'expense_date' => Carbon::create(2023, 9, 10),
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'user_id' => 1, // Relacionado com o segundo usuário
        'category_id' => 9, // Supondo que '2' é Transporte, mas a categoria deve existir
        'amount' => 700.00,
        'expense_date' => Carbon::create(2023, 9, 10),
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'user_id' => 1, // Relacionado com o segundo usuário
        'category_id' => 2, // Supondo que '2' é Transporte, mas a categoria deve existir
        'amount' => 1000.00,
        'expense_date' => Carbon::create(2023, 9, 10),
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'user_id' => 1, // Relacionado com o segundo usuário
        'category_id' => 2, // Supondo que '2' é Transporte, mas a categoria deve existir
        'amount' => 1000.00,
        'expense_date' => Carbon::create(2023, 9, 10),
        'created_at' => now(),
        'updated_at' => now(),
    ],
]);

   // Seed para a tabela de entradas (entries)
   DB::table('entries')->insert([
    [
        'user_id' => 1, // Relacionado com o primeiro usuário
        'amount' => 50000.00,
        'entry_date' => Carbon::create(2023, 9, 1),
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'user_id' => 1, // Relacionado com o segundo usuário
        'amount' => 30000.00,
        'entry_date' => Carbon::create(2023, 9, 1),
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'user_id' => 1, // Relacionado com o segundo usuário
        'amount' => 300.00,
        'entry_date' => Carbon::create(2023, 9, 1),
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'user_id' => 1, // Relacionado com o segundo usuário
        'amount' => 310.00,
        'entry_date' => Carbon::create(2023, 9, 1),
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'user_id' => 1, // Relacionado com o segundo usuário
        'amount' => 30000.00,
        'entry_date' => Carbon::create(2023, 9, 1),
        'created_at' => now(),
        'updated_at' => now(),
    ],
]);


    }
}