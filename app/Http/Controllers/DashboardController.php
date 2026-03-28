<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Loan;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
$topBooks = Book::select('title')
            ->withCount('loans as loan_count')
            ->orderByDesc('loan_count')
            ->limit(5)
            ->get();

        $activeLoans = Loan::with('book')
            ->where('status', 'dipinjam')
            ->latest()
            ->limit(10)
            ->get();

        $stats = [
            'total_books' => Book::count(),
            'books_loaned' => Book::where('stock', '<', 10)->count(),
            'total_loans' => Loan::count(),
            'active_loans' => Loan::where('status', 'dipinjam')->count(),
            'overdue_count' => Loan::where('return_date', '<', now())
                ->where('status', '!=', 'dikembalikan')
                ->count(),
        ];

        return view('dashboard', compact('stats', 'topBooks', 'activeLoans'));
    }
}

