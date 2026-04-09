<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Book;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $books = [
            [
                'title' => 'Laskar Pelangi',
                'author' => 'Andrea Hirata',
                'isbn' => '9789793062741',
                'publisher' => 'Bentang Pustaka',
                'publication_year' => 2005,
                'category' => 'Fiksi',
                'stock' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Bumi Manusia',
                'author' => 'Pramoedya Ananta Toer',
                'isbn' => '9786020393323',
                'publisher' => 'Lentera Dipantara',
                'publication_year' => 1980,
                'category' => 'Fiksi Sejarah',
                'stock' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Sapiens: Manusia Yang Sangat Lengket',
                'author' => 'Yuval Noah Harari',
                'isbn' => '9789797806591',
                'publisher' => 'Pustaka Al Kautsar',
                'publication_year' => 2014,
                'category' => 'Non-Fiksi',
                'stock' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Atomic Habits',
                'author' => 'James Clear',
                'isbn' => '9786024778420',
                'publisher' => 'Penerbit Bentang',
                'publication_year' => 2018,
                'category' => 'Pengembangan Diri',
                'stock' => 6,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Dune',
                'author' => 'Frank Herbert',
                'isbn' => '9781974970464',
                'publisher' => 'Ace Books',
                'publication_year' => 1965,
                'category' => 'Fiksi Sains',
                'stock' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Algoritma Pemrograman',
                'author' => 'Ir. Napsiah, MT',
                'isbn' => '9786021494195',
                'publisher' => 'Andi',
                'publication_year' => 2019,
                'category' => 'Komputer',
                'stock' => 7,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Clean Code',
                'author' => 'Robert C. Martin',
                'isbn' => '9780132350884',
                'publisher' => 'Prentice Hall',
                'publication_year' => 2008,
                'category' => 'Komputer',
                'stock' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Manajemen Perpustakaan',
                'author' => 'Dr. Hj. Sumiati, M.Pd',
                'isbn' => '9786232441783',
                'publisher' => 'Deepublish',
                'publication_year' => 2022,
                'category' => 'Perpustakaan',
                'stock' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($books as $bookData) {
            Book::updateOrCreate(
                ['isbn' => $bookData['isbn']],
                $bookData
            );
        }
    }
}

