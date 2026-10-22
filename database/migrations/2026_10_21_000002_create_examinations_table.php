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
        Schema::create('examinations', function (Blueprint $table) {
            $table->id();
            $table->string('examination_code')->unique();
            $table->foreignId('patient_id')->constrained('patients')->onDelete('restrict');
            $table->foreignId('radiographer_id')->constrained('users')->onDelete('restrict');
            $table->foreignId('referring_doctor_id')->constrained('users')->onDelete('restrict');
            $table->string('examination_type');
            $table->date('examination_date');
            $table->time('examination_time');
            $table->enum('status', ['TERDAFTAR', 'MENUNGGU_INTERPRETASI', 'DRAFT_INTERPRETASI', 'FINAL'])->default('TERDAFTAR');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('examinations');
    }
};
