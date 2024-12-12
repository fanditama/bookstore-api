<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\InventoryBook;
use Database\Seeders\BookSeeder;
use Database\Seeders\InventoryBookSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class InventoryBookTest extends TestCase
{
    public function testCreateSuccess()
    {
        $this->seed([UserSeeder::class, BookSeeder::class]);
        $book = Book::query()->limit(1)->first();

        $this->post('/api/books/' . $book->id . '/inventoryBooks', 
            [
                'stok' => 1111,
                'price' => 2222,
                'is_availaible' => 'tersedia'
            ],
            [
                'Authorization' => 'test'
            ]
        
        )->assertStatus(201)
            ->assertJson([
                'data' => [
                    'stok' => 1111,
                    'price' => 2222,
                    'is_availaible' => 'tersedia'
                ]
            ]);
    }

    public function testCreateFailed()
    {
        $this->seed([UserSeeder::class, BookSeeder::class]);
        $book = Book::query()->limit(1)->first();

        $this->post('/api/books/' . $book->id . '/inventoryBooks', 
            [
                'stok' => 1111,
                'price' => 2222,
                'is_availaible' => ''
            ],
            [
                'Authorization' => 'test'
            ]
        
        )->assertStatus(400)
            ->assertJson([
                'errors' => [
                    'is_availaible' => [
                        'The is availaible field is required.'
                    ]
                ]
            ]);
    }

    public function testCreateBookNotFound()
    {
        $this->seed([UserSeeder::class, BookSeeder::class]);
        $book = Book::query()->limit(1)->first();

        $this->post('/api/books/' . ($book->id + 1) . '/inventoryBooks', 
            [
                'stok' => 1111,
                'price' => 2222,
                'is_availaible' => 'tersedia'
            ],
            [
                'Authorization' => 'test'
            ]
        
        )->assertStatus(404)
            ->assertJson([
                'errors' => [
                    'message' => [
                        'not found'
                    ]
                ]
            ]);
    }

    public function testGetSuccess()
    {
        $this->seed([UserSeeder::class, BookSeeder::class, InventoryBookSeeder::class]);
        $inventoryBook = InventoryBook::query()->limit(1)->first();

        $this->get('/api/books/' . $inventoryBook->book_id . '/inventoryBooks/' . $inventoryBook->id, [
            'Authorization' => 'test'
        ])->assertStatus(200)
            ->assertJson([
                'data' => [
                    'stok' => 1111,
                    'price' => 2222,
                    'is_availaible' => 'tersedia'
                ]
            ]);
    }

    public function testGetNotFound()
    {

    }
}
