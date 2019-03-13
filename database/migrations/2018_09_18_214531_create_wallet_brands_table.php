<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWalletBrandsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('wallet_brands', function (Blueprint $table) {
            $table->char('uuid', 36);
            $table->primary('uuid');
            $table->string('name')->unique();
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('wallet_brands');
    }
}
