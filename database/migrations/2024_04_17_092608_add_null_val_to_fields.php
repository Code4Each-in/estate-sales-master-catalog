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
        Schema::table('catalogs', function (Blueprint $table) {
            $table->double('weight',8,2)->nullable()->change();
            $table->double('sale_price', 8, 2)->nullable()->change();
            $table->double('length',8,2)->nullable()->change();
            $table->double('width',8,2)->nullable()->change();
            $table->double('height',8,2)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('catalogs', function (Blueprint $table) {
            //
        });
    }
};
