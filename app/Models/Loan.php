<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use HasFactory;

    /**
     * Atribut fillable untuk mass assignment.
     */
    protected $fillable = [
        'user_id',
        'borrower_name',
        'book_id',
        'quantity',
        'borrowed_quantity',
        'returned_quantity',
        'pending_return_quantity',
        'loan_date',
        'due_date',
        'return_date',
        'returned_at',
        'fine',
        'status',
        'verification_status',
        'condition',
        'damage_note',
        'fine_paid',
        'fine_status', 
        'fine_paid_at',
    ];

    /**
     * Casting untuk date dan decimal fields.
     */
    protected $casts = [
        'loan_date' => 'date',
        'due_date' => 'date',
        'return_date' => 'date',
        'returned_at' => 'date',
        'fine' => 'decimal:2',
        'fine_paid' => 'decimal:2',
    ];

    /**
     * Relasi belongsTo dengan model Book.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    /**
     * Relasi belongsTo dengan model User.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Accessor computed untuk denda outstanding (fine - fine_paid).
     *
     * @return float Nilai denda yang belum dibayar
     */
    public function getOutstandingFineAttribute()
    {
        return $this->fine - $this->fine_paid;
    }
}







