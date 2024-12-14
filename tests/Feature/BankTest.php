<?php

namespace Tests\Feature;

use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BankTest extends TestCase
{
    public function testCreateSuccess()
    {
        $this->seed([UserSeeder::class]);

        $this->post('/api/banks', [
            'name' => 'test',
            'account_number' => 1111,
            'account_name' => 'test',
            'image' => 'test'
        ],[
            'Authorization' => 'test'
        ])->assertStatus(201)
            ->assertJson([
                'data' => [
                    'name' => 'test',
                    'account_number' => 1111,
                    'account_name' => 'test',
                    'image' => 'test'
                ]
            ]);
    }
}
