<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\InventoryBook;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InventoryBookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $book = Book::query()->limit(1)->first();

        InventoryBook::create([
            'book_id' => $book->id,
            'stok' => 1111,
            'price' => 2222,
            'is_availaible' => 'tersedia'
        ]);
    }
}
