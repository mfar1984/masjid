# INSTRUCTIONS FOR NEXT SESSION

**Module**: Operasi > Fasiliti & Tempahan
**Current Progress**: Phase 1 - 50% Complete
**Next Task**: Implement Controllers

---

## 🎯 IMMEDIATE NEXT STEPS

### 1. Read These Files First
```
OPERASI_FASILITI_TEMPAHAN_COMPLETE_DESIGN.md  (Main design - 1346 lines)
OPERASI_FASILITI_PHASE1_PROGRESS.md           (Current progress)
CONTEXT_TRANSFER_OPERASI_FASILITI.md          (Context transfer)
```

### 2. Verify What's Done
```bash
# Check migrations
php artisan migrate:status

# Check if tables exist
php artisan tinker
>>> \DB::table('senarai_fasiliti')->count()
>>> \DB::table('tempahan_fasiliti')->count()
>>> \DB::table('pembayaran_sewa')->count()
```

### 3. Start Implementation
**Priority Order:**
1. Implement `SenariFasilitiController` (basic CRUD)
2. Implement `TempahanFasilitiController` (CRUD + workflow)
3. Implement `PembayaranSewaController` (basic CRUD)
4. Implement `LaporanTempahanController` (reports)
5. Setup routes with permissions
6. Test everything

---

## 📝 CONTROLLER IMPLEMENTATION GUIDE

### Pattern to Follow
Look at these existing controllers for reference:
- `app/Http/Controllers/SenariAsetController.php` (for basic CRUD)
- `app/Http/Controllers/PermohonanBantuanController.php` (for workflow)
- `app/Http/Controllers/LaporanKebajikanController.php` (for reports)

### Key Requirements
1. **Multi-Masjid Isolation** - Check user role, filter by masjid_id
2. **Auto-Generate Numbers** - Use model methods (generateKodFasiliti, etc)
3. **File Uploads** - Handle optional file uploads (gambar, dokumen)
4. **Workflow Actions** - semak, lulus, tolak, batal, selesai (for Tempahan)
5. **Permissions** - Use middleware: `permission:operasi,read|create|update|delete|approve`

---

## 🔗 ROUTES TO ADD

```php
// Senarai Fasiliti
Route::resource('senarai-fasiliti', SenariFasilitiController::class)
    ->middleware(['auth', 'verified', 'permission:operasi,read']);

// Tempahan Fasiliti
Route::resource('tempahan-fasiliti', TempahanFasilitiController::class)
    ->middleware(['auth', 'verified', 'permission:operasi,read']);

// Workflow actions
Route::post('tempahan-fasiliti/{id}/semak', [TempahanFasilitiController::class, 'semak'])
    ->name('tempahan-fasiliti.semak');
Route::post('tempahan-fasiliti/{id}/lulus', [TempahanFasilitiController::class, 'lulus'])
    ->name('tempahan-fasiliti.lulus');
// ... (tolak, batal, selesai)

// Pembayaran Sewa
Route::resource('pembayaran-sewa', PembayaranSewaController::class)
    ->middleware(['auth', 'verified', 'permission:operasi,read']);

// Laporan
Route::get('laporan-tempahan', [LaporanTempahanController::class, 'index'])
    ->name('laporan-tempahan.index');
```

---

## ⚠️ IMPORTANT NOTES

1. **Follow Masjid Project Rules** (from masjid-rule.md)
2. **Font**: Poppins, 10px-14px only
3. **Border Radius**: 4px-8px, don't overuse
4. **Multi-Masjid**: Always check user role and filter by masjid_id
5. **File Uploads**: All optional, max 5MB
6. **Testing**: Use `npm run build` to check for errors

---

## 📊 ESTIMATED TIME

- Controller Implementation: 4 hours
- Routes Setup: 1 hour
- Testing: 1 hour
- **Total Phase 1**: 6 hours remaining

After Phase 1 complete, move to Phase 2 (Views) - estimated 8 hours

---

**Good luck with the implementation!** 🚀

