<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User; // Ensure you import the User model

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = new User();
        $user->name = 'Yoshimi';
        $user->email = 'yoshimi@gmail.com';
        $user->password = bcrypt('123456'); // Use bcrypt to hash the password
        $user->save();
    }
}
