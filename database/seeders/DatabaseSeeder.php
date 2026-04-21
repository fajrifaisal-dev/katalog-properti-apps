<?php

namespace Database\Seeders;

/* The line `use App\Models\User;` is importing the `User` model class from the `App\Models` namespace.
This allows the `DatabaseSeeder` class to use the `User` model within its scope, such as when
seeding the database with user data. */
// use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
        ]);
    }

}
