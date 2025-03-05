<?php

namespace Database\Seeders;

use App\Models\Post;
use Illuminate\Database\Seeder;

class PostsTableSeeder extends Seeder
{
    public function run()
    {
        // Crear 200 posts
        Post::factory()->count(200)->create();
    }
}
