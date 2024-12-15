<?php

namespace Database\Seeders;

use App\Models\Bank;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BankSearchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::where('username', 'test')->first();

        for ($i=0; $i < 20; $i++) { 
            Bank::create([
                'name' => 'provider' . $i,
                'account_number' => 111 . $i,
                'account_name' => 'customer' . $i,
                'image' => 'test' . $i . '.img',
                'user_id' => $user->id
            ]);
        }
    }
}
