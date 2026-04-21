<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    /**
     * Atribut fillable untuk kategori.
     */
    protected $fillable = [
        'name',
        'description',
    ];

    /**
     * Relasi one-to-many dengan Book via foreign key id_kategori.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function books()
    {
        return $this->hasMany(Book::class, 'id_kategori');
    }

    /**
     * Method untuk highlight nama kategori saat pencarian (HTML mark).
     *
     * @param string|null $search Kata kunci pencarian
     * @return string Nama kategori dengan highlight atau asli
     */
    public function highlightName($search = null)
    {
        if (!$search) return $this->name;
        $pattern = '/(' . preg_quote($search, '/') . ')/i';
        return preg_replace($pattern, '<mark class="bg-warning text-dark fw-bold">$1</mark>', $this->name);
    }
}

