<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserInterest;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Akun demo untuk testing login di Flutter.
        $user = User::create([
            'name'     => 'Pengguna Demo',
            'username' => 'penggunademo',
            'email'    => 'demo@pinspire.com',
            'password' => 'demo1234', // otomatis di-hash
        ]);

        // Minat yang dipilih saat registrasi.
        $minatList = [
            'Hewan lucu',
            'Memasak',
            'Estetika',
            'Dekorasi rumah',
        ];

        foreach ($minatList as $minat) {
            UserInterest::create([
                'user_id' => $user->id,
                'minat'   => $minat,
            ]);
        }
    }
}
