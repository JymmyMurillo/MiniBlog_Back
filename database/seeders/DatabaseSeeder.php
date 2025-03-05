<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            UsersTableSeeder::class,    // Primero usuarios
            PostsTableSeeder::class,    // Luego posts
            CommentsTableSeeder::class, // Finalmente comentarios
        ]);
    }
}
