<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
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
        User::create([
            'username' => 'NiGHTBass',
            'email' => 'user@example.com',
            'password' => Hash::make('password123')
        ]);

        User::create([
            'username' => 'Moorethought',
            'email' => 'anthony@anthonymoore.co',
            'password' => Hash::make('password123')
        ]);

        User::create([
            'username' => 'ENDOR',
            'email' => 'matthewolmos@gmail.com',
            'password' => Hash::make('password123')
        ]);

        User::create([
            'username' => 'fizz',
            'email' => 'rj.santos@live.com',
            'password' => Hash::make('password123')
        ]);

        User::create([
            'username' => 'socrates_style',
            'email' => 'joedark0@gmail.com',
            'password' => Hash::make('password123')
        ]);
    }
}
