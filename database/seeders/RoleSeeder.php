<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // ? Should role names be snake-case or camelCase? Does it matter?
        // Role::create([
        //     'name' => 'super-admin'
        // ]);

        Role::create([
            'name' => 'admin'
        ]);
    }
}
