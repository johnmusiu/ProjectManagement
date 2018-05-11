<?php

use Illuminate\Database\Seeder;
use App\Category;

class CategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $default_category = new Category();
        $default_category->name = 'default';
        $default_category->description = 
            'Holds they type of all tasks not assigned a category';
        $default_category->save();
    }
}
