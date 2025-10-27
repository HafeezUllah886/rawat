<?php

namespace Database\Seeders;

use App\Models\account;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class accountsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        account::create(
            [
                'title' => 'Cash',
                'type' => 'Business',
                'Category' => 'Cash',
            ]
        );

        account::create(
            [
                'title' => 'Test Customer',
                'type' => 'Customer',
            ]
        );

        account::create(
            [
                'title' => 'Test Vendor',
                'type' => 'Vendor',
            ]
        );
    }
}
