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
        Schema::create('nano_merchant_slideshow', function (Blueprint $table) {
          $table->id('slideshow_id');
          $table->string('slideshow_name');
          $table->string('slideshow_image');
          $table->integer('merchant_id');
          $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nano_merchant_slideshow');
    }
};
