<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@gmail.com',
            'password' => bcrypt('password'), // Ensure password is hashed
            'role' => 'admin', // Assuming you have a role field
        ]);

        // Run initial data seeder
        $this->call([
            InitialDataSeeder::class,
        ]);
    }
}
