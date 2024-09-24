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
        $table->boolean('product_group_new')->after('product_group_tags');
        $table->boolean('product_group_hot')->after('product_group_new');
        $table->boolean('product_group_display')->after('product_group_hot');
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
