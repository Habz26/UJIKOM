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
$loanData = $request->validated();
        
        $loan = Loan::create($loanData);
        
        // Update book stock
        $book = Book::find($request->book_id);
        $book->decrement('stock');
        
        return redirect()->route('loans.active')
            ->with('success', 'Peminjaman berhasil dicatat! Jatuh tempo: ' . $loan->due_date->format('d/m/Y'));
    }


    /**
     * Return loan (early or due).
     */
    public function return(Loan $loan): RedirectResponse
    {
        if ($loan->status !== 'dipinjam') {
            return back()->with('error', 'Peminjaman sudah dikembalikan.');
        }

        $dueDate = $loan->due_date ?? $loan->loan_date->copy()->addDays(7);
        $isOverdue = now()->gt($dueDate);
        $lateNote = $isOverdue ? ' (TERLAMBAT ' . now()->diffInDays($dueDate) . ' hari)' : '';

        $loan->update([
            'returned_at' => now(),
            'status' => 'dikembalikan'
        ]);

        $loan->book->increment('stock');

        return back()->with('success', 'Buku berhasil dikembalikan!' . $lateNote);
    }

    /**
     * Show active loans (dipinjam).
     */
    public function active(Request $request): View
    {
        $overdueLoans = Loan::where('status', 'dipinjam')
            ->where('loan_date', '<=', now()->subDays(7))
            ->count();

        $query = Loan::with('book')
            ->where('status', 'dipinjam')
            ->when($request->filled('status') && $request->status === 'terlambat', function ($q) {
                $q->where('loan_date', '<=', now()->subDays(7));
            })
            ->latest();
        
        $loans = $query->paginate(10);
        
        return view('loans.active', compact('loans', 'overdueLoans'));
    }

    /**
     * Show completed history (dikembalikan).
     */
    public function history(Request $request): View
    {
        $query = Loan::with('book')
            ->where('status', 'dikembalikan')
            ->latest();
        
        $loans = $query->paginate(10);
        
        return view('loans.history', compact('loans'));
    }

    /**
     * Update loan condition/damage report.
     */
    public function updateCondition(Request $request, Loan $loan): RedirectResponse
    {
        $request->validate([
            'condition' => 'required|in:baik,rusak',
            'damage_note' => 'nullable|string|max:500'
        ]);

        $loan->update([
            'condition' => $request->condition,
            'damage_note' => $request->damage_note,
            'returned_at' => now(),
            'status' => 'dikembalikan'
        ]);

        $loan->book->increment('stock');

        return redirect()->route('dashboard')
            ->with('success', 'Kondisi buku berhasil diupdate & dikembalikan!');
    }
}





