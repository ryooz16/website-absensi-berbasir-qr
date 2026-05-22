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
        Schema::table('tahun_ajarans', function (Blueprint $blueprint) {
            $blueprint->date('tanggal_mulai')->nullable()->after('nama_tahun');
            $blueprint->date('tanggal_selesai')->nullable()->after('tanggal_mulai');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tahun_ajarans', function (Blueprint $blueprint) {
            $blueprint->dropColumn(['tanggal_mulai', 'tanggal_selesai']);
        });
    }
};
