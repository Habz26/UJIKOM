<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
    ];

    public function books()
    {
        return $this->hasMany(Book::class, 'id_kategori');
    }

    public function highlightName($search = null)
    {
        if (!$search) return $this->name;
        $pattern = '/(' . preg_quote($search, '/') . ')/i';
        return preg_replace($pattern, '<mark class="bg-warning text-dark fw-bold">$1</mark>', $this->name);
    }
}

