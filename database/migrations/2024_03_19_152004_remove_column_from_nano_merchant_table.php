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
        Schema::table('nano_merchant', function (Blueprint $table) {
          $table->dropColumn('merchant_email');
          $table->dropColumn('merchant_phone');
          $table->dropColumn('merchant_address');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('nano_merchant', function (Blueprint $table) {
            //
        });
    }
};
