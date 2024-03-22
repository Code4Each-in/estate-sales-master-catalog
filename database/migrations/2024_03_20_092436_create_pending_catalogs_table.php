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
        Schema::create('pending_catalogs', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('author_id')->constraint()->nullable();
            $table->bigInteger('master_catalog_id')->nullable()->constrained();
            $table->bigInteger('wp_category_id')->nullable();
            $table->string('title')->nullable();
            $table->string('name')->nullable();
            $table->string('sku')->nullable();
            $table->double('base_price', 8, 2)->nullable();
            $table->string('image')->nullable();
            $table->text('content')->nullable();
            $table->string('status');
            $table->date('publish_date')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pending_catalogs');
    }
};
