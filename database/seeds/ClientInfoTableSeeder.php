<?php

use Illuminate\Database\Seeder;
use Ramsey\Uuid\Uuid;

class ClientInfoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('clients_info')->insert([
            [
                'uuid'                      => Uuid::uuid4(),
                'first_name'                => 'demo',
                'last_name'                 => 'demo last',
                'birth_date'                => '1985-10-31',
                'gender'                    => 'male',
                'dui'                       => '147',
                'address'                   => 'dir',
                'city_id'                   => 1,
                'email'                     => 'lrm@gmail.com',
                'alternative_number_phone'  => '0412',
                'client_uuid'               => '0aa8a7b0-06b4-47c5-919f-bac85f15fc98',
                'created_at'                => now(),
                'updated_at'                => now(),
            ],
        ]);
    }
}
