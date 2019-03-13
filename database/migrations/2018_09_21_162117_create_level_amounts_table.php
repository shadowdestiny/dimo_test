<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLevelAmountsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('level_amounts', function (Blueprint $table) {
            $table->char('uuid', 36);
            $table->primary('uuid');
            $table->char('level_uuid', 36);
            $table->float('amount', 8, 2);
            $table->boolean('available')->default(true);
            $table->timestamps();

            $table->foreign('level_uuid')
                  ->references('uuid')->on('levels')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('level_amounts');
    }
}
