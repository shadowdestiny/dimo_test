<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->char('uuid', 36);
            $table->primary('uuid');
            $table->text('text');
            $table->string('type');
            $table->char('step_uuid', 36);
            $table->integer('order');
            $table->timestamps();

            $table->foreign('step_uuid')
                  ->references('uuid')->on('steps')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('questions');
    }
}
