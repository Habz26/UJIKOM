<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'borrower_name',
        'book_id',
        'loan_date',
        'due_date',
        'return_date',
        'returned_at',
        'fine',
        'status',
        'condition',
        'damage_note',
    ];

    protected $casts = [
        'loan_date' => 'date',
        'due_date' => 'date',
        'return_date' => 'date',
        'returned_at' => 'date',
        'fine' => 'decimal:2',
    ];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}



