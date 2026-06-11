<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        // 'name' => 'Test User',
        // 'email' => 'test@example.com',
        // ]);

        // 2. Buat beberapa Kategori default
        Category::create(['name' => 'Laravel']);
        Category::create(['name' => 'Backend']);
        Category::create(['name' => 'Tips & Tricks']);

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }
}
