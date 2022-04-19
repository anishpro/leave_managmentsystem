<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            UserSeeder::class,
            RoleSeeder::class,
            PermissionSeeder::class,
    ]);

        // $path = public_path().'/sql/groups.sql';
        // DB::unprepared(file_get_contents($path));
        // $this->command->info('Group table seeded!');
    }
}
