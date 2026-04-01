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
        Schema::create('rujukans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('balita_id')->constrained()->cascadeOnDelete();
            $table->foreignId('puskesmas_id')->constrained()->cascadeOnDelete();
            $table->date('tanggal_rujuk');
            $table->text('jenis_keluhan');
            $table->enum('status_gizi', ['normal', 'kurang', 'lebih', 'gizi_buruk', 'stunting']);
            $table->text('tindak_lanjut')->nullable();
            $table->foreignId('nakes_id')->constrained('users')->cascadeOnDelete();
            $table->enum('status', ['dirujuk', 'diteruskan', 'selesai'])->default('dirujuk');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rujukans');
    }
};
