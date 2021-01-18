<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Enes Poyraz',
            'email' => 'enes.poyraz@4alabs.io',
            'password' => bcrypt('asd123'),
            'is_admin' => 1
        ]);
    }
}
