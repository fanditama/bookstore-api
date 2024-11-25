<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BookTest extends TestCase
{
    public function testCreateSuccess()
    {
        $this->post('/api/books', [
            'title' => 'si kancil',
            'author' => 'anonymous',
            'publisher' => 'anonymous',
            'publication_year' => 2024,
            'genre' => 'cerpen'
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
        $this->post('/api/books', [
            'title' => '',
            'author' => '',
            'publisher' => 'anonymous',
            'publication_year' => 2024,
            'genre' => 'cerpen'
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
