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
        DB::table('users')->insert([
            [
                'nama_lengkap' => 'Dian Pramadhana, M.Kom',
                'nip' => '199302282022031007',
                'jabatan' => 'Ketua KBK',
                'no_hp' => '085742114040',
                'email' => 'dianpramadhana@gmail.com',
                'kbk' => 'Sistem Informasi',
                'username' => '199302282022031007',
                'password' => Hash::make('199302282022031007'),
                'role' => 'ketua_kbk'
            ],
            [
                'nama_lengkap' => 'Yaqutina Marjani Santosa S.Pd., M.Cs.',
                'nip' => '199211022022031014',
                'kbk' => 'Rekayasa Perangkat Lunak dan Pengetahuan',
                'no_hp' => '081329997431',
                'email' => 'yaqutinams@polindra.ac.id',
                'jabatan' => 'Ketua KBK',
                'username' => '199211022022031014',
                'password' => Hash::make('199211022022031014'),
                'role' => 'ketua_kbk'
            ],
            [
                'nama_lengkap' => 'Willy Permana Putra, S.T., M.Eng.',
                'nip' => '198610042019031004',
                'kbk' => 'Sistem Komputer dan Jaringan',
                'no_hp' => '085878356247',
                'email' => 'willy@polindra.ac.id',
                'jabatan' => 'Ketua KBK',
                'username' => '198610042019031004',
                'password' => Hash::make('198610042019031004'),
                'role' => 'ketua_kbk'
            ],
            [
                'nama_lengkap' => 'Salamet Nur Himawan, S.Si., M.Si.',
                'nip' => '199407022022031005',
                'kbk' => 'Sains Data',
                'no_hp' => '085771584505',
                'email' => 'snhimawan@polindra.ac.id',
                'jabatan' => 'Asisten Ahli',
                'username' => '199407022022031005',
                'password' => Hash::make('199407022022031005'),
                'role' => 'ketua_kbk'
            ],
            [
                'nama_lengkap' => 'Gilar Wisnu Hardi S.Ft., M.T',
                'nip' => '199308112022031008',
                'kbk' => 'Fundamental And Management Nursing',
                'no_hp' => '081294582347',
                'email' => 'gilarwisnu@gmail.com',
                'jabatan' => 'Asisten Ahli',
                'username' => '199308112022031008',
                'password' => Hash::make('199308112022031008'),
                'role' => 'ketua_kbk'
            ],
            [
                'nama_lengkap' => 'Priyanto, S.Pd., M.Kes.',
                'nip' => '196502231984121001',
                'kbk' => 'Clinical Care Nursing',
                'no_hp' => '081223236195',
                'email' => 'priyantoghryfano@polindra.ac.id',
                'jabatan' => 'Lektor',
                'username' => '196502231984121001',
                'password' => Hash::make('196502231984121001'),
                'role' => 'ketua_kbk'
            ],
            [
                'nama_lengkap' => 'Dr. Indra Ruswadi, S.Kep., NS., M.PH.',
                'nip' => '196709271987031078',
                'kbk' => 'Mental Health And Community Nursing',
                'no_hp' => '081222532678',
                'email' => 'indra@polindra.ac.id',
                'jabatan' => 'Lektor',
                'username' => '196709271987031078',
                'password' => Hash::make('196709271987031078'),
                'role' => 'ketua_kbk'
            ],
            [
                'nama_lengkap' => 'Ir. Dedi Suwandi, S.ST., M.T.',
                'nip' => '198405052019031016',
                'kbk' => 'Perancangan Manufaktur',
                'no_hp' => '087828663555',
                'email' => 'dedi@polindra.ac.id',
                'jabatan' => 'Lektor',
                'username' => '198405052019031016',
                'password' => Hash::make('198405052019031016'),
                'role' => 'ketua_kbk'
            ],
            [
                'nama_lengkap' => 'Tito Endramawan, S.Pd, M.Eng.',
                'nip' => '19830312201121002',
                'kbk' => 'Rekayasa Material',
                'no_hp' => '085222212276',
                'email' => 'titoendramawan@gmail.com',
                'jabatan' => 'Lektor',
                'username' => '19830312201121002',
                'password' => Hash::make('19830312201121002'),
                'role' => 'ketua_kbk'
            ],
            [
                'nama_lengkap' => 'Yudhy Kurniawan, A.Md., S.T., M.T.',
                'nip' => '197710112021211003',
                'kbk' => 'RHVAC',
                'no_hp' => '081330069393',
                'email' => 'yudhy@polindra.ac.id',
                'jabatan' => 'Asisten Ahli',
                'username' => '197710112021211003',
                'password' => Hash::make('197710112021211003'),
                'role' => 'ketua_kbk'
            ],
            [
                'nama_lengkap' => 'Bobi Khoerun, S.Pd., M.T.',
                'nip' => '198806032018031001',
                'kbk' => 'Instrumentasi dan Kontrol',
                'no_hp' => '081914151132',
                'email' => 'bobikhoerun@polindra.ac.id',
                'jabatan' => 'Lektor',
                'username' => '198806032018031001',
                'password' => Hash::make('198806032018031001'),
                'role' => 'ketua_kbk'
            ],
        ]);
    }
}
