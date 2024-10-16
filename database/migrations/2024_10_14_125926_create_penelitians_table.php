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
        Schema::create('penelitians', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('kbk_id')->constrained('kelompok_keahlians')->nullable();
            $table->string('judul');
            $table->text('abstrak');
            $table->string('gambar')->nullable();
            $table->string('penulis');
            $table->text('anggota_penulis')->nullable();
            $table->string('email_penulis');
            $table->text('lampiran')->nullable();
            $table->string('status')->default('Belum Divalidasi');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penelitians');
    }
};
