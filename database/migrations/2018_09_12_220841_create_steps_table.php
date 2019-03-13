<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStepsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('steps', function (Blueprint $table) {
            $table->char('uuid', 36);
            $table->primary('uuid');
            $table->string('name')->unique();
            $table->integer('order')->unsigned()->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('steps');
    }
}
