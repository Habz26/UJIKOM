<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLoanRequest;
use App\Models\Book;
use App\Models\Loan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LoanController extends Controller
{
    /**
     * Menampilkan form peminjaman baru dengan daftar buku tersedia.
     * Admin melihat semua buku, siswa hanya buku yang belum dipinjamnya.
     *
     * @return View Halaman form create peminjaman dengan daftar buku dan status admin
     */
    public function create(): View
    {
        $user = auth()->user();
        $isAdmin = $user->role === 'admin';

        $booksQuery = Book::where('stock', '>', 0);
        if (!$isAdmin) {
            // Siswa only see available books not already borrowed by them
            $booksQuery->whereDoesntHave('loans', function ($q) use ($user) {
                $q->where('user_id', $user->id)->where('status', 'dipinjam');
            });
        }

        $books = $booksQuery->withTrashed(false)->get();
        return view('loans.create', compact('books', 'isAdmin'));
    }

    /**
     * Menyimpan peminjaman baru dengan validasi stok dan duplikasi (khusus siswa).
     * Mengurangi stok buku dan membuat record Loan dalam transaksi DB.
     *
     * @param StoreLoanRequest $request Data validasi peminjaman (book_id, quantity, return_date, dll)
     * @return RedirectResponse Redirect ke active loans dengan pesan sukses atau error validasi
     */
    public function store(StoreLoanRequest $request): RedirectResponse
    {
        $userId = auth()->id();
        $bookId = $request->input('book_id');
        $quantity = $request->input('quantity', 1);

        if (!$bookId) {
            return back()
                ->withErrors(['book_id' => 'Buku harus dipilih'])
                ->withInput();
        }

        DB::transaction(function () use ($userId, $bookId, $quantity, $request) {
            $book = Book::findOrFail($bookId);
            if ($book->stock < $quantity) {
                throw new \Exception('Stok buku tidak mencukupi. Stok tersedia: ' . $book->stock);
            }

            if (auth()->user()->role === 'siswa') {
                $existing = Loan::where('user_id', $userId)->where('book_id', $bookId)->where('status', 'dipinjam')->exists();
                if ($existing) {
                    throw new \Exception('Anda sudah meminjam buku ini!');
                }
            }

            $loanData = $request->validated();
            $loanData['user_id'] = $userId;
            $loanData['borrower_name'] = $loanData['borrower_name'] ?? auth()->user()->name;
            $loanData['quantity'] = $quantity;
            $loanData['borrowed_quantity'] = $quantity;
            $loanData['returned_quantity'] = 0;

            $loan = Loan::create($loanData);
            $book->decrement('stock', $quantity);
        });

        return redirect()
            ->route('loans.active')
            ->with('success', "Peminjaman {$quantity} eksemplar berhasil! Jatuh tempo: " . date('d/m/Y', strtotime($request->return_date)));
    }

    /**
     * Siswa mengajukan pengembalian (set status pending verification, belum restore stok).
     * Hanya untuk non-admin, update pending_return_quantity.
     *
     * @param Request $request Parameter 'return_quantity' jumlah yang dikembalikan
     * @param Loan $loan Model Loan peminjaman yang diajukan pengembalian
     * @return RedirectResponse Kembali dengan pesan sukses atau error validasi
     */
    public function return(Request $request, Loan $loan): RedirectResponse
    {
        $isAdmin = auth()->user()->role === 'admin';
        if ($isAdmin) {
            return back()->with('error', 'Admin tidak boleh menggunakan tombol ini. Gunakan "Verifikasi".');
        }

        $request->validate([
            'return_quantity' => 'required|integer|min:1|max:' . $loan->quantity,
        ]);

        if ($loan->quantity <= 0) {
            return back()->with('error', 'Peminjaman sudah sepenuhnya dikembalikan.');
        }

        if ($loan->status !== 'dipinjam') {
            return back()->with('error', 'Hanya peminjaman aktif yang bisa diajukan pengembalian.');
        }

        DB::transaction(function () use ($loan, $request) {
            $pendingQty = $request->return_quantity;
            // Don't touch quantity/returned_quantity - keep original borrowed for active view
            $loan->pending_return_quantity = ($loan->pending_return_quantity ?? 0) + $pendingQty;
            $loan->returned_at = now();
            $loan->status = $loan->quantity > $pendingQty ? 'dipinjam' : 'pending_return';
            $loan->verification_status = 'pending';
            // NO STOCK RESTORE - wait for admin
            $loan->save();
        });

        $qtyStr = $request->return_quantity > 1 ? $request->return_quantity . ' eksemplar' : 'buku';
        return back()->with('success', $qtyStr . ' berhasil diajukan untuk verifikasi admin!');
    }

    /**
     * Menampilkan daftar peminjaman aktif (dipinjam/pending_return) dengan filter terlambat.
     * Admin melihat semua, siswa hanya miliknya.
     *
     * @param Request $request Parameter filter 'status=terlambat' opsional
     * @return View Halaman active loans dengan paginasi dan count terlambat
     */
    public function active(Request $request): View
    {
        $user = auth()->user();
        $isAdmin = $user->role === 'admin';

        $query = Loan::with(['book', 'user'])->whereIn('status', ['dipinjam', 'pending_return']);

        if (!$isAdmin) {
            $query->where('user_id', $user->id);
        }

        $query
            ->when($request->filled('status') && $request->status === 'terlambat', function ($q) {
                $q->where('return_date', '<', now());
            })
            ->latest();

        $loans = $query->paginate(10);

        $overdueLoans = $isAdmin
            ? $query
                ->cloneWithout(['orders', 'limit'])
                ->where('return_date', '<', now())
                ->count()
            : $loans->where('return_date', '<', now())->count();

        return view('loans.active', compact('loans', 'overdueLoans', 'isAdmin'));
    }

    /**
     * Menampilkan riwayat peminjaman selesai (dikembalikan atau returned_quantity > 0).
     *
     * @param Request $request Tidak digunakan
     * @return View Halaman history dengan paginasi loans selesai
     */
    public function history(Request $request): View
    {
        $user = auth()->user();
        $isAdmin = $user->role === 'admin';

        $query = Loan::with(['book', 'user'])->where(function ($q) {
            $q->where('status', 'dikembalikan')->orWhereRaw('returned_quantity > 0');
        });

        if (!$isAdmin) {
            $query->where('user_id', $user->id);
        }

        $loans = $query->latest()->paginate(10);

        return view('loans.history', compact('loans', 'isAdmin'));
    }

    /**
     * Admin memverifikasi pengembalian: hitung denda keterlambatan/rusak, restore stok, update status.
     *
     * @param Request $request 'return_quantity', 'condition' (baik/rusak), 'damage_note' opsional
     * @param Loan $loan Model Loan dalam status pending
     * @return RedirectResponse Kembali dengan pesan sukses/error dan info denda baru
     */
    public function verifyReturn(Request $request, Loan $loan): RedirectResponse
    {
        $isAdmin = auth()->user()->role === 'admin';
        if (!$isAdmin) {
            return back()->with('error', 'Hanya admin yang bisa verifikasi.');
        }

        if ($loan->verification_status !== 'pending') {
            return back()->with('error', 'Pengembalian tidak dalam status pending.');
        }

        $request->validate([
            'return_quantity' => ['required', 'integer', 'min:1', 'max:' . ($loan->pending_return_quantity ?? 0)],
            'condition' => 'required|in:baik,rusak',
            'damage_note' => 'nullable|string|max:500',
        ]);

        // Update pending_return_quantity after verification
        $loan->pending_return_quantity -= $request->return_quantity;

        $verifyQty = $request->return_quantity;
        $dueDate = $loan->return_date ?? $loan->loan_date->copy()->addDays(7);
        $lateDays = now()->gt($dueDate) ? floor(now()->diffInDays($dueDate)) : 0;
            
        // Late fine: Rp 1000/day per quantity
        $lateFine = $lateDays * 1000 * $verifyQty;
            
        // Damage fine: Rp 100000 if rusak per quantity
        $damageFine = $request->condition === 'rusak' ? 100000 * $verifyQty : 0;
            
        $totalNewFine = $lateFine + $damageFine;
        
        DB::transaction(function () use ($loan, $request, $verifyQty, $totalNewFine) {
            $loan->fine += $totalNewFine;
            $loan->fine_paid = 0; // Reset on new fine
            $loan->fine_status = 'unpaid';
            $loan->condition = $request->condition;
            $loan->damage_note = $request->damage_note;
            
            // Always restore stock for verified quantity
            $loan->book->increment('stock', $verifyQty);
            
            $loan->returned_quantity += $verifyQty;
            $loan->quantity -= $verifyQty;
            
            if ($loan->quantity <= 0) {
                $loan->status = 'dikembalikan';
            }
            $loan->verification_status = 'verified_returned';

            $loan->save();
        });

        $qtyStr = $verifyQty > 1 ? $verifyQty . ' eksemplar' : 'buku';
        $fineNote = $totalNewFine > 0 ? ' (Denda baru: Rp ' . number_format($totalNewFine) . ')' : '';
        return back()->with('success', 'Verifikasi ' . $qtyStr . ' selesai!' . $fineNote);
    }

    /**
     * Admin mencatat pembayaran denda (parsial atau penuh) untuk loan selesai.
     * Update fine_paid, fine_status (paid/partial), log aktivitas.
     *
     * @param Request $request Parameter 'amount' nilai pembayaran (max outstanding_fine)
     * @param Loan $loan Model Loan status 'dikembalikan' dengan denda outstanding > 0
     * @return RedirectResponse Kembali dengan pesan sukses dan sisa denda
     */
    public function payFine(Request $request, Loan $loan): RedirectResponse
    {
        $isAdmin = auth()->user()->role === 'admin';
        if (!$isAdmin) {
            return back()->with('error', 'Hanya admin yang boleh.');
        }

        if ($loan->status !== 'dikembalikan') {
            return back()->with('error', 'Hanya loan dikembalikan yang bisa dibayar dendanya.');
        }

        $outstanding = $loan->outstanding_fine;
        if ($outstanding <= 0) {
            return back()->with('error', 'Tidak ada denda tersisa.');
        }

        $request->validate([
            'amount' => 'required|numeric|min:0.01|max:' . $outstanding,
        ]);

        $amount = (float) $request->amount;
        DB::transaction(function () use ($loan, $amount) {
            $loan->fine_paid += $amount;
            $loan->fine_paid_at = now();
            
            if ($loan->fine_paid >= $loan->fine) {
                $loan->fine_status = 'paid';
            } elseif ($loan->fine_paid > 0) {
                $loan->fine_status = 'partial';
            }
            
            $loan->save();
            
            Log::info('Fine payment recorded', [
                'loan_id' => $loan->id,
                'amount' => $amount,
                'outstanding' => $loan->outstanding_fine,
                'admin_id' => auth()->id(),
            ]);
        });

        return back()->with('success', 'Denda Rp ' . number_format($amount) . ' berhasil dicatat. Sisa: Rp ' . number_format($loan->fresh()->outstanding_fine));
    }
}

