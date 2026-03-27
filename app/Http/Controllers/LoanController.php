<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLoanRequest;
use App\Models\Book;
use App\Models\Loan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LoanController extends Controller
{
    /**
     * Show form for new loan.
     */
    public function create(): View
    {
        $books = Book::where('stock', '>', 0)->get();
        return view('loans.create', compact('books'));
    }

    /**
     * Store new loan.
     */
    public function store(StoreLoanRequest $request): RedirectResponse
    {
        $loan = Loan::create($request->validated());
        
        // Update book stock
        $book = Book::find($request->book_id);
        $book->decrement('stock');
        
        return redirect()->route('loans.history')
            ->with('success', 'Peminjaman berhasil dicatat!');
    }


    /**
     * Return loan (early or due).
     */
    public function return(Loan $loan): RedirectResponse
    {
        if ($loan->status !== 'dipinjam') {
            return back()->with('error', 'Peminjaman sudah dikembalikan.');
        }

        $loan->update([
            'return_date' => now(),
            'status' => 'dikembalikan'
        ]);

        $loan->book->increment('stock');

        return back()->with('success', 'Buku berhasil dikembalikan!');
    }

    /**
     * Show loan history.
     */
    public function history(Request $request): View
    {
        $query = Loan::with('book')->latest();
        
        $loans = $query->paginate(10);
        
        return view('loans.history', compact('loans'));
    }
}


