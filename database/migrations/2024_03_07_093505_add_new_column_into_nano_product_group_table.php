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
          $table->string('product_group_color')->after('product_group_name');
          $table->string('product_group_material')->after('product_group_color');
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
