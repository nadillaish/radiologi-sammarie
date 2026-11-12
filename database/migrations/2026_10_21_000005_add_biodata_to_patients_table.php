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
        Schema::table('patients', function (Blueprint $table) {
            $table->date('date_of_birth')->nullable()->after('name');
            $table->string('gender', 20)->nullable()->after('date_of_birth');
            $table->string('phone', 30)->nullable()->after('gender');
            $table->text('address')->nullable()->after('phone');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->dropColumn(['date_of_birth', 'gender', 'phone', 'address']);
        });
    }
};

