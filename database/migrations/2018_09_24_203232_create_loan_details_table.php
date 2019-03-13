<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoanDetailsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('loan_details', function (Blueprint $table) {
            $table->char('uuid', 36);
            $table->primary('uuid');
            $table->integer('number_fee');
            $table->float('fee', 8, 4);
            $table->float('interest', 8, 4);
            $table->float('capital', 8, 4);
            $table->float('balance', 8, 4);
            $table->char('loan_uuid', 36);
            $table->boolean('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('loan_details');
    }
}
