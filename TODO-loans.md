# TODO: Display Book Title in Loan Input Field

## Plan Status: Completed

### Steps:
- [x] 1. Create/update TODO file with plan steps ✅
- [x] 2. Edit resources/views/loans/create.blade.php:
  - Add hidden input for book_id
  - Change datalist options: value=title, data-id=id
  - Update JS to set hidden value on selection and filter properly ✅
- [ ] 3. Test form: display shows title, submits correct ID, stock updates
- [x] 4. Complete task ✅

Files updated. Test manually with `php artisan serve` and visit /loans/create.
