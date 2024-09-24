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
    Schema::create('nano_merchant_user_permission_role', function (Blueprint $table) {
      $table->id('permission_role_id');
      $table->tinyInteger('permission_role_read')->default(0);
      $table->tinyInteger('permission_role_create')->default(0);
      $table->tinyInteger('permission_role_update')->default(0);
      $table->tinyInteger('permission_role_delete')->default(0);
      $table->integer('role_id')->index();
      $table->integer('permission_id')->index();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('nano_merchant_user_permission_role');
  }
};
