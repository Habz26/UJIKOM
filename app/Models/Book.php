<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Book extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Atribut yang dapat diisi massal saat create/update.
     */
    protected $fillable = [
        'title',
        'author', 
        'year',
        'stock',
        'publisher',
        'id_kategori',
    ];

    /**
     * Konfigurasi casting tipe data attributes.
     */
    protected $casts = [
        'year' => 'integer',
    ];

    /**
     * Relasi one-to-many dengan model Loan (peminjaman buku ini).
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function loans()
    {
        return $this->hasMany(Loan::class);
    }

    /**
     * Relasi belongsTo dengan model Category via foreign key id_kategori.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function kategori()
    {
        return $this->belongsTo(Category::class, 'id_kategori');
    }
}

