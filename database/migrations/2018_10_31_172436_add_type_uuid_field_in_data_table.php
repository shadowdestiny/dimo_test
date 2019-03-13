<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTypeUuidFieldInDataTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('client_data', function (Blueprint $table) {
            $table->char('type_uuid', 36)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('client_data', function (Blueprint $table) {
            $table->dropColumn('type_uuid');
        });
    }
}
