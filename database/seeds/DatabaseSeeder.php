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
        $this->call(GigCategorySeeder::class);
        $this->call(GigServiceTypeSeeder::class);
        $this->call(OrderSeeder::class);
    }
}
