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
        Schema::create('imunisasi_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('balita_id')->constrained()->cascadeOnDelete();
            $table->enum('jenis_imunisasi', [
                'HB-0', 'BCG', 'Polio-1', 'Polio-2', 'Polio-3', 'Polio-4',
                'DPT-HB-1', 'DPT-HB-2', 'DPT-HB-3',
                'Campak', 'Campak-Rubella', 'JE', 'PCV', 'Rotavirus', 'MR'
            ]);
            $table->date('tanggal_diberikan');
            $table->string('batch_number')->nullable();
            $table->enum('lokasi', ['Posyandu', 'Puskesmas', 'RS', 'Lainnya']);
            $table->foreignId('input_by')->constrained('users')->cascadeOnDelete();
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('imunisasi_records');
    }
};
