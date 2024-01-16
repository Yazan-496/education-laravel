<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'id'    =>  1,
                'name'  =>  'admin',
                'email' =>  "admin@admin.com",
                'password'  =>  Hash::make("12345678"),
                'real'      =>  "12345678",
                'role_id'   =>   1,
            ]
        ];
        User::insert($users);
    }
}

