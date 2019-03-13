<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddComissionFieldToLoanDetailsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('loan_details', function (Blueprint $table) {
            $table->float('commission', 8, 2);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('loan_details', function (Blueprint $table) {
            $table->dropColumn('commission');
        });
    }
}
