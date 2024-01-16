<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Permission;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            [
                'id'    =>  1,
                'name'  =>  'SHOW_USERS',
                'role_id'   =>  1
            ],[
                'id'    =>  2,
                'name'  =>  'UPDATE_USER',
                'role_id'   =>  1
            ],[
                'id'    =>  3,
                'name'  =>  'DELETE_USER',
                'role_id'   =>  1
            ],[
                'id'    =>  4,
                'name'  =>  'CREATE_USER',
                'role_id'   =>  1
            ],[
                'id'    =>  5,
                'name'  =>  'SHOW_QUIZ',
                'role_id'   =>  1
            ],[
                'id'    =>  6,
                'name'  =>  'UPDATE_QUIZ',
                'role_id'   =>  1
            ],[
                'id'    =>  7,
                'name'  =>  'DELETE_QUIZ',
                'role_id'   =>  1
            ],[
                'id'    =>  8,
                'name'  =>  'CREATE_QUIZ',
                'role_id'   =>  1
            ],
        ];

        Permission::insert($permissions);
    }
}
