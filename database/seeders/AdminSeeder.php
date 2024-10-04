<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Foundation\Auth\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'nama_lengkap' => 'Franz Kafka',
            'nip' => '19740817200312',
            'jabatan' => 'Staf Admin',
            'no_hp' => '08123456789',
            'email' => 'admin@admin.com',
            'username' => 'admin',
            'password' => bcrypt('admin'),
        ]);
    }
}
