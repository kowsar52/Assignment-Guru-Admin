<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->integer('category')->nullable();
            $table->integer('customer')->nullable();
            $table->dateTime('deadline')->nullable();
            $table->longText('description')->nullable();
            $table->string('discount')->nullable();
            $table->dateTime('for_final_date')->nullable();
            $table->dateTime('initial_deadline')->nullable();
            $table->boolean('is_private')->nullable();
            $table->integer('language')->nullable();
            $table->integer('level')->nullable();
            $table->integer('number_of_sources')->nullable();
            $table->string('offer_code')->nullable();
            $table->string('price')->nullable();
            $table->string('product')->nullable();
            $table->string('promocode')->nullable();
            $table->string('quantity')->nullable();
            $table->integer('service')->nullable();
            $table->integer('space')->nullable();
            $table->integer('subject')->nullable();
            $table->integer('citiation_style')->nullable();
            $table->string('status')->nullable();
            $table->longText('topic')->nullable();
            $table->integer('words_count')->nullable();
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
        Schema::dropIfExists('orders');
    }
}
