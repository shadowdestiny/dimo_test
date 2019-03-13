<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGlobalAmortizationTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('global_amortization', function (Blueprint $table) {
            $table->char('uuid', 36);
            $table->primary('uuid');
            $table->char('loan_uuid', 36);
            $table->integer('day');
            $table->float('pay', 8, 2)->nullable();
            $table->float('balance', 8, 2);
            $table->float('interest', 8, 2);
            $table->float('commission', 8, 2);
            $table->float('comission_prepayment', 8, 2);
            $table->float('balance_total', 8, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('global_amortization');
    }
}
