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
        Schema::create('produks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nama_produk');
            $table->text('deskripsi');
            $table->string('gambar')->nullable();
            $table->string('inventor');
            $table->text('anggota_inventor');
            $table->string('no_hp_inventor');
            $table->foreign('kelompok_keahlian_id')->nullable()->references('id')->on('kelompok_keahlians')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produks');
    }
};
