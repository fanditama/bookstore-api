<?php

namespace Database\Seeders;

use App\Http\Resources\BankResource;
use App\Models\Bank;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::where('username', 'test')->first();

        Bank::create([
            'name' => 'test',
            'account_number' => 1111,
            'account_name' => 'test',
            'image' => 'test.img',
            'user_id' => $user->id
        ]);
    }
}
