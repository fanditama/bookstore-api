<?php

namespace Tests\Feature;

use App\Models\Bank;
use Database\Seeders\BankSeeder;
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

    public function testGetSuccess()
    {
        $this->seed([UserSeeder::class, BankSeeder::class]);
        $bank = Bank::query()->limit(1)->first();

        $this->get('/api/banks/' . $bank->id, [
            'Authorization' => 'test'
        ])->assertStatus(200)
            ->assertJson([
                'data' => [
                    'name' => 'test',
                    'account_number' => 1111,
                    'account_name' => 'test',
                    'image' => 'test.img'
                ]
            ]);
    }

    public function testGetNotFound()
    {
        $this->seed([UserSeeder::class, BankSeeder::class]);
        $bank = Bank::query()->limit(1)->first();

        $this->get('/api/banks/' . ($bank->id + 1), [
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

    public function testGetOtherUserContact()
    {
        $this->seed([UserSeeder::class, BankSeeder::class]);
        $bank = Bank::query()->limit(1)->first();

        $this->get('/api/banks/' . $bank->id, [
            'Authorization' => 'test2'
        ])->assertStatus(404)
            ->assertJson([
                'errors' => [
                    'message' => [
                        'not found'
                    ]
                ]
            ]);
    }

    public function testUpdateSuccess()
    {
        $this->seed([UserSeeder::class, BankSeeder::class]);
        $bank = Bank::query()->limit(1)->first();

        $this->put('/api/banks/' . $bank->id, [
            'name' => 'test2',
            'account_number' => 11112,
            'account_name' => 'test2',
            'image' => 'test2'
        ],[
            'Authorization' => 'test'
        ])->assertStatus(200)
            ->assertJson([
                'data' => [
                    'name' => 'test2',
                    'account_number' => 11112,
                    'account_name' => 'test2',
                    'image' => 'test2'
                ]
            ]);

    }

    public function testUpdateValidationError()
    {
        $this->seed([UserSeeder::class, BankSeeder::class]);
        $bank = Bank::query()->limit(1)->first();

        $this->put('/api/banks/' . $bank->id, [
            'name' => '',
            'account_number' => 11112,
            'account_name' => 'test2',
            'image' => 'test2'
        ],[
            'Authorization' => 'test'
        ])->assertStatus(400)
            ->assertJson([
                'errors' => [
                    'name' => [
                        'The name field is required.'
                    ]
                ]
            ]);
    }
}
