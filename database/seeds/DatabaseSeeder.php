<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(CourseTableSeeder::class);
        $this->command->info('Seeded the Courses!'); 

        $this->call('CountriesSeeder');
        $this->command->info('Seeded the countries!'); 
    }
}
