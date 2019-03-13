<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class LevelTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('levels')->insert([
            [
                'uuid'              => Str::uuid(),
                'name'              => 'Plata',
                'key'               => 'silver',
                'order'             => 1,
                'commission'        => 2.00,
                'next_level_amount' => 200.00,
                'max_loans'         => 8,
                'max_time'          => 60,
                'created_at'        => now(),
                'updated_at'        => now(),
            ],
            [
                'uuid'              => Str::uuid(),
                'name'              => 'Oro',
                'key'               => 'gold',
                'order'             => 2,
                'commission'        => 3.00,
                'next_level_amount' => 1000.00,
                'max_loans'         => 20,
                'max_time'          => 60,
                'created_at'        => now(),
                'updated_at'        => now(),
            ],
            [
                'uuid'              => Str::uuid(),
                'name'              => 'Diamante',
                'key'               => 'diamond',
                'order'             => 3,
                'commission'        => 4.00,
                'next_level_amount' => 0.00,
                'max_loans'         => 0,
                'max_time'          => 0,
                'created_at'        => now(),
                'updated_at'        => now(),
            ],
        ]);
    }
}
