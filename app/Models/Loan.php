<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use HasFactory;

    protected $fillable = [
        'borrower_name',
        'book_id',
        'loan_date',
        'due_date',
        'return_date',
        'returned_at',
        'status',
        'condition',
        'damage_note',
    ];

    protected $casts = [
        'loan_date' => 'date',
        'due_date' => 'date',
        'return_date' => 'date',
        'returned_at' => 'date',
    ];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }
}

