<?php

namespace Tests\Feature;

use Database\Seeders\BankSearchSeeder;
use Illuminate\Support\Facades\Log;
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

    public function testDeleteSuccess()
    {
        $this->seed([UserSeeder::class, BankSeeder::class]);
        $bank = Bank::query()->limit(1)->first();

        $this->delete('/api/banks/' . $bank->id, [
            
        ],[
            'Authorization' => 'test'
        ])->assertStatus(200)
            ->assertJson([
                'data' => [
                    'name' => true
                ]
            ]);
    }

    public function testDeleteNotFound()
    {
        $this->seed([UserSeeder::class, BankSeeder::class]);
        $bank = Bank::query()->limit(1)->first();

        $this->delete('/api/banks/' . ($bank->id + 1), [
            
        ],[
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

    public function testSeachByProviderName()
    {
        $this->seed([UserSeeder::class, BankSearchSeeder::class]);

        $response = $this->get('/api/banks?identity=provider', [
            'Authorization' => 'test'
        ])->assertStatus(200)
            ->json();
        
        Log::info(json_encode($response, JSON_PRETTY_PRINT));

        self::assertEquals(10, count($response['data']));
        self::assertEquals(20, $response['meta']['total']);
    }
    
    public function testSeachByCustomerAccountName()
    {
        $this->seed([UserSeeder::class, BankSearchSeeder::class]);

        $response = $this->get('/api/banks?identity=customer', [
            'Authorization' => 'test'
        ])->assertStatus(200)
            ->json();
        
        Log::info(json_encode($response, JSON_PRETTY_PRINT));

        self::assertEquals(10, count($response['data']));
        self::assertEquals(20, $response['meta']['total']);
    }
    
    public function testSeachByAccountNumber()
    {
        $this->seed([UserSeeder::class, BankSearchSeeder::class]);

        $response = $this->get('/api/banks?number=111', [
            'Authorization' => 'test'
        ])->assertStatus(200)
            ->json();
        
        Log::info(json_encode($response, JSON_PRETTY_PRINT));

        self::assertEquals(10, count($response['data']));
        self::assertEquals(20, $response['meta']['total']);
    }

    public function testSeachByImage()
    {
        $this->seed([UserSeeder::class, BankSearchSeeder::class]);

        $response = $this->get('/api/banks?image=test', [
            'Authorization' => 'test'
        ])->assertStatus(200)
            ->json();
        
        Log::info(json_encode($response, JSON_PRETTY_PRINT));

        self::assertEquals(10, count($response['data']));
        self::assertEquals(20, $response['meta']['total']);
    }

    public function testSeachNotFound()
    {
        $this->seed([UserSeeder::class, BankSearchSeeder::class]);

        $response = $this->get('/api/banks?identity=tidakada', [
            'Authorization' => 'test'
        ])->assertStatus(200)
            ->json();
        
        Log::info(json_encode($response, JSON_PRETTY_PRINT));

        self::assertEquals(0, count($response['data']));
        self::assertEquals(0, $response['meta']['total']);
    }
    
    public function testSeachWithPage()
    {
        $this->seed([UserSeeder::class, BankSearchSeeder::class]);

        $response = $this->get('/api/banks?size=5&page=2', [
            'Authorization' => 'test'
        ])->assertStatus(200)
            ->json();
        
        Log::info(json_encode($response, JSON_PRETTY_PRINT));

        self::assertEquals(5, count($response['data']));
        self::assertEquals(20, $response['meta']['total']);
        self::assertEquals(2, $response['meta']['current_page']);
    }
}
