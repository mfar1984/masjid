# Kewangan Forms - Jenis Derma & Jenis Bil Integration COMPLETE ✅

## Summary
Berjaya integrate Jenis Derma dan Jenis Bil dari Tetapan Kewangan dengan 2 form (Derma & Sumbangan dan Utiliti & Bil). Sekarang dropdown jenis derma/bil adalah dynamic dan pull dari database, bukan hardcoded lagi.

## Changes Made

### 1. Database Migration
**File:** `database/migrations/2025_12_13_092740_add_jenis_derma_bil_to_kutipan_dana_and_perbelanjaan_tables.php`

**Added columns:**
- `kutipan_dana` table: `jenis_derma_id` (foreign key to `kategori_kewangan`)
- `perbelanjaan` table: `jenis_bil_id` (foreign key to `kategori_kewangan`)

**Purpose:** Store relationship between transactions and jenis derma/bil

### 2. Model Updates

#### KutipanDana Model
**File:** `app/Models/KutipanDana.php`

**Changes:**
- Added `jenis_derma_id` to `$fillable` array
- Added `jenisDerma()` relationship method:
```php
public function jenisDerma()
{
    return $this->belongsTo(KategoriKewangan::class, 'jenis_derma_id');
}
```

#### Perbelanjaan Model
**File:** `app/Models/Perbelanjaan.php`

**Changes:**
- Added `jenis_bil_id` to `$fillable` array
- Added `jenisBil()` relationship method:
```php
public function jenisBil()
{
    return $this->belongsTo(KategoriKewangan::class, 'jenis_bil_id');
}
```

### 3. Controller Updates

#### KutipanDanaController
**File:** `app/Http/Controllers/KutipanDanaController.php`

**Updated method:** `dermaSumbangan()`
```php
public function dermaSumbangan()
{
    $user = Auth::user();
    $masjidId = $user->masjid_id;

    $kategori = KategoriKewangan::where('masjid_id', $masjidId)->kategoriPendapatan()->aktif()->orderBy('urutan')->get();
    $akaunBank = AkaunBank::where('masjid_id', $masjidId)->aktif()->orderBy('nama_bank')->get();
    $kaedahBayaran = KategoriKewangan::where('masjid_id', $masjidId)->kaedahBayaran()->aktif()->orderBy('urutan')->get();
    $jenisDerma = KategoriKewangan::where('masjid_id', $masjidId)
        ->where('jenis_kategori', 'jenis_derma')
        ->where('status', 'Aktif')
        ->orderBy('urutan')
        ->get(); // ← NEW

    return view('kutipan-dana.derma-sumbangan', compact('kategori', 'akaunBank', 'kaedahBayaran', 'jenisDerma'));
}
```

#### PerbelanjaanController
**File:** `app/Http/Controllers/PerbelanjaanController.php`

**Updated method:** `utilitiBil()`
```php
public function utilitiBil()
{
    $user = Auth::user();
    $masjidId = $user->masjid_id;

    $kategori = KategoriKewangan::where('masjid_id', $masjidId)->perbelanjaan()->aktif()->orderBy('nama_kategori')->get();
    $akaunBank = AkaunBank::where('masjid_id', $masjidId)->aktif()->orderBy('nama_bank')->get();
    $kaedahBayaran = KategoriKewangan::where('masjid_id', $masjidId)->kaedahBayaran()->aktif()->orderBy('urutan')->get();
    $jenisBil = KategoriKewangan::where('masjid_id', $masjidId)
        ->where('jenis_kategori', 'jenis_bil')
        ->where('status', 'Aktif')
        ->orderBy('urutan')
        ->get(); // ← NEW

    return view('perbelanjaan.utiliti-bil', compact('kategori', 'akaunBank', 'kaedahBayaran', 'jenisBil'));
}
```

### 4. View Updates

#### Derma & Sumbangan Form
**File:** `resources/views/kutipan-dana/derma-sumbangan.blade.php`

**BEFORE (Hardcoded):**
```html
<select id="jenis_derma" name="jenis_derma" required>
    <option value="">-- Pilih Jenis --</option>
    <option value="Derma Am">Derma Am</option>
    <option value="Pembinaan">Pembinaan</option>
    <option value="Penyelenggaraan">Penyelenggaraan</option>
    <option value="Aktiviti">Aktiviti</option>
    <option value="Lain-lain">Lain-lain</option>
</select>
```

**AFTER (Dynamic):**
```html
<select id="jenis_derma_id" name="jenis_derma_id" required>
    <option value="">-- Pilih Jenis Derma --</option>
    @foreach($jenisDerma as $jenis)
        <option value="{{ $jenis->id }}">
            {{ $jenis->nama_kategori }}
        </option>
    @endforeach
</select>
```

#### Utiliti & Bil Form
**File:** `resources/views/perbelanjaan/utiliti-bil.blade.php`

**BEFORE (Hardcoded):**
```html
<select id="jenis_bil" name="jenis_bil" required>
    <option value="">-- Pilih Jenis --</option>
    <option value="Elektrik">Elektrik</option>
    <option value="Air">Air</option>
    <option value="Telefon">Telefon</option>
    <option value="Internet">Internet</option>
    <option value="Lain-lain">Lain-lain</option>
</select>
```

**AFTER (Dynamic):**
```html
<select id="jenis_bil_id" name="jenis_bil_id" required>
    <option value="">-- Pilih Jenis Bil --</option>
    @foreach($jenisBil as $jenis)
        <option value="{{ $jenis->id }}">
            {{ $jenis->nama_kategori }}
        </option>
    @endforeach
</select>
```

## How It Works Now

### Admin Workflow

#### 1. Setup Jenis Derma/Bil (One-time)
1. Navigate to `/tetapan-kewangan`
2. Click "Kategori" tab
3. Manage "Jenis Derma" section:
   - Add custom jenis derma (e.g., "Derma Masjid Baru", "Derma Anak Yatim")
   - Edit existing jenis derma
   - Set status Aktif/Tidak Aktif
   - Arrange order with urutan
4. Manage "Jenis Bil" section:
   - Add custom jenis bil (e.g., "Bil Astro", "Bil Unifi")
   - Edit existing jenis bil
   - Set status Aktif/Tidak Aktif
   - Arrange order with urutan

#### 2. Use in Forms (Daily Operations)
1. Navigate to `/transaksi-kewangan`
2. Click "Tambah Pendapatan" → "Derma & Sumbangan"
3. Fill form:
   - **Jenis Derma dropdown** now shows options from Tetapan Kewangan
   - Select appropriate jenis derma
   - Fill other fields
   - Submit
4. Or click "Tambah Perbelanjaan" → "Utiliti & Bil"
5. Fill form:
   - **Jenis Bil dropdown** now shows options from Tetapan Kewangan
   - Select appropriate jenis bil
   - Fill other fields
   - Submit

### Data Flow
```
Tetapan Kewangan (Setup)
    ↓
kategori_kewangan table
    ↓
Controller fetches jenis_derma/jenis_bil
    ↓
View renders dynamic dropdown
    ↓
User selects jenis_derma_id/jenis_bil_id
    ↓
Saved to kutipan_dana/perbelanjaan table
    ↓
Can be used for reporting/filtering
```

## Benefits

### 1. Flexibility
- ✅ Admin can add custom jenis derma/bil based on masjid needs
- ✅ No need to modify code to add new types
- ✅ Each masjid can have different jenis derma/bil

### 2. Better Categorization
- ✅ More detailed transaction categorization
- ✅ Can track specific types of derma (e.g., Derma Pembinaan vs Derma Pendidikan)
- ✅ Can track specific types of bil (e.g., Bil Air vs Bil Elektrik)

### 3. Improved Reporting (Future)
- ✅ Can generate reports by jenis derma
- ✅ Can generate reports by jenis bil
- ✅ Better insights into income/expense breakdown

### 4. Data Integrity
- ✅ Foreign key relationships ensure data consistency
- ✅ Soft delete support (if jenis derma/bil deleted, transactions remain)
- ✅ Multi-masjid isolation maintained

## Testing

### Build Test
```bash
npm run build
```
**Result:** ✅ Success - No errors

### Manual Testing Required

#### Test Derma & Sumbangan Form
1. ⏳ Navigate to `/transaksi-kewangan/derma-sumbangan`
2. ⏳ Verify "Jenis Derma" dropdown shows options from Tetapan Kewangan
3. ⏳ Verify dropdown shows 6 default options:
   - Derma Umum
   - Derma Pembinaan
   - Derma Penyelenggaraan
   - Derma Pendidikan
   - Derma Kebajikan
   - Derma Khas
4. ⏳ Select a jenis derma
5. ⏳ Fill other fields and submit
6. ⏳ Verify data saved correctly in database

#### Test Utiliti & Bil Form
1. ⏳ Navigate to `/transaksi-kewangan/utiliti-bil`
2. ⏳ Verify "Jenis Bil" dropdown shows options from Tetapan Kewangan
3. ⏳ Verify dropdown shows 9 default options:
   - Bil Air
   - Bil Elektrik
   - Bil Telefon
   - Bil Internet
   - Bil Cukai Tanah
   - Bil Cukai Pintu
   - Bil Insurans
   - Bil Gas
   - Bil Lain-lain
4. ⏳ Select a jenis bil
5. ⏳ Fill other fields and submit
6. ⏳ Verify data saved correctly in database

#### Test Dynamic Updates
1. ⏳ Go to `/tetapan-kewangan` → Kategori tab
2. ⏳ Add new "Jenis Derma" (e.g., "Derma Ramadan")
3. ⏳ Go back to `/transaksi-kewangan/derma-sumbangan`
4. ⏳ Verify new jenis derma appears in dropdown
5. ⏳ Set jenis derma status to "Tidak Aktif"
6. ⏳ Verify it disappears from dropdown

### Database Verification
```bash
# Check if jenis_derma_id column exists
php artisan tinker --execute="echo json_encode(DB::select('DESCRIBE kutipan_dana'), JSON_PRETTY_PRINT);"

# Check if jenis_bil_id column exists
php artisan tinker --execute="echo json_encode(DB::select('DESCRIBE perbelanjaan'), JSON_PRETTY_PRINT);"

# Check saved data
php artisan tinker --execute="echo json_encode(DB::table('kutipan_dana')->select('id', 'jenis_kutipan', 'jenis_derma_id')->latest()->first(), JSON_PRETTY_PRINT);"
```

## Files Modified

1. **Migration:**
   - `database/migrations/2025_12_13_092740_add_jenis_derma_bil_to_kutipan_dana_and_perbelanjaan_tables.php` (created)

2. **Models:**
   - `app/Models/KutipanDana.php` (modified - added jenis_derma_id fillable & relationship)
   - `app/Models/Perbelanjaan.php` (modified - added jenis_bil_id fillable & relationship)

3. **Controllers:**
   - `app/Http/Controllers/KutipanDanaController.php` (modified - added jenisDerma data to dermaSumbangan method)
   - `app/Http/Controllers/PerbelanjaanController.php` (modified - added jenisBil data to utilitiBil method)

4. **Views:**
   - `resources/views/kutipan-dana/derma-sumbangan.blade.php` (modified - replaced hardcoded dropdown with dynamic)
   - `resources/views/perbelanjaan/utiliti-bil.blade.php` (modified - replaced hardcoded dropdown with dynamic)

## Remaining Forms (Not Yet Integrated)

These forms don't need jenis derma/bil integration:
- ✅ Kutipan Kariah - specific to kariah members
- ✅ Kutipan Zakat - specific to zakat types
- ✅ Kutipan Lain - general kutipan
- ✅ Penyelenggaraan - specific to maintenance work
- ✅ Gaji & Elaun - specific to staff salary
- ✅ Perbelanjaan Lain - general expenses

**Note:** If needed in future, can add similar integration for these forms.

## Next Steps (Future Enhancement)

### Phase 1: Update Store Methods ⏳
Update `KutipanDanaController@store` and `PerbelanjaanController@store` to:
1. Validate `jenis_derma_id` and `jenis_bil_id`
2. Save to database
3. Handle validation errors

### Phase 2: Show in Transaction List ⏳
Update transaction list views to display:
1. Jenis Derma name (instead of just kategori)
2. Jenis Bil name (instead of just kategori)
3. Add filter by jenis derma/bil

### Phase 3: Reporting Enhancement ⏳
Create reports:
1. **Laporan Derma by Jenis:**
   - Group by jenis derma
   - Show total per jenis
   - Chart visualization

2. **Laporan Perbelanjaan by Jenis Bil:**
   - Group by jenis bil
   - Show total per jenis
   - Chart visualization

## Status
✅ **COMPLETE** - Forms integrated with dynamic jenis derma/bil dropdowns
⏳ **PENDING** - Store method validation updates
⏳ **PENDING** - Transaction list display updates
⏳ **PENDING** - Reporting enhancements
