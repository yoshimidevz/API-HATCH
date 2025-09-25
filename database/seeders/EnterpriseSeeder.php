<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class EnterpriseSeeder extends Seeder
{
    public function run(): void
    {
        $user = new User();
        $user->name = 'Enterprise Rumo';
        $user->email = 'rumo@gmail.com';
        $user->role = 'enterprise';
        $user->password = bcrypt('123456');
        $user->save();
        dd($user);
    }
}
