<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $superAdmin = Role::create(['name' => 'super-admin','guard_name'=>'api']);
        $superDev = Role::create(['name' => 'super-dev','guard_name'=>'api']);
        $admin = Role::create(['name' => 'admin','guard_name'=>'api']);
        $applicant = Role::create(['name' => 'applicant','guard_name'=>'api']);
        $supervisor = Role::create(['name' => 'supervisor','guard_name'=>'api']);

        $demo_user = User::find(1);
        $demo_user->assignRole($applicant);

        $user = \App\Models\User::factory()->create([
            'name' => 'Dev User',
            'email' => 'sahumagain@gmail.com',
            'password' => Hash::make('password'),
        ]);
        $user1 = \App\Models\User::factory()->create([
            'name' => 'Super User',
            'email' => 'supervisor@gmail.com',
            'password' => Hash::make('password'),
        ]);

        $user1->assignRole($supervisor);

        $user->assignRole($superDev);
    }
}
