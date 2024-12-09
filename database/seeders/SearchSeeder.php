<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SearchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::where('username', 'test')->first();

        for ($i=0; $i < 20; $i++) { 
            Book::create([
                'title' => 'title_name' . $i,
                'author' => 'author_name' . $i,
                'publisher' => 'test_publish' . $i,
                'publication_year' => 2000 . $i,
                'genre' => 'cerpen',
                'user_id' => $user->id
            ]);
        }
    }
}
