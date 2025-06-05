<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = Role::findOrCreate('admin', 'web');

        $mentorRole = Role::findOrCreate('mentor', 'web');

        $userRole = Role::findOrCreate('student', 'web');

        $user = User::create([
            'name' => 'Team Fatur',
            'email' => 'fatur@gmail.com',
            'password' => bcrypt('passwordd')
        ]);

        $user->assignRole($adminRole);
    }
}
