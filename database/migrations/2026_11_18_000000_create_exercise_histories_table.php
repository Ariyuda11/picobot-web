<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exercise_histories', function (Blueprint $table) {
            $table->id();

            $table->foreignId('patient_id')
                ->constrained('patients')
                ->cascadeOnDelete();

            // Akun yang mencatat riwayat latihan (admin atau pasien)
            $table->foreignId('recorded_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->string('exercise_name');
            $table->string('intensity', 20)->default('Sedang');
            $table->unsignedInteger('duration_minutes');
            $table->unsignedInteger('repetitions')->nullable();
            $table->unsignedTinyInteger('pain_level')->nullable();
            $table->text('notes')->nullable();
            $table->dateTime('performed_at');

            $table->timestamps();

            $table->index(['patient_id', 'performed_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exercise_histories');
    }
};
