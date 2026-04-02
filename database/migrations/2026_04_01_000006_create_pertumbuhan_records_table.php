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
        Schema::create('pertumbuhan_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('balita_id')->constrained()->cascadeOnDelete();
            $table->date('tanggal');
            $table->decimal('berat_badan', 5, 2); // kg
            $table->decimal('tinggi_badan', 5, 2); // cm
            $table->decimal('lingkar_kepala', 4, 2)->nullable(); // cm
            $table->decimal('lingkar_lengan_atas', 4, 2)->nullable(); // cm

            $table->string('umur_saat_ukur')->nullable(); // calculated
            $table->enum('status_gizi', ['normal', 'kurang', 'lebih', 'gizi_buruk', 'stunting']);
            $table->decimal('z_score_bbu', 6, 2)->nullable(); // Berat menurut Umur
            $table->decimal('z_score_tbu', 6, 2)->nullable(); // Tinggi menurut Umur
            $table->decimal('z_score_bbtb', 6, 2)->nullable(); // Berat menurut Tinggi

            $table->foreignId('kader_id')->constrained('users')->cascadeOnDelete();
            $table->text('catatan')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pertumbuhan_records');
    }
};
