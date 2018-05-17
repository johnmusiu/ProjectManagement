<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Role;
use App\Department;

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
        $it_department = Department::where('name', 'IT')->first();
        $sales_department = Department::where('name', 'Sales')->first();

        $dept_member1 = new User();
        $dept_member1->name = "James K. Matiku";
        $dept_member1->email = "jmatiku@cytonn.com";
        $dept_member1->status = "inactive";
        $dept_member1->password = bcrypt('secret');
        $dept_member1->department()->associate($it_department);
        $dept_member1->save();
        $dept_member1->roles()->attach($role_member);

        $dept_member2 = new User();
        $dept_member2->name = "Grace K. Mulonga";
        $dept_member2->email = "gmulonga@cytonn.com";
        $dept_member2->status = "inactive";        
        $dept_member2->password = bcrypt('secret');
        $dept_member2->department()->associate($sales_department);
        $dept_member2->save();
        $dept_member2->roles()->attach($role_member);

        $dept_manager1 = new User();
        $dept_manager1->name = "Adams P. Kamanu";
        $dept_manager1->email = "akamanu@cytonn.com";
        $dept_manager1->status = "inactive";
        $dept_manager1->password = bcrypt('secret');
        $dept_manager1->department()->associate($it_department);
        $dept_manager1->save();
        $dept_manager1->roles()->attach($role_manager);

        $dept_manager2 = new User();
        $dept_manager2->name = "Eunice N. Ondiek";
        $dept_manager2->email = "eondiek@cytonn.com";
        $dept_manager2->status = "inactive";
        $dept_manager2->password = bcrypt('secret');
        $dept_manager2->department()->associate($sales_department);
        $dept_manager2->save();
        $dept_manager2->roles()->attach($role_manager);

        //create followers 
        $dept_manager1->following()->attach($dept_member1);
        $dept_manager2->following()->attach($dept_member2);
        $dept_member1->following()->attach($dept_member2);

    }
}
