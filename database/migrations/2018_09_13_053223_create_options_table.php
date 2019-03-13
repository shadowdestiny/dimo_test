<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOptionsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('options', function (Blueprint $table) {
            $table->char('uuid', 36);
            $table->primary('uuid');
            $table->string('text');
            $table->char('question_uuid', 36);
            $table->timestamps();

            $table->foreign('question_uuid')
                  ->references('uuid')->on('questions')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('options');
    }
}
