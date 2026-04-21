<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLoanRequest extends FormRequest
{
    /**
     * Menentukan apakah user diizinkan membuat peminjaman (selalu true).
     *
     * @return bool Selalu true
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Aturan validasi untuk store peminjaman baru.
     *
     * @return array<string, string> Rules untuk book_id (exists), quantity (1-10), dates logic
     */
    public function rules(): array
    {
        return [
            'book_id' => 'required|exists:books,id',
            'quantity' => 'required|integer|min:1|max:10',
            'loan_date' => 'required|date|before_or_equal:today',
            'return_date' => 'required|date|after:loan_date',
        ];
    }

    /**
     * Pesan error custom untuk peminjaman.
     *
     * @return array<string, string> Pesan untuk book, dates
     */
    public function messages(): array
    {
        return [
            'book_id.required' => 'Buku wajib dipilih.',
            'loan_date.required' => 'Tanggal pinjam wajib diisi.',
            'return_date.after' => 'Tanggal kembali harus setelah tanggal pinjam.',
        ];
    }
}

