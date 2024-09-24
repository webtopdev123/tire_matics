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
        Schema::create('nano_product_ecatalog', function (Blueprint $table) {
            $table->id('ecatalog_id');
            $table->string('ecatalog_name')->nullable();
            $table->string('ecatalog_thumbnail')->nullable();
            $table->string('ecatalog_file')->nullable();
            $table->integer('merchant_id');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nano_product_ecatalog');
    }
};
