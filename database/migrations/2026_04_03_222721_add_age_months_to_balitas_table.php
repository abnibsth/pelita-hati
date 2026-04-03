<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('balitas', function (Blueprint $table) {
            $table->integer('age_months')->nullable()->after('birth_date')->comment('Age in months at registration');
        });

        // Populate existing records with calculated age
        DB::statement('
            UPDATE balitas 
            SET age_months = TIMESTAMPDIFF(MONTH, birth_date, CURDATE())
            WHERE age_months IS NULL
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('balitas', function (Blueprint $table) {
            $table->dropColumn('age_months');
        });
    }
};
