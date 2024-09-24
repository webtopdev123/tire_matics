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
      Schema::create('nano_product_group', function (Blueprint $table) {
        $table->id('product_group_id');
        $table->string('product_group_name');
        $table->string('product_group_sku');
        $table->boolean('product_group_featured');
        $table->string('product_group_label');
        $table->string('product_group_tags');
        $table->boolean('product_group_status');
        $table->string('category_id');
        $table->integer('merchant_id')->index();
        $table->timestamps();
      });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nano_product_group');
    }
};
