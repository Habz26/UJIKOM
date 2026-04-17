<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Book extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'author', 
        'year',
        'stock',
'publisher',
        'id_kategori',
    ];

    protected $casts = [
        'year' => 'integer',
    ];

    public function loans()
    {
        return $this->hasMany(Loan::class);
    }

    public function kategori()
    {
        return $this->belongsTo(Category::class, 'id_kategori');
    }
}

