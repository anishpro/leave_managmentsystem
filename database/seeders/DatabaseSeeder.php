<?php

namespace Database\Seeders;

use App\Models\DutyStation;
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
            GroupSeeder::class,
            PublicHolidaySeeder::class,
            LeaveTypeSeeder::class,
            DesignationSeeder::class,
            ContractTypeSeeder::class,
            ContractLeaveMapSeeder::class,
            DutyStationSeeder::class
    ]);

        // $path = public_path().'/sql/groups.sql';
        // DB::unprepared(file_get_contents($path));
        // $this->command->info('Group table seeded!');
    }
}
