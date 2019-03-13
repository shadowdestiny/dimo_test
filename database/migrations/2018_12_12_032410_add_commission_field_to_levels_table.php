<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCommissionFieldToLevelsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('levels', function (Blueprint $table) {
            $table->float('commission', 8, 2)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('levels', function (Blueprint $table) {
            $table->dropColumn('commission');
        });
    }
}
