<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLoanRequest;
use App\Models\Book;
use App\Models\Loan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class LoanController extends Controller
{
    /**
     * Show form for new loan.kw
     */
    public function create(): View
    {
        $user = auth()->user();
        $isAdmin = $user->role === 'admin';
        
        $booksQuery = Book::where('stock', '>', 0);
        if (!$isAdmin) {
            // Siswa only see available books not already borrowed by them
            $booksQuery->whereDoesntHave('loans', function ($q) use ($user) {
                $q->where('user_id', $user->id)
                  ->where('status', 'dipinjam');
            });
        }
        
        $books = $booksQuery->withTrashed(false)->get();
        return view('loans.create', compact('books', 'isAdmin'));
    }

    /**
     * Store new loan.
     */
    public function store(StoreLoanRequest $request): RedirectResponse
    {
        $userId = auth()->id();
        $bookId = $request->input('book_id');
        if (!$bookId) {
            return back()->withErrors(['book_id' => 'Buku harus dipilih'])->withInput();
        }
        
        DB::transaction(function () use ($userId, $bookId, $request) {
            // Check stock
            $book = Book::findOrFail($bookId);
            if ($book->stock <= 0) {
                throw new \Exception('Stok buku habis');
            }
            
            // Check duplicate for siswa
            if (auth()->user()->role === 'siswa') {
                $existing = Loan::where('user_id', $userId)
                    ->where('book_id', $bookId)
                    ->where('status', 'dipinjam')
                    ->exists();
                if ($existing) {
                    throw new \Exception('Anda sudah meminjam buku ini!');
                }
            }
            
            $loanData = $request->validated();
            $loanData['user_id'] = $userId;
            $loanData['borrower_name'] = $loanData['borrower_name'] ?? auth()->user()->name;
            $loan = Loan::create($loanData);
            
            $book->decrement('stock');
        });
        
        return redirect()->route('loans.active')
            ->with('success', 'Peminjaman berhasil! Jatuh tempo: ' . date('d/m/Y', strtotime($request->return_date)));
    }


    /**
     * Return loan (early or due).
     */
public function return(Loan $loan): RedirectResponse
{
    if ($loan->status !== 'dipinjam') {
        return back()->with('error', 'Peminjaman sudah dikembalikan.');
    }

    DB::transaction(function () use ($loan) {
        $dueDate = $loan->return_date ?? $loan->loan_date->copy()->addDays(7);
        $lateDays = now()->gt($dueDate) ? floor(now()->diffInDays($dueDate)) : 0;
        $fine = $lateDays * 1000; // Rp1000/hari

        $loan->update([
            'returned_at' => now(),
            'status' => 'dikembalikan',
            'fine' => $fine,
        ]);

        $loan->book->increment('stock');
    });

    $fineNote = $loan->fine > 0 ? ' (Denda: Rp' . number_format($loan->fine) . ')' : '';
    
    return back()->with('success', 'Buku berhasil dikembalikan!' . $fineNote);
}

    /**
     * Show active loans (dipinjam).
     */
    public function active(Request $request): View
    {
        $user = auth()->user();
        $isAdmin = $user->role === 'admin';

        $query = Loan::with(['book', 'user'])
            ->where('status', 'dipinjam');

        if (!$isAdmin) {
            $query->where('user_id', $user->id);
        }

        $query->when($request->filled('status') && $request->status === 'terlambat', function ($q) {
            $q->where('return_date', '<', now());
        })->latest();

        $loans = $query->paginate(10);

        $overdueLoans = $isAdmin 
            ? $query->cloneWithout(['orders', 'limit'])->where('return_date', '<', now())->count()
            : $loans->where('return_date', '<', now())->count();

        return view('loans.active', compact('loans', 'overdueLoans', 'isAdmin'));
    }

    /**
     * Show completed history (dikembalikan).
     */
    public function history(Request $request): View
    {
        $user = auth()->user();
        $isAdmin = $user->role === 'admin';

        $query = Loan::with(['book', 'user'])
            ->where('status', 'dikembalikan');

        if (!$isAdmin) {
            $query->where('user_id', $user->id);
        }

        $loans = $query->latest()->paginate(10);

        return view('loans.history', compact('loans', 'isAdmin'));
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

        return redirect()->route('loans.history')
            ->with('success', 'Kondisi buku berhasil diupdate & dikembalikan!');
    }
}





