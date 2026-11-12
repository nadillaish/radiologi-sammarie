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
        Schema::table('examinations', function (Blueprint $table) {
            $table->foreignId('radiologist_id')
                ->nullable()
                ->after('referring_doctor_id')
                ->constrained('users')
                ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('examinations', function (Blueprint $table) {
            $table->dropForeign(['radiologist_id']);
            $table->dropColumn('radiologist_id');
        });
    }
};

