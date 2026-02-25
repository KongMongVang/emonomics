<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Type;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $expenseType = Type::where('type_name', 'expense')->first();
        $incomeType = Type::where('type_name', 'income')->first();

        if ($expenseType) {
            $expenseCategories = [
                'Food',
                'Transportation',
                'Utilities',
                'Entertainment',
                'Health',
                'Shopping',
                'Education',
                'Other',
            ];
            foreach ($expenseCategories as $cat) {
                DB::table('categories')->updateOrInsert([
                    'category_name' => $cat,
                    'type_id' => $expenseType->type_id,
                ]);
            }
        }
        if ($incomeType) {
            $incomeCategories = [
                'Salary',
                'Business',
                'Investment',
                'Gift',
                'Other',
            ];
            foreach ($incomeCategories as $cat) {
                DB::table('categories')->updateOrInsert([
                    'category_name' => $cat,
                    'type_id' => $incomeType->type_id,
                ]);
            }
        }
    }
}