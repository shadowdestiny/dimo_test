<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class WalletBrandsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('wallet_brands')->insert([
            [
                'uuid'       => Str::uuid(),
                'name'       => 'Tigo Money',
                'active'     => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'uuid'       => Str::uuid(),
                'name'       => 'Momo',
                'active'     => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
