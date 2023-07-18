<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'student',
            'email' => 'students@mail.com',
            'role' => 'student',
            'password' => Hash::make('password'),


        ]);

        DB::table('users')->insert([
            'name' => 'Teacher',
            'email' => 'teacher1@t.com',
            'role' => 'teacher',
            'password' => Hash::make('12345678'),
        ]);

        DB::table('users')->insert([
            'name' => 'Admin',
            'email' => 'admin@a.com',
            'role' => 'admin',
            'password' => Hash::make('12345678'),
        ]);
    }
}
