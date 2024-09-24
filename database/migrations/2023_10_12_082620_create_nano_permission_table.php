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
    Schema::create('nano_merchant_permission', function (Blueprint $table) {
      $table->id('permission_id');
      $table->string('permission_name');
      $table->string('permission_code');
      $table->tinyInteger('permission_read')->default(0);
      $table->tinyInteger('permission_delete')->default(0);
      $table->tinyInteger('permission_update')->default(0);
      $table->tinyInteger('permission_create')->default(0);
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('nano_permission');
  }
};
