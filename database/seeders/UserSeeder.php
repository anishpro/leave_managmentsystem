<?php

namespace Database\Seeders;

use App\Models\User;
use Hash;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash as FacadesHash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory()->create([
            'name' => 'Demo User',
            'email' => 'demo@gmail.com',
            'password' => FacadesHash::make('password')
        ]);
    }
}
