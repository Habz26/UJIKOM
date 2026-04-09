<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Book;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Book>
 */
class BookFactory extends Factory
{
    protected $model = Book::class;

    public function definition(): array
    {
        $categories = [
            'Fiksi', 'Non-Fiksi', 'Komputer', 'Pengembangan Diri', 
            'Perpustakaan', 'Sains Fiksi', 'Sejarah', 'Biografi'
        ];

        $publishers = [
            'Gramedia Pustaka Utama', 'Bentang Pustaka', 'Erlangga', 
            'Andi Publisher', 'Deepublish', 'Pustaka Pelajar', 
            'Kompas', 'Mizan', 'Gava Media'
        ];

        $years = $this->faker->numberBetween(1950, date('Y'));

        return [
            'title' => $this->faker->sentence(4),
            'author' => $this->faker->name(),
            'year' => $years,
            'stock' => $this->faker->numberBetween(0, 20),
            'publisher' => $this->faker->randomElement($publishers),
            'category' => $this->faker->randomElement($categories),
        ];
    }
}

