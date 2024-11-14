<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('penelitians', function (Blueprint $table) {
            $table->unsignedBigInteger('penulis_korespondensi')->change();
            $table->foreign('penulis_korespondensi')->references('id')->on('anggota_kelompok_keahlians')->onDelete('cascade');
        });
    }
    
    public function down()
    {
        Schema::table('penelitians', function (Blueprint $table) {
            $table->dropForeign(['penulis_korespondensi']);
            $table->string('penulis_korespondensi')->change();
        });
    }
};
