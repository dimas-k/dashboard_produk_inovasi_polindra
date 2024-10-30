<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class KetuaKbkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $siId = DB::table('kelompok_keahlians')->where('nama_kbk', 'Sistem Informasi')->value('id');
        $rplpId = DB::table('kelompok_keahlians')->where('nama_kbk', 'Rekayasa Perangkat Lunak dan Pengetahuan')->value('id');
        $skjId = DB::table('kelompok_keahlians')->where('nama_kbk', 'Sistem Komputer dan Jaringan')->value('id');
        $sdId = DB::table('kelompok_keahlians')->where('nama_kbk', 'Sains Data')->value('id');
        $fmnId = DB::table('kelompok_keahlians')->where('nama_kbk', 'Fundamental And Management Nursing')->value('id');
        $ccnId = DB::table('kelompok_keahlians')->where('nama_kbk', 'Clinical Care Nursing')->value('id');
        $mhcnId = DB::table('kelompok_keahlians')->where('nama_kbk', 'Mental Health And Community Nursing')->value('id');
        $pmId = DB::table('kelompok_keahlians')->where('nama_kbk', 'Perancangan Manufaktur')->value('id');
        $rmId = DB::table('kelompok_keahlians')->where('nama_kbk', 'Rekayasa Material')->value('id');
        $rhvacId = DB::table('kelompok_keahlians')->where('nama_kbk', 'RHVAC')->value('id');
        $ikId = DB::table('kelompok_keahlians')->where('nama_kbk', 'Instrumentasi dan Kontrol')->value('id');

        DB::table('users')->insert([
            [
                'nama_lengkap' => 'Dian Pramadhana, M.Kom',
                'nip' => '199302282022031007',
                'jabatan' => 'Ketua KBK',
                'pas_foto' => 'dokumen-user/foto_user_default.png',
                'no_hp' => '085742114040',
                'kbk_id'=>$siId,
                'email' => 'dianpramadhana@gmail.com',
                'username' => '199302282022031007',
                'password' => Hash::make('199302282022031007'),
                'role' => 'ketua_kbk'
            ],
            [
                'nama_lengkap' => 'Yaqutina Marjani Santosa S.Pd., M.Cs.',
                'nip' => '199211022022031014',
                'no_hp' => '081329997431',
                'kbk_id'=>$rplpId,
                'email' => 'yaqutinams@polindra.ac.id',
                'jabatan' => 'Ketua KBK',
                'pas_foto' => 'dokumen-user/foto_user_default.png',
                'username' => '199211022022031014',
                'password' => Hash::make('199211022022031014'),
                'role' => 'ketua_kbk'
            ],
            [
                'nama_lengkap' => 'Willy Permana Putra, S.T., M.Eng.',
                'nip' => '198610042019031004',
                'no_hp' => '085878356247',
                'kbk_id'=>$skjId,
                'email' => 'willy@polindra.ac.id',
                'jabatan' => 'Ketua KBK',
                'pas_foto' => 'dokumen-user/foto_user_default.png',
                'username' => '198610042019031004',
                'password' => Hash::make('198610042019031004'),
                'role' => 'ketua_kbk'
            ],
            [
                'nama_lengkap' => 'Salamet Nur Himawan, S.Si., M.Si.',
                'nip' => '199407022022031005',
                'no_hp' => '085771584505',
                'kbk_id'=>$sdId,
                'email' => 'snhimawan@polindra.ac.id',
                'jabatan' => 'Asisten Ahli',
                'pas_foto' => 'dokumen-user/foto_user_default.png',
                'username' => '199407022022031005',
                'password' => Hash::make('199407022022031005'),
                'role' => 'ketua_kbk'
            ],
            [
                'nama_lengkap' => 'Gilar Wisnu Hardi S.Ft., M.T',
                'nip' => '199308112022031008',
                'no_hp' => '081294582347',
                'kbk_id'=>$fmnId,
                'email' => 'gilarwisnu@gmail.com',
                'jabatan' => 'Asisten Ahli',
                'pas_foto' => 'dokumen-user/foto_user_default.png',
                'username' => '199308112022031008',
                'password' => Hash::make('199308112022031008'),
                'role' => 'ketua_kbk'
            ],
            [
                'nama_lengkap' => 'Priyanto, S.Pd., M.Kes.',
                'nip' => '196502231984121001',
                'no_hp' => '081223236195',
                'kbk_id'=>$ccnId,
                'email' => 'priyantoghryfano@polindra.ac.id',
                'jabatan' => 'Lektor',
                'pas_foto' => 'dokumen-user/foto_user_default.png',
                'username' => '196502231984121001',
                'password' => Hash::make('196502231984121001'),
                'role' => 'ketua_kbk'
            ],
            [
                'nama_lengkap' => 'Dr. Indra Ruswadi, S.Kep., NS., M.PH.',
                'nip' => '196709271987031078',
                'no_hp' => '081222532678',
                'kbk_id'=>$mhcnId,
                'email' => 'indra@polindra.ac.id',
                'jabatan' => 'Lektor',
                'pas_foto' => 'dokumen-user/foto_user_default.png',
                'username' => '196709271987031078',
                'password' => Hash::make('196709271987031078'),
                'role' => 'ketua_kbk'
            ],
            [
                'nama_lengkap' => 'Ir. Dedi Suwandi, S.ST., M.T.',
                'nip' => '198405052019031016',
                'no_hp' => '087828663555',
                'kbk_id'=>$pmId,
                'email' => 'dedi@polindra.ac.id',
                'jabatan' => 'Lektor',
                'pas_foto' => 'dokumen-user/foto_user_default.png',
                'username' => '198405052019031016',
                'password' => Hash::make('198405052019031016'),
                'role' => 'ketua_kbk'
            ],
            [
                'nama_lengkap' => 'Tito Endramawan, S.Pd, M.Eng.',
                'nip' => '19830312201121002',
                'no_hp' => '085222212276',
                'kbk_id'=>$rmId,
                'email' => 'titoendramawan@gmail.com',
                'jabatan' => 'Lektor',
                'pas_foto' => 'dokumen-user/foto_user_default.png',
                'username' => '19830312201121002',
                'password' => Hash::make('19830312201121002'),
                'role' => 'ketua_kbk'
            ],
            [
                'nama_lengkap' => 'Yudhy Kurniawan, A.Md., S.T., M.T.',
                'nip' => '197710112021211003',
                'no_hp' => '081330069393',
                'kbk_id'=>$rhvacId,
                'email' => 'yudhy@polindra.ac.id',
                'jabatan' => 'Asisten Ahli',
                'pas_foto' => 'dokumen-user/foto_user_default.png',
                'username' => '197710112021211003',
                'password' => Hash::make('197710112021211003'),
                'role' => 'ketua_kbk'
            ],
            [
                'nama_lengkap' => 'Bobi Khoerun, S.Pd., M.T.',
                'nip' => '198806032018031001',
                'no_hp' => '081914151132',
                'kbk_id'=>$ikId,
                'email' => 'bobikhoerun@polindra.ac.id',
                'jabatan' => 'Lektor',
                'pas_foto' => 'dokumen-user/foto_user_default.png',
                'username' => '198806032018031001',
                'password' => Hash::make('198806032018031001'),
                'role' => 'ketua_kbk'
            ],
        ]);
    }
}
