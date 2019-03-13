<?php

use Illuminate\Database\Seeder;

class ClientsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('clients')->insert([
            [
                'uuid'         => '0aa8a7b0-06b4-47c5-919f-bac85f15fc98',
                'number_phone' => '70600162',
                'verified'     => true,
                'level_uuid'   => DB::table('levels')->where('key', 'silver')->first()->uuid,
                'status'       => 'initial',
                'created_at'   => now(),
            ],
        ]);
    }
}
