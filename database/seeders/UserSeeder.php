<?php

namespace Database\Seeders;

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = DB::table('roles')->where('name','admin')->first();

        DB::table('users')->insert([
            [
                'id' => Str::uuid(),
                'name' => 'John Doe',
                'email' => 'johndoe@example.com',
                'password' => Hash::make('jhon12345678'),
                'role_id' => $admin->id,
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
