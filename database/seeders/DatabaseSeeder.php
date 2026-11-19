<?php

namespace Database\Seeders;

use App\Models\SchoolClass;
use App\Models\Students;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        SchoolClass::factory()->count(7)->create();
        $schoolClasses = SchoolClass::all();
        Students::factory()
            ->count(20)
            ->sequence(fn() => [
                'class_id' => $schoolClasses->random()->id,
            ])
            ->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }
}
