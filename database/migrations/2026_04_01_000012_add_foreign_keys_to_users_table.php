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
        // Add foreign key constraints to users table
        Schema::table('users', function (Blueprint $table) {
            $table->foreign('kelurahan_id')->references('id')->on('kelurahans')->cascadeOnDelete();
            $table->foreign('kecamatan_id')->references('id')->on('kecamatans')->cascadeOnDelete();
            $table->foreign('posyandu_id')->references('id')->on('posyandus')->cascadeOnDelete();
            $table->foreign('puskesmas_id')->references('id')->on('puskesmas')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['kelurahan_id', 'kecamatan_id', 'posyandu_id', 'puskesmas_id']);
        });
    }
};
