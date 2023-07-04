<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
        $user = User::create([
            'username' => 'NiGHTBass',
            'email' => 'tonatiuh.cuauhtemoc@gmail.com',
            'password' => Hash::make('password123')
        ]);

        $role = Role::where('name', 'admin')->firstOrFail();

        $user->roles()->attach($role->id);
        $user->roles()->updateExistingPivot($role->id, ['created_at' => now()]);
        $user->roles()->updateExistingPivot($role->id, ['updated_at' => now()]);

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
        User::create([
            'username' => 'cuppscakes',
            'email' => 'matthew@mtslzr.io',
            'password' => Hash::make('password123')
        ]);
        User::create([
            'username' => 'CJuarez',
            'email' => 'christina.i.juarez@gmail.com',
            'password' => Hash::make('password123')
        ]);

        User::create([
            'username' => 'testUser',
            'email' => 'user@example.com',
            'password' => Hash::make('password123')
        ]);
        
    }
}
