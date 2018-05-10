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
        //Seed user
        $this->call(UserTableSeeder::class);
    }
}
