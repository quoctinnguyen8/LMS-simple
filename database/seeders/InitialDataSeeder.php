<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InitialDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Insert some initial settings
        DB::table('settings')->insert([
            ['setting_key' => 'center_name', 'setting_value' => 'Learning Center', 'created_at' => now(), 'updated_at' => now()],
            ['setting_key' => 'address', 'setting_value' => '123 Education Street, Knowledge City', 'created_at' => now(), 'updated_at' => now()],
            ['setting_key' => 'phone', 'setting_value' => '+1234567890', 'created_at' => now(), 'updated_at' => now()],
            ['setting_key' => 'email', 'setting_value' => 'contact@learningcenter.com', 'created_at' => now(), 'updated_at' => now()],
            ['setting_key' => 'logo', 'setting_value' => 'logo.png', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Insert sample rooms
        DB::table('rooms')->insert([
            [
                'name' => 'Room A1',
                'capacity' => 30,
                'location' => 'Building A - Floor 1',
                'description' => 'Standard classroom with projector',
                'status' => 'available',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Room B2',
                'capacity' => 50,
                'location' => 'Building B - Floor 2',
                'description' => 'Large seminar room with audio system',
                'status' => 'available',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Lab C1',
                'capacity' => 20,
                'location' => 'Building C - Floor 1',
                'description' => 'Computer lab with 20 workstations',
                'status' => 'available',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);

        // Insert sample categories
        DB::table('categories')->insert([
            [
                'name' => 'Programming',
                'slug' => 'programming',
                'description' => 'Software development courses',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Design',
                'slug' => 'design',
                'description' => 'UI/UX and graphic design courses',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Business',
                'slug' => 'business',
                'description' => 'Business and management courses',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}
