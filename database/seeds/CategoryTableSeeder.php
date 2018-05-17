<?php

use Illuminate\Database\Seeder;
use App\Category;
use App\Department;

class CategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sales_department = Department::find(2);
        $IT_department = Department::find(1);

        $default_category = new Category();
        $default_category->name = 'Default';
        $default_category->description = 
            'Holds they type of all tasks not assigned a category';
        $default_category->save();

        $weekly_category = new Category();
        $weekly_category->name = 'Sales Data';
        $weekly_category->description = 'Get sales data from sales persons';
        $weekly_category->department()->associate($sales_department);
        $weekly_category->save();

        $daily_category = new Category();
        $daily_category->name = 'Customer Care';
        $daily_category->description = 'Get in touch with clients on sales issues';
        $daily_category->department()->associate($sales_department);
        $daily_category->save();

        $weekly_category = new Category();
        $weekly_category->name = 'Internet Diagnostics';
        $weekly_category->description = 'Get intouch with ISP incase of poor speeds';
        $weekly_category->department()->associate($IT_department);
        $weekly_category->save();

        $daily_category = new Category();
        $daily_category->name = 'Daily';
        $daily_category->description = 'For tasks done daily';
        $daily_category->department()->associate($IT_department);
        $daily_category->save();
    }
}
