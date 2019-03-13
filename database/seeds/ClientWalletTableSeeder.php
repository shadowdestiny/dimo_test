<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ClientWalletTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('client_wallets')->insert([
            [
                'uuid'              => Str::uuid(),
                'wallet_brand_uuid' => DB::table('wallet_brands')->whereName('Momo')->first()->uuid,
                'client_uuid'       => '0aa8a7b0-06b4-47c5-919f-bac85f15fc98',
                'number_account'    => '1234567',
                'created_at'        => now(),
                'updated_at'        => now(),
            ],
        ]);
    }
}
