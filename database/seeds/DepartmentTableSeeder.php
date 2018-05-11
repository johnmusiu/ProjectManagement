<?php

use Illuminate\Database\Seeder;
use App\Department;

class DepartmentTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $it_department = new Department();
        $it_department->name = 'IT';
        $it_department->description = 
            'Deals with IT related tasks such as network configurtions';
        $it_department->save();

        $sales_department = new Department();
        $sales_department->name = 'Sales';
        $sales_department->description = 
            'Deals with sales related tasks';
        $sales_department->save();

    }
}
