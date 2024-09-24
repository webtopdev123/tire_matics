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
        Schema::create('nano_product_category', function (Blueprint $table) {
          $table->id('category_id');
          $table->string('category_name');
          $table->integer('parent_id')->default(0)->nullable();
          $table->integer('merchant_id');
          $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nano_product_category');
    }
};
