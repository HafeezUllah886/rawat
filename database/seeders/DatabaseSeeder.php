<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\account;
use App\Models\products;
use Database\Factories\productFactory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(warehouseSeeder::class);
        $this->call(usersSeeder::class);
        $this->call(accountsSeeder::class);
        $this->call(productSeeder::class);
    }
}
