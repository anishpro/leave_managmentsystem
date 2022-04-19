<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->getData() as $item) {
            Permission::create(['name' => 'create_'.$item,'guard_name'=>'api']);
            Permission::create(['name' => 'read_'.$item,'guard_name'=>'api']);
            Permission::create(['name' => 'update_'.$item,'guard_name'=>'api']);
            Permission::create(['name' => 'delete_'.$item,'guard_name'=>'api']);
        }
        Permission::create(['name' => 'update_users','guard_name'=>'api']);
        Permission::create(['name' => 'update_supervisee','guard_name'=>'api']);
    }
    public function getData()
    {
        return [
           'permission',
           'role',
           'user',
           ''
       ];
    }
}
