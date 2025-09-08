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
        // Admin users
        $admin1 = User::create([
            'name' => 'Admin Kantin 3',
            'email' => 'admin3@kantin.test',
            'password' => bcrypt('password'),
            'role' => 'admin', // Set role di database
            'balance' => 0
        ]);
        $admin1->assignRole('admin'); // Assign role Spatie Permission

        $admin2 = User::create([
            'name' => 'Admin Kantin 4',
            'email' => 'admin4@kantin.test',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'balance' => 0
        ]);
        $admin2->assignRole('admin');

        $admin3 = User::create([
            'name' => 'Admin Kantin 5',
            'email' => 'admin5@kantin.test',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'balance' => 0
        ]);
        $admin3->assignRole('admin');

        $admin4 = User::create([
            'name' => 'Admin Kantin 6',
            'email' => 'admin6@kantin.test',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'balance' => 0
        ]);
        $admin4->assignRole('admin');

        $admin5 = User::create([
            'name' => 'Admin Kantin 7',
            'email' => 'admin7@kantin.test',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'balance' => 0
        ]);
        $admin5->assignRole('admin');

        $admin6 = User::create([
            'name' => 'Admin Kantin 8',
            'email' => 'admin8@kantin.test',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'balance' => 0
        ]);
        $admin6->assignRole('admin');

        $admin7 = User::create([
            'name' => 'Admin Kantin 9',
            'email' => 'admin9@kantin.test',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'balance' => 0
        ]);
        $admin7->assignRole('admin');

        $admin8 = User::create([
            'name' => 'Admin Kantin 10',
            'email' => 'admin10@kantin.test',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'balance' => 0
        ]);
        $admin8->assignRole('admin');

        // Sample user accounts (uncomment jika perlu)
        $user1 = User::create([
            'name' => 'Siswa 5',
            'email' => 'user5@kantin.test',
            'password' => bcrypt('password'),
            'role' => 'user',
            'balance' => 50000 // Kasih saldo awal untuk testing
        ]);
        $user1->assignRole('user');

        $user2 = User::create([
            'name' => 'Siswa 6',
            'email' => 'user6@kantin.test',
            'password' => bcrypt('password'),
            'role' => 'user',
            'balance' => 75000
        ]);
        $user2->assignRole('user');
    }
}
