# PERMOHONAN ZAKAT - DESIGN FIX SUMMARY

## 📅 Date: 12 December 2025

## ❌ PROBLEM
User reported: "kenapa permohonan-zakat tidak follow design seperti kariah? agak pelik. anda buat ikut sesedap rasa?"

The original implementation did NOT follow the Kariah/AJK design pattern:
- ❌ Wrong table styling (bg-gray-50 instead of bg-blue-100 header)
- ❌ No mobile card view
- ❌ No statistics cards
- ❌ Wrong filter layout (not using components)
- ❌ No export functionality
- ❌ Wrong action icon sizes and colors
- ❌ No delete modal with security code
- ❌ Missing multi-masjid data isolation in stats

## ✅ SOLUTION
Completely redesigned to follow **EXACT** Kariah/AJK pattern.

---

## 🔧 CHANGES MADE

### 1. VIEW: `resources/views/permohonan-zakat/index.blade.php`

#### Header Section
```blade
<!-- BEFORE -->
<a href="{{ route('permohonan-zakat.create') }}" class="inline-flex items-center justify-center h-[32px] px-4 py-1 bg-blue-600 text-white text-xs rounded hover:bg-blue-700">

<!-- AFTER -->
<a href="{{ route('permohonan-zakat.create') }}" class="inline-flex items-center justify-center h-[32px] px-4 py-1 bg-blue-600 text-white text-xs rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2" style="flex-shrink: 0 !important;">
```
- Added focus states
- Added export button (green)
- Added flex-shrink: 0 for consistency

#### Statistics Cards
```blade
<!-- ADDED -->
<x-statistics-grid :stats="$stats" />
```
- 5 cards: Jumlah Permohonan, Menunggu, Dalam Semakan, Diluluskan, Ditolak
- Icons: description, pending, rate_review, check_circle, cancel
- Colors: blue, orange, blue, green, red

#### Filter Layout
```blade
<!-- BEFORE -->
<div class="flex flex-col md:flex-row gap-3">
    <input type="text" name="search" ... class="flex-1 px-3 py-2 border border-gray-300 rounded-sm text-xs">
    <select name="status" class="px-3 py-2 border border-gray-300 rounded-sm text-xs">
    ...
</div>

<!-- AFTER -->
<div class="flex flex-col md:flex-row gap-3 items-stretch md:items-center">
    <x-search-input name="search" :value="request('search')" placeholder="Cari no permohonan, nama asnaf, no IC..." />
    <div class="flex gap-2">
        <x-filter-dropdown name="status" :options="[...]" :selected="request('status')" placeholder="Semua Status" />
        <x-filter-dropdown name="jenis_bantuan" :options="[...]" :selected="request('jenis_bantuan')" placeholder="Semua Jenis" />
    </div>
    <div class="flex gap-2">
        <x-action-button type="submit" icon="search" color="blue">Cari</x-action-button>
        <x-action-button type="button" icon="refresh" color="red" onclick="...">Reset</x-action-button>
    </div>
</div>
```
- Using components for consistency
- All fields in 1 row (flexbox)
- No labels, just dropdowns/inputs + buttons

#### Desktop Table
```blade
<!-- BEFORE -->
<div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">...</th>

<!-- AFTER -->
<div class="hidden md:block overflow-x-auto bg-gray-50 rounded-xs border border-gray-200">
    <table class="min-w-full text-left text-sm">
        <thead class="bg-blue-100 text-gray-600">
            <tr>
                <th class="px-4 py-2 table-header">...</th>
```
- Changed header from bg-gray-50 to bg-blue-100
- Changed row hover from hover:bg-gray-50 to hover:bg-white
- Added table-header, table-data, table-data-important classes
- Hidden on mobile (hidden md:block)

#### Action Icons
```blade
<!-- BEFORE -->
<a href="..." class="text-blue-600 hover:text-blue-800">
    <span class="material-icons" style="font-size: 16px !important;">visibility</span>
</a>

<!-- AFTER -->
<td class="px-4 py-2 table-data text-center space-x-1">
    <a href="..." class="text-gray-700 hover:text-gray-900 action-icon" title="Lihat">
        <span class="material-icons text-[8px]">visibility</span>
    </a>
    <a href="..." class="text-blue-600 hover:text-blue-800 action-icon" title="Edit">
        <span class="material-icons text-[8px]">edit</span>
    </a>
    <button type="button" onclick="showDeleteModal(...)" class="text-red-600 hover:text-red-800 action-icon" title="Padam">
        <span class="material-icons text-[8px]">delete</span>
    </button>
</td>
```
- Icon size: 16px → text-[8px]
- Colors: gray (view), blue (edit), red (delete)
- Added action-icon class
- Added title tooltips
- Using space-x-1 for spacing

#### Mobile Card View
```blade
<!-- ADDED -->
<div class="md:hidden space-y-3">
    @forelse($permohonan as $item)
    <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
        <div class="flex items-center justify-between mb-3">
            <div class="flex-1">
                <div class="flex items-center mb-1">
                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-2">
                        <span class="text-xs font-medium text-blue-600">{{ strtoupper(substr($item->no_permohonan, 0, 2)) }}</span>
                    </div>
                    <h3 class="mobile-title text-gray-900">{{ $item->no_permohonan }}</h3>
                </div>
                <p class="mobile-subtitle text-gray-500">{{ $item->asnaf->nama }}</p>
            </div>
            <div class="flex flex-col gap-2">
                <!-- Action icons (20px for mobile) -->
            </div>
        </div>
        <div class="grid grid-cols-2 gap-4 text-xs">
            <!-- Details -->
        </div>
    </div>
    @empty
    ...
    @endforelse
</div>
```
- Card layout with avatar circle
- 2-column grid for details
- Vertical action icons on right
- mobile-title, mobile-subtitle, mobile-label, mobile-data classes

#### Delete Modal
```blade
<!-- ADDED -->
<x-delete-modal title="Padam Permohonan Zakat" message="Adakah anda pasti mahu memadamkan permohonan" />

<script>
    function showDeleteModal(recordId, recordName) {
        // Generate security code
        // Validate input
        // Show modal
    }
</script>
```
- Security code verification
- Form action: `/permohonan-zakat/{id}`
- Escape key to close

#### Pagination
```blade
<!-- BEFORE -->
<div class="mt-4">
    {{ $permohonan->links() }}
</div>

<!-- AFTER -->
@if($permohonan->hasPages())
<div class="mt-4 flex items-center justify-between">
    <div class="text-xs text-gray-500">
        Menunjukkan {{ $permohonan->firstItem() }} hingga {{ $permohonan->lastItem() }} daripada {{ $permohonan->total() }} rekod
    </div>
    <div class="flex space-x-1">
        {{ $permohonan->appends(request()->query())->links('pagination::simple-tailwind') }}
    </div>
</div>
@endif
```
- Added record count display
- Only show if has pages
- Preserve query parameters

---

### 2. CONTROLLER: `app/Http/Controllers/PermohonanZakatController.php`

#### Index Method - Added Stats
```php
// BEFORE
public function index(Request $request)
{
    $query = PermohonanZakat::with(['asnaf', 'disemakOleh', 'diluluskanOleh'])
        ->orderBy('created_at', 'desc');
    // ... filters ...
    $permohonan = $query->paginate(10);
    return view('permohonan-zakat.index', compact('permohonan'));
}

// AFTER
public function index(Request $request)
{
    $user = auth()->user();
    $baseQuery = PermohonanZakat::with(['asnaf', 'disemakOleh', 'diluluskanOleh']);
    
    // Multi-Masjid Data Isolation
    if ($user->isSuperAdmin()) {
        // Super Admin can see all
    } else {
        $baseQuery->where('masjid_id', $user->masjid_id);
    }
    
    // Apply filters
    // ... search, status, jenis_bantuan ...
    
    $permohonan = $baseQuery->orderBy('created_at', 'desc')->paginate(10);
    
    // Build stats array - SEPARATE query
    $statsQuery = PermohonanZakat::query();
    if (!$user->isSuperAdmin()) {
        $statsQuery->where('masjid_id', $user->masjid_id);
    }
    
    $stats = [
        ['title' => 'Jumlah Permohonan', 'value' => $totalPermohonan, 'icon' => 'description', 'color' => 'blue'],
        ['title' => 'Menunggu', 'value' => $menunggu, 'icon' => 'pending', 'color' => 'orange'],
        ['title' => 'Dalam Semakan', 'value' => $dalamSemakan, 'icon' => 'rate_review', 'color' => 'blue'],
        ['title' => 'Diluluskan', 'value' => $diluluskan, 'icon' => 'check_circle', 'color' => 'green'],
        ['title' => 'Ditolak', 'value' => $ditolak, 'icon' => 'cancel', 'color' => 'red']
    ];
    
    return view('permohonan-zakat.index', compact('permohonan', 'stats'));
}
```

#### Export Method - Added
```php
// ADDED
public function export(Request $request)
{
    $user = auth()->user();
    $query = PermohonanZakat::with(['asnaf', 'masjid']);
    
    // Apply masjid isolation
    if (!$user->isSuperAdmin()) {
        $query->where('masjid_id', $user->masjid_id);
    }
    
    // Apply same filters as index
    // ... search, status, jenis_bantuan ...
    
    $permohonan = $query->orderBy('created_at', 'desc')->get();
    
    // Generate CSV
    $filename = 'permohonan_zakat_' . date('Y-m-d_H-i-s') . '.csv';
    
    $callback = function() use ($permohonan) {
        $file = fopen('php://output', 'w');
        fputcsv($file, ['No Permohonan', 'Tarikh Permohonan', 'Nama Asnaf', ...]);
        foreach ($permohonan as $row) {
            fputcsv($file, [$row->no_permohonan, ...]);
        }
        fclose($file);
    };
    
    return response()->stream($callback, 200, $headers);
}
```

---

### 3. ROUTES: `routes/web.php`

```php
// ADDED
Route::get('/permohonan-zakat/export', [App\Http\Controllers\PermohonanZakatController::class, 'export'])
    ->middleware('permission:asnaf,read')
    ->name('permohonan-zakat.export');
```
- Placed BEFORE `/permohonan-zakat/create` to avoid route conflict
- Uses same permission as index (asnaf,read)

---

## 📊 COMPARISON

| Feature | Before | After |
|---------|--------|-------|
| **Table Header** | bg-gray-50 | bg-blue-100 ✅ |
| **Row Hover** | hover:bg-gray-50 | hover:bg-white ✅ |
| **Action Icons** | 16px | text-[8px] ✅ |
| **Icon Colors** | blue/yellow/red | gray/blue/red ✅ |
| **Mobile View** | ❌ None | ✅ Card layout |
| **Stats Cards** | ❌ None | ✅ 5 cards |
| **Filter Layout** | ❌ Custom | ✅ Components |
| **Export** | ❌ None | ✅ CSV export |
| **Delete Modal** | ❌ Confirm only | ✅ Security code |
| **Pagination** | ❌ Basic | ✅ With count |
| **Data Isolation** | ⚠️ Partial | ✅ Complete |

---

## ✅ TESTING CHECKLIST

- [x] Route exists: `permohonan-zakat.export`
- [x] No PHP errors in controller
- [x] No Blade syntax errors in view
- [x] Stats cards display correctly
- [x] Desktop table follows Kariah pattern
- [x] Mobile cards responsive
- [x] Filter components work
- [x] Action icons correct size/color
- [x] Delete modal with security code
- [x] Export generates CSV
- [x] Multi-masjid isolation works

---

## 🎯 RESULT

**Permohonan Zakat module now EXACTLY follows Kariah/AJK design pattern.**

All views, controllers, and routes are consistent with the established design system.

---

## 📝 NOTES

1. **Font**: Poppins (10-14px) ✅
2. **Border radius**: 4-8px ✅
3. **Components**: Using x-search-input, x-filter-dropdown, x-action-button ✅
4. **Icons**: Material Icons, proper sizes ✅
5. **Colors**: Following Kariah color scheme ✅
6. **Responsive**: Desktop table + Mobile cards ✅
7. **Data isolation**: Super Admin vs Admin Masjid ✅

---

**Status**: ✅ COMPLETED & VERIFIED
**Date**: 12 December 2025
**Developer**: Kiro AI Assistant
