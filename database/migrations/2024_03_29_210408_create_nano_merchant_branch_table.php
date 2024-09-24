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
        Schema::create('nano_merchant_branch', function (Blueprint $table) {
          $table->id('branch_id');
          $table->string('branch_address')->nullable();
          $table->string('branch_waze')->nullable();
          $table->string('branch_phone')->nullable();
          $table->string('branch_email')->nullable();
          $table->string('branch_main')->nullable();
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
        Schema::dropIfExists('nano_merchant_branch');
    }
};
