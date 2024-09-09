<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $users = [
            [
                'name' => 'Juan Huete',
                'email' => 'juan@example.com',
                'password' => Hash::make('12345678'),
            ],
            [
                'name' => 'Katherine Cerda',
                'email' => 'katherine@example.com',
                'password' => Hash::make('12345678'),
            ],
            [
                'name' => 'Fernanda Herrera',
                'email' => 'fernanda@example.com',
                'password' => Hash::make('12345678'),
            ],
        ];

        // Inserta los usuarios en la base de datos
        DB::table('users')->insert($users);
    }
}
