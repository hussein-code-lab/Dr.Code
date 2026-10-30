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
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // e.g. PAT-2026-0001
            $table->string('name');
            $table->string('phone')->index();
            $table->string('national_id')->nullable()->index();
            $table->date('birth_date')->nullable();
            $table->string('gender'); // App\Enums\Gender
            $table->string('blood_group')->nullable(); // App\Enums\BloodGroup
            $table->json('medical_history')->nullable(); // chronic diseases, allergies, surgeries
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
