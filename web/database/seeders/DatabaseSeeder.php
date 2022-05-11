<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => "superadmin",
            'username' => "superadmin",
            'email' => "superadmin@gmail.com",
            'password' => Hash::make('superadmin'),
        ]);

        DB::table('users')->insert([
            'name' => "admin",
            'username' => "admin",
            'email' => "admin@gmail.com",
            'password' => Hash::make('admin'),
        ]);

        DB::table('roles')->insert([
            'name' => "superadmin",
            "guard_name" => "web",
        ]);
        DB::table('roles')->insert([
            'name' => "admin",
            "guard_name" => "web",

        ]);
        DB::table('roles')->insert([
            'name' => "sekretariat",
            "guard_name" => "web",

        ]);
        DB::table('roles')->insert([
            'name' => "lattas",
            "guard_name" => "web",

        ]);
        DB::table('roles')->insert([
            'name' => "penta",
            "guard_name" => "web",

        ]);
        DB::table('roles')->insert([
            'name' => "hi",
            "guard_name" => "web",

        ]);
        
    }
}
