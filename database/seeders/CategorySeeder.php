<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Fiksi', 'description' => 'Karya fiksi/sastra'],
            ['name' => 'Non-Fiksi', 'description' => 'Buku non-fiksi/informasi'],
            ['name' => 'Komputer', 'description' => 'Buku tentang pemrograman & IT'],
            ['name' => 'Pengembangan Diri', 'description' => 'Motivasi & self-improvement'],
            ['name' => 'Perpustakaan', 'description' => 'Referensi perpustakaan'],
            ['name' => 'Sains Fiksi', 'description' => 'Fiksi ilmiah'],
            ['name' => 'Sejarah', 'description' => 'Buku sejarah'],
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(
                ['name' => $category['name']],
                $category
            );
        }
    }
}

