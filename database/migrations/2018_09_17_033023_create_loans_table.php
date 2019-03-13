<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoansTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('loans', function (Blueprint $table) {
            $table->char('uuid', 36);
            $table->primary('uuid');
            $table->integer('status')->default(1);
            $table->char('client_uuid', 36);
            $table->char('level_amount_uuid', 36);
            $table->text('comment')->nullable();
            $table->char('wallet_uuid', 36);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('loans');
    }
}
