<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMaxLoansAndMaxTimeFieldToLevelsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('levels', function (Blueprint $table) {
            $table->integer('max_loans')->default(0);
            $table->integer('max_time')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('levels', function (Blueprint $table) {
            $table->dropColumn('max_loans');
            $table->dropColumn('max_time');
        });
    }
}
