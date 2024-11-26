<?php

namespace Tests\Feature;

use Database\Seeders\UserSeeder;
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
}
