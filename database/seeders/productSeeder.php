<?php

namespace Database\Seeders;

use App\Models\products;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class productSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        products::create(
            [
                'name' => 'Test Product',
                'code' => rand(11111111, 99999999),
                'bike' => '125cc',
                'model' => '2019',
                'brand' => 'Honda',
                'alert' => '20',
            ],
        );
        products::create(
            [
                'name' => 'Test Product 3',
                'code' => rand(11111111, 99999999),
                'bike' => '150cc',
                'model' => '2019',
                'brand' => 'Honda',
                'alert' => '20',
            ]
        );
        products::create(
            [
                'name' => 'Test Product 1',
                'code' => rand(11111111, 99999999),
                'bike' => '70cc',
                'model' => '2019',
                'brand' => 'Honda',
                'alert' => '20',
            ]
        );
    }
}
