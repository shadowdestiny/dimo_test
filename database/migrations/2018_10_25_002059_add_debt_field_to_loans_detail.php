<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDebtFieldToLoansDetail extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('loan_details', function (Blueprint $table) {
            $table->float('debt', 8, 2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('loan_details', function (Blueprint $table) {
            $table->dropColumn('debt');
        });
    }
}
