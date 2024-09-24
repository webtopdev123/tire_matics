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
        Schema::create('nano_merchant', function (Blueprint $table) {
          $table->id('merchant_id');
          $table->string('merchant_name');
          $table->string('merchant_registration_number',64);

          $table->string('merchant_url')->nullable();
          $table->string('merchant_email',150);
          $table->string('merchant_address');
          $table->string('merchant_phone',64)->nullable();

          $table->string('merchant_whatsapp')->nullable();
          $table->string('merchant_facebook')->nullable();
          $table->string('merchant_instagram')->nullable();
          $table->string('merchant_tiktok',64)->nullable();
          $table->string('merchant_logo')->nullable();
          $table->boolean('merchant_priceshow')->nullable();

          $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nano_merchant');
    }
};
