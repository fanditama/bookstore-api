<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::where('username', 'test')->first();
        Book::create([
            'title' => 'test',
            'author' => 'test',
            'publisher' => 'test',
            'publication_year' => 2024,
            'genre' => 'cerpen',
            'user_id' => $user->id
        ]);
    }
}
