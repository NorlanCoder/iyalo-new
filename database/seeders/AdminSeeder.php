<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'name' => "Admin ADMIN",
                'phone' => "97854632",
                'email' => "admin@gmail.com",
                'birthday' => new \DateTime(),
                'role' => "admin",
                'password' => Hash::make('azertyui'),
                'created_at' => new \DateTime(),
                'updated_at' => new \DateTime(),
            ],

            [
                'name' => "Pablo LOTO",
                'phone' => "98456321",
                'email' => "lotopoh@gmail.com",
                'birthday' => new \DateTime(),
                'role' => "announcer",
                'password' => Hash::make('azertyui'),
                'created_at' => new \DateTime(),
                'updated_at' => new \DateTime(),
            ],
        ]);
    }
}
