<?php

use App\Level;
use Illuminate\Database\Seeder;

class UpdateOrderLevelSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $level = Level::where("key","=","bronze")->first();
        $level->order = 1;
        $level->save();

        $level = Level::where("key","=","silver")->first();
        $level->order = 2;
        $level->save();

        $level = Level::where("key","=","gold")->first();
        $level->order = 3;
        $level->save();
    }
}
