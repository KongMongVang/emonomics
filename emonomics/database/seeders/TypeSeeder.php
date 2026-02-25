<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            ['type_name' => 'expense'],
            ['type_name' => 'income'],
        ];
        foreach ($types as $type) {
            DB::table('types')->updateOrInsert(['type_name' => $type['type_name']], $type);
        }
    }
}