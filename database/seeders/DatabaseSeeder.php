<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
         User::factory(20)->create();
         Event::factory(10)->create();

         $events = Event::all();

        foreach ($events as $event) {
            $users = User::query()->inRandomOrder()->limit(rand(1, 10))->get()->pluck('id');
            $event->users()->attach($users);
        }
    }
}
