<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Role;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role_member = Role::where('name', 'department_member')->first();
        $role_manager = Role::where('name', 'department_manager')->first();

        $dept_member1 = new User();
        $dept_member1->name = "James K. Matiku";
        $dept_member1->email = "jmatiku@cytonn.com";
        $dept_member1->status = "inactive";
        $dept_member1->password = bcrypt('secret');
        $dept_member1->save();
        $dept_member1->roles()->attach($role_member);

        $dept_member2 = new User();
        $dept_member2->name = "Grace K. Mulonga";
        $dept_member2->email = "gmulonga@cytonn.com";
        $dept_member2->status = "inactive";        
        $dept_member2->password = bcrypt('secret');
        $dept_member2->save();
        $dept_member2->roles()->attach($role_member);

        $dept_manager = new User();
        $dept_manager->name = "Adams P. Kamanu";
        $dept_manager->email = "akamanu@cytonn.com";
        $dept_manager->status = "inactive";
        $dept_manager->password = bcrypt('secret');
        $dept_manager->save();
        $dept_manager->roles()->attach($role_manager);
    }
}
