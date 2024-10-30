<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class KbkSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'nama_kbk' => 'Sistem Informasi',
                'jurusan' => 'Teknik Informatika',
                'deskripsi' => 'Ketua KBK - Email: dianpramadhana@gmail.com',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nama_kbk' => 'Rekayasa Perangkat Lunak dan Pengetahuan',
                'jurusan' => 'Teknik Informatika',
                'deskripsi' => 'Ketua KBK - Email: yaqutinams@polindra.ac.id',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nama_kbk' => 'Sistem Komputer dan Jaringan',
                'jurusan' => 'Teknik Informatika',
                'deskripsi' => 'Ketua KBK - Email: willy@polindra.ac.id',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nama_kbk' => 'Sains Data',
                'jurusan' => 'Teknik Informatika',
                'deskripsi' => 'Asisten Ahli - Email: snhimawan@polindra.ac.id',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nama_kbk' => 'Fundamental And Management Nursing',
                'jurusan' => 'Kesehatan',
                'deskripsi' => 'Asisten Ahli - Email: gilarwisnu@gmail.com',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nama_kbk' => 'Clinical Care Nursing',
                'jurusan' => 'Kesehatan',
                'deskripsi' => 'Lektor - Email: priyantoghyfano@polindra.ac.id',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nama_kbk' => 'Mental Health And Community Nursing',
                'jurusan' => 'Kesehatan',
                'deskripsi' => 'Lektor - Email: indra@polindra.ac.id',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nama_kbk' => 'Perancangan Manufaktur',
                'jurusan' => 'Teknik',
                'deskripsi' => 'Lektor - Email: dedi@polindra.ac.id',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nama_kbk' => 'Rekayasa Material',
                'jurusan' => 'Teknik',
                'deskripsi' => 'Lektor - Email: titoendramawan@gmail.com',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nama_kbk' => 'RHVAC',
                'jurusan' => 'Teknik',
                'deskripsi' => 'Asisten Ahli - Email: yudhy@polindra.ac.id',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nama_kbk' => 'Instrumentasi dan Kontrol',
                'jurusan' => 'Teknik',
                'deskripsi' => 'Lektor - Email: bobikhoerun@polindra.ac.id',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        DB::table('kelompok_keahlians')->insert($data);
    }
}
