<?php

namespace Database\Seeders;

use Database\Factories\RoleSeeder;
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
        $this->call(RoleSeeder::class);
        \App\Models\User::factory(1000)->create();
    }
}
