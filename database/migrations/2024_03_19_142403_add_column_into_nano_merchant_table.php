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
          $table->string('merchant_skincolor',16)->after('merchant_priceshow');
      });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
