<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLoanRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'book_id' => 'required|exists:books,id',
            'loan_date' => 'required|date|before_or_equal:today',
            'return_date' => 'required|date|after:loan_date',
        ];
    }

    public function messages(): array
    {
        return [
            'book_id.required' => 'Buku wajib dipilih.',
            'loan_date.required' => 'Tanggal pinjam wajib diisi.',
            'return_date.after' => 'Tanggal kembali harus setelah tanggal pinjam.',
        ];
    }
}

