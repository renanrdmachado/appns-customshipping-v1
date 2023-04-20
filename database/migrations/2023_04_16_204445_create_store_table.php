<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store', function (Blueprint $table) {
            $table->id();
            $table->string('user_id')->unique();
            $table->longText('nuvemshop_store_id')->nullable();
            $table->longText('nuvemshop_store_token')->nullable();
            $table->longText('nuvemshop_store_data')->nullable();
            $table->longText('payments_status')->nullable();
            $table->longText('payments_data')->nullable();
            $table->longText('payments_next_date')->nullable();
            $table->longText('payments_cus_id')->nullable();
            $table->longText('payments_sub_id')->nullable();
            $table->string('freetrial')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('store');
    }
};
