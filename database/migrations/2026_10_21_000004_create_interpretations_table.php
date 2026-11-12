<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('interpretations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('examination_id')->constrained('examinations')->onDelete('cascade');
            $table->foreignId('radiologist_id')->constrained('users')->onDelete('restrict');
            $table->text('interpretation_text');
            $table->enum('status', ['DRAFT', 'FINAL'])->default('DRAFT');
            $table->timestamp('finalized_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('interpretations');
    }
};
