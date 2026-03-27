# Loans History Return Logic

Status: [x] Complete

## Implemented:
1. [x] Added `PATCH /loans/{loan}/return` route.
2. [x] LoanController::return(): Check status 'dipinjam', set return_date=now(), status='dikembalikan', book stock +1.
3. [x] history.blade.php: 
   - "Aksi" column.
   - If 'dipinjam': "Kembalikan" button (PATCH form, confirm).
   - Status: Terlambat (red) if 7 days overdue.
   - Dikembalikan (green)/Selesai ✓.
   - colspan fixed to 6.
4. [x] route:clear / view:clear.

**What happens on due date**: Manual button anytime for return. Overdue badge shows after 7 days (no auto change).

Test: Create loan (/loans/create), history → Kembalikan button works, stock restores.
