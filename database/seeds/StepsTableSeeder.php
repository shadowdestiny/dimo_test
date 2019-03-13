<?php

use Illuminate\Database\Seeder;
use Ramsey\Uuid\Uuid;

class StepsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('steps')->insert([
            [
                'uuid'        => Uuid::uuid4(),
                'name'        => 'Datos Generales',
                'order'       => 1,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'uuid'        => Uuid::uuid4(),
                'name'        => 'Datos del Dispositivo',
                'order'       => 2,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'uuid'        => Uuid::uuid4(),
                'name'        => 'Estudios Personales',
                'order'       => 3,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
        ]);
    }
}
