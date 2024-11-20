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
        Schema::create('penelitians_anggotas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('penelitian_id')->constrained('penelitians')->onDelete('cascade');
            $table->foreignId('anggota_id')->constrained('anggota_kelompok_keahlians')->onDelete('cascade');
            $table->string('anggota_type'); // Menyimpan tipe relasi (users atau anggota_kelompok_keahlians)
            $table->timestamps();

            $table->index(['anggota_id', 'anggota_type']);

        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penelitians_anggotas');
    }
};
