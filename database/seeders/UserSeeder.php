<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {

        $admin = User::create([
            'name' => 'Admin Kantin 2',
            'email' => 'admin2@kantin.test',
            'password' => bcrypt('password')
        ]);
        $admin->assignRole('admin');

        // $user = User::create([
        //     'name' => 'Siswa 5',
        //     'email' => 'user5@kantin.test',
        //     'password' => bcrypt('password')
        // ]);
        // $user->assignRole('user');

        // $user = User::create([
        //     'name' => 'Siswa 6',
        //     'email' => 'user6@kantin.test',
        //     'password' => bcrypt('password')
        // ]);
        // $user->assignRole('user');
    }
}
