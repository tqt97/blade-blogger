<?php

namespace Database\Seeders;

use App\Models\Category;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Category::factory()->count(100)->create();

        User::factory()->create([
            'name' => 'TuanTQ',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('12341234'),
        ]);

        User::factory()
            ->count(10)
            ->create()
            ->each(function (User $user) {
                Post::factory()
                    ->count(5)
                    ->create([
                        'user_id' => $user->id,
                        'category_id' => Category::active()->inRandomOrder()->first()->id,
                    ]);
            });

    }
}
