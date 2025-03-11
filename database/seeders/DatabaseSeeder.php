<?php

namespace Database\Seeders;

use App\Models\Schedule;
use App\Models\User;
use Carbon\Carbon;
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
            'email' => 'test@example.com',
        ]);

        // seed schedule
        Schedule::create([
            'day_of_week' => 'Monday',
            'open_time' => '08:00',
            'close_time' => '16:00',
            'lunch_start' => '12:00',
            'lunch_end' => '12:45',
            'every_other_week' => false,
            'eow_start_date' => null,
        ]);

        Schedule::create([
            'day_of_week' => 'Wednesday',
            'open_time' => '08:00',
            'close_time' => '16:00',
            'lunch_start' => '12:00',
            'lunch_end' => '12:45',
            'every_other_week' => false,
            'eow_start_date' => null,
        ]);

        Schedule::create([
            'day_of_week' => 'Friday',
            'open_time' => '08:00',
            'close_time' => '16:00',
            'lunch_start' => '12:00',
            'lunch_end' => '12:45',
            'every_other_week' => false,
            'eow_start_date' => null,
        ]);

        Schedule::create([
            'day_of_week' => 'Saturday',
            'open_time' => '08:00',
            'close_time' => '16:00',
            'lunch_start' => '12:00',
            'lunch_end' => '12:45',
            'every_other_week' => true,
            'eow_start_date' => Carbon::create(2025, 2, 1)->format('Y-m-d'),
        ]);

        


    }
}
