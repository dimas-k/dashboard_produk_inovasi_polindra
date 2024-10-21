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
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nama_lengkap');
            $table->bigInteger('nip')->unique();
            $table->string('jabatan');
            $table->string('pas_foto')->nullable();
            $table->string('no_hp');
            $table->foreignId('kbk_id')->nullable()->constrained('kelompok_keahlians')->nullable();
            $table->string('email')->unique();
            $table->string('username');
            $table->string('password');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
