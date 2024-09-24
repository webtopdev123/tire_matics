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
        Schema::create('nano_product_attribute_variant', function (Blueprint $table) {
          $table->id('product_attribute_variant_id');
          $table->string('product_attribute_variant_name');
          $table->integer('product_attribute_id');
          $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nano_product_attribute_variant');
    }
};
