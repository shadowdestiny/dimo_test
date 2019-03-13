<?php

use App\Setting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SettingLimitsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('settings')->insert([
            [
                'uuid'        => Str::uuid(),
                'key'         => Setting::MAXIMUM_TO_APPLY,
                'description' => 'Máximo al que se puede aplicar',
                'value'       => '45',
            ],
        ]);

        DB::table('settings')->insert([
            [
                'uuid'        => Str::uuid(),
                'key'         => Setting::MINIMUN_TO_APPLY,
                'description' => 'Minimo al que se puede aplicar',
                'value'       => '45',
            ],
        ]);

        DB::table('settings')->insert([
            [
                'uuid'        => Str::uuid(),
                'key'         => Setting::MAXIMUM_NEXT_LEVEL,
                'description' => 'Minimo a aplicar para el siguiente nivel',
                'value'       => '25',
            ],
        ]);

        DB::table('settings')->insert([
            [
                'uuid'        => Str::uuid(),
                'key'         => Setting::PAY_NEXT,
                'description' => 'Pagos siguientes',
                'value'       => '20',
            ],

        ]);
    }
}
