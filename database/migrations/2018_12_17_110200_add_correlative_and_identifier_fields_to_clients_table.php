<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCorrelativeAndIdentifierFieldsToClientsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('loans', function (Blueprint $table) {
            $table->string('identifier')->nullable();
            $table->string('correlative')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('loans', function (Blueprint $table) {
            $table->dropColumn(['identifier', 'correlative']);
        });
    }
}
