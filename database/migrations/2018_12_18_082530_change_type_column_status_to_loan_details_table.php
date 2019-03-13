<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeTypeColumnStatusToLoanDetailsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('loan_details', function (Blueprint $table) {
            $table->dropColumn('status');
        });
        Schema::table('loan_details', function (Blueprint $table) {
            $table->integer('status')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('loan_details', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
}
