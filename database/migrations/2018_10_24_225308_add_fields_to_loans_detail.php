<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToLoansDetail extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('loan_details', function (Blueprint $table) {
            $table->float('balance_total');
            $table->float('balance_debt')->nullable();
            $table->float('balance_total_debt')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('loan_details', function (Blueprint $table) {
            $table->dropColumn('balance_total');
            $table->dropColumn('balance_debt');
        });
    }
}
