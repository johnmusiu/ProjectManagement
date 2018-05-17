<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Seed role
        $this->call(RoleTableSeeder::class);
        //seed department
        $this->call(DepartmentTableSeeder::class);
        //Seed user
        $this->call(UserTableSeeder::class);
        //Seed category
        $this->call(CategoryTableSeeder::class);
        //seed tasks
        $this->call(TaskTableSeeder::class);
    }
}
