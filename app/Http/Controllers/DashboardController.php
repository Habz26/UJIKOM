<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Loan;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $isAdmin = true; // Admin only

        $topBooks = Book::select('title')
            ->withCount('loans as loan_count')
            ->orderByDesc('loan_count')
            ->limit(5)
            ->get();

        $activeLoans = Loan::with(['book', 'user'])
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
            'due_today_count' => Loan::whereDate('return_date', now())
                ->where('status', 'dipinjam')
                ->count(),
        ];

        return view('dashboard', compact('stats', 'topBooks', 'activeLoans', 'isAdmin'));
    }

    public function siswa(Request $request)
    {
        $user = auth()->user();
        $isAdmin = false;

        $userLoans = $user->loans()->with('book');
        $topBooks = collect(); 
        $activeLoans = $userLoans->where('status', 'dipinjam')->latest()->limit(10)->get();

        $stats = [
            'total_books' => null,
            'books_loaned' => null,
            'total_loans' => $userLoans->count(),
            'active_loans' => $userLoans->where('status', 'dipinjam')->count(),
            'overdue_count' => $userLoans->where('return_date', '<', now())
                ->where('status', '!=', 'dikembalikan')->count(),
            'due_today_count' => $userLoans->whereDate('return_date', now())
                ->where('status', 'dipinjam')->count(),
        ];

        return view('dashboardsiswa', compact('stats', 'topBooks', 'activeLoans', 'isAdmin'));
    }
}

