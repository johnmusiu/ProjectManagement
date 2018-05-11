<?php

use Illuminate\Database\Seeder;
use App\Role;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //create junior employee role
        $role_junior = new Role();
        $role_junior->name = 'department_member';
        $role_junior->description = 'A Junior Member of the Department';
        $role_junior->save();
        
        //create department manager role
        $role_supervisor = new Role();
        $role_supervisor->name = 'department_manager';
        $role_supervisor->description = 'A Department Manager';
        $role_supervisor->save();
    }
}
