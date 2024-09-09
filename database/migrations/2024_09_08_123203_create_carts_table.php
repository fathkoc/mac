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
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->integer('position');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('phone')->nullable();
            $table->decimal('map_lat', 10, 7)->nullable();
            $table->decimal('map_long', 10, 7)->nullable();
            $table->string('discount')->nullable();
            $table->string('menu')->nullable();
            $table->string('brand_like')->nullable();
            $table->unsignedInteger('like')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};
