<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('product', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255)->nullable();
            $table->double('price')->nullable();
            $table->double('origin_price')->nullable();
            $table->double('discount_price')->nullable();
            $table->string('short_description', 512);
            $table->unsignedInteger('qty')->nullable();
            $table->unsignedInteger('shipping')->nullable();
            $table->float('weight')->nullable();
            $table->text('description')->nullable();
            $table->text('information')->nullable();
            $table->text('review')->nullable();
            $table->string('image', 255)->nullable();
            $table->timestamps();

            //product_category_id
            $table->unsignedBigInteger('product_category_id');
            $table->foreign('product_category_id')->references('id')->on('product_category');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product');
    }
};
