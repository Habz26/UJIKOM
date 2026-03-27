# Style Profile Forms to Match Books

Status: [x] Complete

## Changes:
1. [x] profile/edit.blade.php: library layout + Bootstrap tabs with icons (Info, Password, Role, Delete)
2. [x] update-profile-information-form.blade.php: Bootstrap forms, localized labels, warning box for unverified email
3. [x] update-password-form.blade.php: Bootstrap lg inputs/buttons, error bags, localized
4. [x] update-role-form.blade.php: form-select-lg, validation errors
5. [x] delete-user-form.blade.php: Bootstrap modal with danger theme, password confirm

- All Tailwind/x-components replaced with Bootstrap equivalents (form-control-lg, btn-primary-lg, invalid-feedback).
- Preserved Alpine toasts, verification logic, @error bags.
- Localized Indonesian labels/buttons matching app.
- Matches books styling: fw-bold fs-5 labels, px-5 lg buttons, icons.

**Test:** Visit `/profile/edit` - tabs navigate, forms submit with validation/modals.
