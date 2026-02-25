<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EmotionSeeder extends Seeder
{
    public function run(): void
    {
        $emotions = [
            'Happy',
            'Sad',
            'Angry',
            'Excited',
            'Anxious',
            'Calm',
            'Frustrated',
            'Content',
        ];
        foreach ($emotions as $emotion) {
            DB::table('emotions')->updateOrInsert(['name' => $emotion]);
        }
    }
}