<?php

use App\Setting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SettingTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('settings')->insert([
            [
                'uuid'        => Str::uuid(),
                'key'         => Setting::ANNUAL_RATE,
                'description' => 'Tasa Anual',
                'value'       => '90',
            ],
            [
                'uuid'        => Str::uuid(),
                'key'         => Setting::LATE_FEE,
                'description' => 'Tasa de Mora',
                'value'       => '50',
            ],
            [
                'uuid'        => Str::uuid(),
                'key'         => Setting::PERIODS,
                'description' => 'Periodos',
                'value'       => '2',
            ],
        ]);
    }
}
