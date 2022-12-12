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
        $this->call(DepartmentsSeeder::class);
        $this->call(RewardSeeder::class);
        $this->call(BanruralSeeder::class);
        $this->call(TigoSeeder::class);
    }
}