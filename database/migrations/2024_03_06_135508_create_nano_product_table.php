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
        Schema::create('nano_product', function (Blueprint $table) {
          $table->id('product_id');
          $table->string('product_sku', 64);
          $table->integer('product_color');
          $table->string('product_height', 14);
          $table->string('product_length', 14);
          $table->string('product_width', 14);
          $table->string('product_diameter', 14);
          $table->string('product_fit', 14);
          $table->string('product_abc_size', 14);
          $table->decimal('product_regular_price', 14, 2);
          $table->decimal('product_sale_price',14,2);
          $table->integer('product_position');
          $table->string('product_image');
          $table->boolean('product_status');
          $table->integer('product_group_id')->index();
          $table->integer('merchant_id');
          $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nano_product');
    }
};
