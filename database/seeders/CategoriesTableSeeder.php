<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('categories')->insert([
            ['name' => 'Alimentação', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Transporte', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Saúde', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Educação', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Entretenimento', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Outros', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}