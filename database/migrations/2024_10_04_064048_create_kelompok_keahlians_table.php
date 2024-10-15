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
        Schema::create('kelompok_keahlians', function (Blueprint $table) {
            $table->bigIncrements('id'); // Primary key
            $table->string('nama_kbk');
            $table->string('jurusan')->nullable();
            $table->bigIncrements('produk_id')->nullable()->constrained('produks');
            $table->timestamps();
            // Defining foreign key constraint
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kelompok_keahlians');
    }
};
