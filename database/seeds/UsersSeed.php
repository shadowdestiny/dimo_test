<?php

use Illuminate\Database\Seeder;

class UsersSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'name'                  => 'MR. diimo',
                'username'              => 'diimo',
                'email'                 => 'admin@admin.com',
                'password'              => bcrypt('diimo2018'),
                'type'                  => 1,
                'remember_token'        => str_random(10),
            ],
        ]);
    }
}
