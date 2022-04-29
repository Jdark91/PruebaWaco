<?php
  
use Illuminate\Database\Seeder;
use Database\Seeders\CreateUsers;
   
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(CreateUsers::class);
    }
}