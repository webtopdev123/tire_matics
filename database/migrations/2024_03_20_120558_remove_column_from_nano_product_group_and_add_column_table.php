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
      Schema::table('nano_product_group', function (Blueprint $table) {
        $table->dropColumn('product_group_status');
        $table->dropColumn('product_group_label');
      });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('nano_product_group', function (Blueprint $table) {
            //
        });
    }
};
