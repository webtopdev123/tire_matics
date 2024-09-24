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
        Schema::create('nano_merchant_content', function (Blueprint $table) {
          $table->id('content_id');
          $table->string('content_page');
          $table->text('content_text');
          $table->integer('merchant_id');
          $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nano_merchant_content');
    }
};
