<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class BookFactory extends Factory
{
    public function definition(): array
    {
        return [
            'judul'   => fake()->sentence(3),
            'penulis' => fake()->name(),
            'stok'    => fake()->numberBetween(1, 50),
            'harga'   => fake()->numberBetween(10000, 500000),
        ];
    }
}