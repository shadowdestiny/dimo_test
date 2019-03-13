<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPaydayFieldToLoanDetailsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('loan_details', function (Blueprint $table) {
            $table->date('payday');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('loan_details', function (Blueprint $table) {
            $table->dropColumn('payday');
        });
    }
}
