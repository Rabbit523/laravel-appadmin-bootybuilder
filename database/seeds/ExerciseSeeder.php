<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ExerciseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 0; $i < 10; $i++) {
            DB::table('exercises')->insert([
                'title' => Str::random(10),
                'description' => Str::random(10),
                'series' => 1,
                'repetitions' => 1,
                'standalone' => 0,
                'format_id' => 1,
                'views' => 1,
                'published' => 0,
                'created_at' => DB::raw('CURRENT_TIMESTAMP'),
                'updated_at' => DB::raw('CURRENT_TIMESTAMP'),
            ]);
        }
    }
}
