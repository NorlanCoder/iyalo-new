<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('categories')->insert([
            [
                'label' => "Maison",
                'created_at' => new \DateTime(),
                'updated_at' => new \DateTime(),
            ],

            [
                'label' => "Appartement",
                'created_at' => new \DateTime(),
                'updated_at' => new \DateTime(),
            ],
            [
                'label' => "Villa",
                'created_at' => new \DateTime(),
                'updated_at' => new \DateTime(),
            ],
        ]); //
    }
}
