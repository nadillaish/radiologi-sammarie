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
        Schema::create('radiology_service_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            $table->text('keywords')->nullable();
            $table->timestamps();
        });

        Schema::create('radiologist_service_category', function (Blueprint $table) {
            $table->id();
            $table->foreignId('radiologist_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('category_id')->constrained('radiology_service_categories')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['radiologist_id', 'category_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('radiologist_service_category');
        Schema::dropIfExists('radiology_service_categories');
    }
};

