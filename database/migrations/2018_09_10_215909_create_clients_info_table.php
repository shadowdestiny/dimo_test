<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientsInfoTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('clients_info', function (Blueprint $table) {
            $table->char('uuid', 36);
            $table->primary('uuid');
            $table->string('first_name');
            $table->string('last_name');
            $table->date('birth_date');
            $table->string('gender');
            $table->string('dui')->unique();
            $table->text('address');
            $table->string('city_id');
            $table->string('email')->unique();
            $table->string('alternative_number_phone');
            $table->char('client_uuid', 36);
            $table->timestamps();

            $table->foreign('client_uuid')
                  ->references('uuid')->on('clients')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('clients_info');
    }
}
