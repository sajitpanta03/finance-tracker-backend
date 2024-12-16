<?php

namespace Database\Seeders;

use App\Models\Expense_category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class Expense_categorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        Expense_category::factory(7)->create();
    }
}
