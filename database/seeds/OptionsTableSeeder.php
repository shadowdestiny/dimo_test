<?php

use Illuminate\Database\Seeder;

class OptionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('options')->insert([
            [
                'uuid'          => Uuid::uuid4(),
                'text'          => 'Si, es mio',
                'question_uuid' => DB::table('questions')->whereText('¿Es tuyo este dispositivo?')->first()->uuid,
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
        ]);
    }
}
