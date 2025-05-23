<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\User;
use Database\Seeders\BookSearchSeeder;
use Database\Seeders\BookSeeder;
use Database\Seeders\SearchSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class BookTest extends TestCase
{
    public function testCreateSuccess()
    {
        $this->seed([UserSeeder::class]); //get data user_id untuk autentikasi

        $this->post('/api/books',
            [
                'title' => 'si kancil',
                'author' => 'anonymous',
                'publisher' => 'anonymous',
                'publication_year' => 2024,
                'genre' => 'cerpen'
            ],
            [
                'Authorization' => 'test'
            ])->assertStatus(201)
            ->assertJson([
                'data' => [
                    'title' => 'si kancil',
                    'author' => 'anonymous',
                    'publisher' => 'anonymous',
                    'publication_year' => 2024,
                    'genre' => 'cerpen'
                ]
            ]);
    }

    public function testCreateFailed()
    {
        $this->seed([UserSeeder::class]); // get data user_id untuk autentikasi

        $this->post('/api/books',
            [
                'title' => '',
                'author' => '',
                'publisher' => 'anonymous',
                'publication_year' => 2024,
                'genre' => 'cerpen'
            ],
            [
                'Authorization' => 'test'
            ])->assertStatus(400)
            ->assertJson([
                'errors' => [
                    'title' => [
                        'The title field is required.'
                    ],
                    'author' => [
                        'The author field is required.'
                    ]
                ]
            ]);
    }

    public function testCreateUnauthorized()
    {
        $this->seed([UserSeeder::class]);

        $this->post('/api/books',
            [
                'title' => '',
                'author' => 'anonymous',
                'publisher' => 'anonymous',
                'publication_year' => 2024,
                'genre' => 'cerpen'
            ],
            [
                'Authorization' => 'salah'
            ])->assertStatus(401)
            ->assertJson([
                'errors' => [
                    'message' => [
                        'unauthorized'
                    ]
                ]
            ]);
    }

    public function testGetSuccess()
    {
        $this->seed([UserSeeder::class, BookSeeder::class]);
        $book = Book::query()->limit(1)->first();

        $this->get('/api/books/' . $book->id,[
                'Authorization' => 'test'
            ])->assertStatus(200)
            ->assertJson([
                'data' => [
                    'title' => 'test',
                    'author' => 'test',
                    'publisher' => 'test',
                    'publication_year' => 2024,
                    'genre' => 'cerpen'
                ]
            ]);
    }

    public function testGetNotFound()
    {
        $this->seed([UserSeeder::class, BookSeeder::class]);
        $book = Book::query()->limit(1)->first();

        $this->get('/api/books/' . ($book->id + 1),[
            'Authorization' => 'test'
        ])->assertStatus(404)
            ->assertJson([
                'errors' => [
                    "message" => [
                        "not found"
                    ]
                ]
            ]);
    }

    public function testUpdateSuccess()
    {
        $this->seed([UserSeeder::class, BookSeeder::class]);
        $book = Book::query()->limit(1)->first();

        $this->put('/api/books/' . $book->id, [
            'title' => 'test2',
            'author' => 'test2',
            'publisher' => 'test2',
            'publication_year' => 2025,
            'genre' => 'sejarah'
        ],[
            'Authorization' => 'test'
        ])->assertStatus(200)
            ->assertJson([
                'data' => [
                    'title' => 'test2',
                    'author' => 'test2',
                    'publisher' => 'test2',
                    'publication_year' => 2025,
                    'genre' => 'sejarah'
                ]
            ]);
    }

    public function testUpdateValidationError()
    {
        $this->seed([UserSeeder::class, BookSeeder::class]);
        $book = Book::query()->limit(1)->first();

        $this->put('/api/books/' . $book->id, [
            'title' => '',
            'author' => 'test2',
            'publisher' => 'test2',
            'publication_year' => 2025,
            'genre' => 'sejarah'
        ],[
            'Authorization' => 'test'
        ])->assertStatus(400)
            ->assertJson([
                'errors' => [
                    'title' => [
                        'The title field is required.'
                    ]
                ]
            ]);
    }

    public function testDeleteSucces() {
        $this->seed([UserSeeder::class, BookSeeder::class]);
        $book = Book::query()->limit(1)->first();

        $this->delete('/api/books/' . $book->id, [], [
            'Authorization' => 'test'
        ])->assertStatus(200)
            ->assertJson([
                'data' => true
            ]);
    }

    public function testDeleteNotFound() 
    {
        $this->seed([UserSeeder::class, BookSeeder::class]);
        $book = Book::query()->limit(1)->first();

        $this->delete('/api/books/' . ($book->id + 1), [], [
            'Authorization' => 'test'
        ])->assertStatus(404)
            ->assertJson([
                'errors' => [
                    'message' => [
                        'not found'
                    ]
                ]
            ]);
    }

    public function testSearchByTitleName()
    {
        $this->seed([UserSeeder::class, BookSearchSeeder::class]);

        $response = $this->get('/api/books?name=title_name', [
            'Authorization' => 'test'
        ])
            ->assertStatus(200)
            ->json();
        
        Log::info(json_encode($response, JSON_PRETTY_PRINT));

        self::assertEquals(10, count($response['data']));
        self::assertEquals(20, $response['meta']['total']);
    
    }
    public function testSearchByAuthorName()
    {
        $this->seed([UserSeeder::class, BookSearchSeeder::class]);

        $response = $this->get('/api/books?name=author_name', [
            'Authorization' => 'test'
        ])
            ->assertStatus(200)
            ->json();
        
        Log::info(json_encode($response, JSON_PRETTY_PRINT));

        self::assertEquals(10, count($response['data']));
        self::assertEquals(20, $response['meta']['total']);
    }
    
    public function testSearchByPublishPublisher()
    {
        $this->seed([UserSeeder::class, BookSearchSeeder::class]);

        $response = $this->get('/api/books?publish=test_publish', [
            'Authorization' => 'test'
        ])
            ->assertStatus(200)
            ->json();
        
        Log::info(json_encode($response, JSON_PRETTY_PRINT));

        self::assertEquals(10, count($response['data']));
        self::assertEquals(20, $response['meta']['total']);
    }

    public function testSearchByPublishPublicationYear()
    {
        $this->seed([UserSeeder::class, BookSearchSeeder::class]);

        $response = $this->get('/api/books?publish=2000', [
            'Authorization' => 'test'
        ])
            ->assertStatus(200)
            ->json();
        
        Log::info(json_encode($response, JSON_PRETTY_PRINT));

        self::assertEquals(10, count($response['data']));
        self::assertEquals(20, $response['meta']['total']);
    }
    
    public function testSearchByGenre()
    {
        $this->seed([UserSeeder::class, BookSearchSeeder::class]);

        $response = $this->get('/api/books?genre=cerpen', [
            'Authorization' => 'test'
        ])
            ->assertStatus(200)
            ->json();
        
        Log::info(json_encode($response, JSON_PRETTY_PRINT));

        self::assertEquals(10, count($response['data']));
        self::assertEquals(20, $response['meta']['total']);
    }

    public function testSearchNotFound()
    {
        $this->seed([UserSeeder::class, BookSearchSeeder::class]);

        $response = $this->get('/api/books?name=tidakada', [
            'Authorization' => 'test'
        ])
            ->assertStatus(200)
            ->json();
        
        Log::info(json_encode($response, JSON_PRETTY_PRINT));

        self::assertEquals(0, count($response['data']));
        self::assertEquals(0, $response['meta']['total']);
    }
    
    public function testSearchWithPage()
    {
        $this->seed([UserSeeder::class, BookSearchSeeder::class]);

        $response = $this->get('/api/books?size=5&page=2', [
            'Authorization' => 'test'
        ])
            ->assertStatus(200)
            ->json();
        
        Log::info(json_encode($response, JSON_PRETTY_PRINT));

        self::assertEquals(5, count($response['data']));
        self::assertEquals(20, $response['meta']['total']);
        self::assertEquals(2, $response['meta']['current_page']);
    }
}
