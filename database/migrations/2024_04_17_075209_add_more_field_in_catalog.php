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
            $table->double('weight',8,2);
            $table->string('color')->nullable();
            $table->double('sale_price', 8, 2);
            $table->string('brand')->nullable();
            $table->double('length',8,2);
            $table->double('width',8,2);
            $table->double('height',8,2);
            
    
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
