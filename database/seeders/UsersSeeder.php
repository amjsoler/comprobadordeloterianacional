<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //Mi usuario de pruebas
        $user = new User();
        $user->name = "Jorge";
        $user->email = "amjsoler@gmail.com";
        $user->email_verified_at = now();
        $user->password = Hash::make("jas12345");
        $user->save();

        User::factory()->count(99)->create();
    }
}
