<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientWalletsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('client_wallets', function (Blueprint $table) {
            $table->char('uuid', 36);
            $table->primary('uuid');
            $table->char('wallet_brand_uuid', 36);
            $table->char('client_uuid', 36);
            $table->string('number_account');
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('client_wallets');
    }
}
