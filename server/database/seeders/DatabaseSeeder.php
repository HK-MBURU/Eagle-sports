<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
  public function run(): void
    {
        // Create Admin User
        User::updateOrCreate(
            ['email' => 'admin@eaglesports.co.ke'],
            [
                'name'              => 'Admin',
                'password'          => Hash::make('12345678'),
                'email_verified_at' => now(),
            ]
        );

        // Create Test User
        User::updateOrCreate(
            ['email' => 'test@example.com'],
            [
                'name'              => 'Test User',
                'password'          => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        // Optional: Create additional test users
        // User::factory(10)->create();

        $this->call([
            VideoSeeder::class,
            WomenRunningSeeder::class,
            FlagshipEventSeeder::class,
            CalendarEventSeeder::class,
            ResultsCentreSeeder::class,
        ]);
    }
}
