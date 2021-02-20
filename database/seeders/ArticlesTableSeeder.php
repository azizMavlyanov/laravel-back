<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Article;

class ArticlesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Let's truncate our existing records to start from scratch.
        Article::truncate();

        $faker = \Faker\Factory::create();

        // And now, let's create a few articles in our database:
        for ($i = 0; $i < 3; $i++) {
            Article::create([
                'body' => $faker->paragraph,
                'heading' => $faker->sentence,
                'subheading' => $faker->sentence,
                'slug' => $faker->unique()->slug(),
                'meta' => $faker->sentence,
                'version' => $faker->numberBetween(1, 5),
                'user_id' => 3,
                'photo_id' => 12,
            ]);
        }
    }
}
