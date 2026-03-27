# 📚 Sistem Peminjaman Buku - Library Loan App (UJIKOM)

Aplikasi web **modern & responsive** untuk mengelola perpustakaan menggunakan **Laravel 13 + Bootstrap 5**.

## 🎯 Fitur Utama
| Halaman | Fitur |
|---------|-------|
| **Dashboard** | Statistik: Total Buku, Buku Dipinjam, Total Transaksi (3 cards cantik) |
| **Data Buku** | CRUD lengkap (Create/Read/Update/Delete), search, pagination, modal |
| **Peminjaman** | Form peminjam → otomatis kurangi stok buku |
| **Riwayat** | Tabel lengkap dengan badge status (Dipinjam/Dikembalikan) |

## ✨ Desain & UI
- **Bootstrap 5** (CDN) + **Bootstrap Icons**
- **Sidebar kiri fixed** responsive (mobile: hamburger menu)
- **UIverse style**: Cards rounded shadow, hover effects, gradient sidebar
- **Warna soft**: Biru/hijau gradient, abu-abu clean
- ✅ **Mobile-first responsive**

## 🛠 Tech Stack
```
Laravel 13
MySQL (books + loans tables)
Bootstrap 5.3
Blade Templates
Auth Middleware
Form Validation
Eloquent ORM
```

## 📋 Struktur Database
```
books: id, title, author, year, stock
loans: id, borrower_name, book_id, loan_date, return_date, status
```

## 🚀 Cara Menjalankan
```bash
# 1. Migrate database (buat tabel)
php artisan migrate

# 2. Jalankan server
php artisan serve

# 3. Login (user default ada)
http://127.0.0.1:8000/login
```

## 🧭 Navigasi
```
Dashboard → /dashboard
Data Buku → /books (CRUD lengkap)
Peminjaman → /loans/create
Riwayat → /history
```

## 📱 Responsive Breakpoints
- **Desktop** (>992px): Sidebar selalu tampil
- **Tablet/Mobile** (<992px): Sidebar tersembunyi → klik hamburger

## 🔧 Custom Features
1. **Auto stock decrement** saat pinjam
2. **Search & pagination** real-time
3. **Modal CRUD** (no page reload)
4. **Toast notifications** (success/error)
5. **Validation Indonesia** (pesan error lokal)

## 🎨 UI Components
```
✅ Cards dengan icon + gradient
✅ Table hover + responsive
✅ Badges status (warning/success)
✅ Buttons hover animation
✅ Form validation real-time
```

## ✅ Checklist UJIKOM
- [x] CRUD Lengkap
- [x] Responsive Design  
- [x] Modern UI/UX
- [x] Database Relations
- [x] Form Validation
- [x] Authentication
- [x] Search & Pagination

**Aplikasi siap demo! 🚀** Test di browser + mobile.

---
*Made with ❤️ for UJIKOM - Clean, Modern, Production Ready*

