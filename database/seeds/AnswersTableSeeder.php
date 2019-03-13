<?php

use Illuminate\Database\Seeder;

    class AnswersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('answers')->insert([
            [
                'uuid'                      => '0aa8a7b0-06b4-47c5-919f-bac85f15fc98',
                'question_uuid'             => '55efd493-f8cc-444d-b8b2-5355e36d0bbe',
                'client_uuid'               => '0aa8a7b0-06b4-47c5-919f-bac85f15fc98',
                'type'                      => 1,
                'response'                  => 'respuesta',
                'created_at'                => now(),
                'updated_at'                => now(),
            ],
        ]);
    }
}
