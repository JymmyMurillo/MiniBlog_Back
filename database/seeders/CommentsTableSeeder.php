<?php

namespace Database\Seeders;

use App\Models\Comment;
use Illuminate\Database\Seeder;

class CommentsTableSeeder extends Seeder
{
    public function run()
    {
        // Crear 1000 comentarios
        Comment::factory()->count(1000)->create();
    }
}
