# E-Masjid Development Guide
**Comprehensive A-Z Guide untuk Pembangunan Page Baru**

## 📋 Table of Contents
1. [Design Patterns & Standards](#design-patterns--standards)
2. [Frontend Development](#frontend-development)
3. [Backend Development](#backend-development)
4. [Data Isolation & Multi-Tenant](#data-isolation--multi-tenant)
5. [Permission Matrix System](#permission-matrix-system)
6. [Testing Guidelines](#testing-guidelines)

---

## 🎨 Design Patterns & Standards

### Font & Typography
```css
/* Font Family: Poppins (WAJIB) */
font-family: 'Poppins', sans-serif;

/* Font Sizes (10px - 14px SAHAJA) */
.text-2xs { font-size: 10px; }  /* Minimum */
.text-xs   { font-size: 11px; }  /* Standard table data */
.text-sm   { font-size: 12px; }  /* Important table data */
.text-base { font-size: 13px; }  /* Mobile titles */
.text-lg   { font-size: 14px; }  /* Maximum */

/* Table Typography Classes */
.table-header        { font-weight: 500; font-size: 11px; }
.table-data          { font-weight: 400; font-size: 11px; }
.table-data-important{ font-weight: 500; font-size: 12px; }
.mobile-title        { font-weight: 600; font-size: 13px; }
.mobile-subtitle     { font-weight: 400; font-size: 10px; }
.mobile-data         { font-weight: 500; font-size: 11px; }
```

### Spacing & Layout
```css
/* Container Pattern */
.container mx-auto px-0 py-0

/* Page Container */
.bg-white shadow-lg border-x border-gray-200 p-6

/* Card Spacing */
padding: 16px (p-4)
margin-bottom: 12px (mb-3)
gap: 12px (gap-3)

/* Border Radius (0px - 2px SAHAJA) */
rounded-xs  /* 0px - untuk table */
rounded-sm  /* 2px - untuk cards, buttons */
rounded     /* 2px - standard */
```

### Button Standards
```html
<!-- Standard Button Height: 32px -->
<button class="inline-flex items-center justify-center h-[32px] px-4 py-1 bg-blue-600 text-white text-xs rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2" style="flex-shrink: 0 !important;">
    <span class="material-icons mr-2" style="font-size: 16px !important;">add</span>
    Button Text
</button>

<!-- Action Icon Buttons -->
<button class="p-2 text-blue-600 hover:text-blue-800 rounded-full hover:bg-blue-50">
    <span class="material-icons text-sm">edit</span>
</button>
```

### Color Scheme
```css
/* Primary Colors */
Blue:   bg-blue-600, text-blue-600, border-blue-600
Green:  bg-green-600, text-green-600, border-green-600
Orange: bg-orange-600, text-orange-600, border-orange-600
Red:    bg-red-600, text-red-600, border-red-600
Purple: bg-purple-600, text-purple-600, border-purple-600

/* Status Colors */
Success: bg-green-100 text-green-800
Warning: bg-orange-100 text-orange-800
Error:   bg-red-100 text-red-800
Info:    bg-blue-100 text-blue-800
```

---

## 🖥️ Frontend Development

### Page Structure Template
```html
<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page Title - E-Masjid</title>
    <x-favicon />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-gray-50 min-h-screen flex flex-col" style="font-family: 'Poppins', sans-serif;">
    <x-double-navbar :user="auth()->user()" />

    <main class="flex-1">
        <div class="container mx-auto px-0 py-0">
            <!-- Page Container -->
            <div class="bg-white shadow-lg border-x border-gray-200 p-6">
                <!-- Header -->
                <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
                    <div>
                        <h1 class="text-xl font-bold text-gray-900 mb-1">Page Title</h1>
                        <p class="text-xs text-gray-600">Page description</p>
                    </div>
                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center space-y-2 sm:space-y-0 sm:space-x-2">
                        <!-- Action buttons here -->
                    </div>
                </div>

                <!-- Statistics Cards -->
                <x-statistics-grid :stats="$stats" />

                <!-- Filters & Search -->
                <!-- Content here -->

                <!-- Desktop Table -->
                <!-- Mobile Card View -->
            </div>
        </div>
    </main>

    <x-footer />
</body>
</html>
```

### Desktop Table Pattern
```html
<div class="hidden md:block overflow-x-auto bg-gray-50 rounded-xs border border-gray-200">
    <table class="min-w-full text-left text-sm">
        <thead class="bg-blue-100 text-gray-600">
            <tr>
                <th class="px-4 py-2 table-header">Column 1</th>
                <th class="px-4 py-2 table-header">Column 2</th>
                <th class="px-4 py-2 table-header text-center">Tindakan</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @forelse($items as $item)
            <tr class="hover:bg-white">
                <td class="px-4 py-2 table-data">
                    <div class="table-data-important text-gray-900">{{ $item->name }}</div>
                    <div class="table-data text-gray-500">{{ $item->created_at->format('d/m/Y') }}</div>
                </td>
                <td class="px-4 py-2 table-data text-gray-600">{{ $item->description }}</td>
                <td class="px-4 py-2 table-data text-center space-x-1">
                    <!-- Action icons here -->
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="3" class="px-4 py-8 text-center text-gray-500">
                    <span class="material-icons mb-2" style="font-size: 48px !important;">inbox</span>
                    <p class="text-sm">Tiada data dijumpai</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
```

### Mobile Card View Pattern
```html
<div class="md:hidden space-y-3">
    @forelse($items as $item)
    <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
        <!-- Header with Name and Actions -->
        <div class="flex items-center justify-between mb-3">
            <div class="flex-1">
                <h3 class="mobile-title text-gray-900">{{ $item->name }}</h3>
                <p class="mobile-subtitle text-gray-500">{{ $item->description }}</p>
            </div>
            <div class="flex items-center space-x-2">
                <!-- Action buttons here -->
            </div>
        </div>

        <!-- Details Grid -->
        <div class="grid grid-cols-2 gap-4 text-xs">
            <div>
                <p class="mobile-label text-gray-500 mb-1">Label</p>
                <span class="mobile-data text-gray-900">{{ $item->value }}</span>
            </div>
        </div>
    </div>
    @empty
    <div class="text-center py-8">
        <span class="material-icons mb-2 text-gray-400" style="font-size: 48px !important;">inbox</span>
        <p class="text-sm text-gray-500">Tiada data dijumpai</p>
    </div>
    @endforelse
</div>
```

---

## ⚙️ Backend Development

### Controller Structure Template
```php
<?php

namespace App\Http\Controllers;

use App\Models\YourModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class YourController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        // Base query with relationships
        $baseQuery = YourModel::with(['relationships']);

        // WAJIB: Multi-Masjid Data Isolation
        if ($user->isSuperAdmin()) {
            // Super Admin can see all data
            // No additional filtering needed
        } else {
            // Admin Masjid can ONLY see data from their own masjid
            $userMasjidId = $user->masjid_id;
            if ($userMasjidId) {
                $baseQuery->where('masjid_id', $userMasjidId);
            } else {
                // If user has no masjid_id, show no data
                $baseQuery->whereRaw('1 = 0'); // Always false condition
            }
        }

        // Apply filters
        if ($request->filled('search')) {
            $search = $request->search;
            $baseQuery->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Get paginated results
        $items = $baseQuery->orderBy('created_at', 'desc')->paginate(10);

        // Build stats array (ALWAYS 3 cards for consistency)
        $totalItems = (clone $baseQuery)->count();
        $activeItems = (clone $baseQuery)->where('status', 'active')->count();
        $inactiveItems = (clone $baseQuery)->where('status', 'inactive')->count();

        $stats = [
            [
                'title' => 'Jumlah Items',
                'value' => $totalItems,
                'icon' => 'inventory',
                'color' => 'blue'
            ],
            [
                'title' => 'Aktif',
                'value' => $activeItems,
                'icon' => 'check_circle',
                'color' => 'green'
            ],
            [
                'title' => 'Tidak Aktif',
                'value' => $inactiveItems,
                'icon' => 'cancel',
                'color' => 'red'
            ]
        ];

        return view('your-module.index', compact('items', 'stats'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = auth()->user();

        // Validation
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'boolean'
        ]);

        // WAJIB: Auto-assign masjid_id for data isolation
        if (!$user->isSuperAdmin()) {
            $validated['masjid_id'] = $user->masjid_id;
        } else {
            // Super Admin can specify masjid_id or leave null
            $validated['masjid_id'] = $request->masjid_id;
        }

        $item = YourModel::create($validated);

        return redirect()->route('your-module.index')
                        ->with('success', 'Item berjaya ditambah');
    }

    /**
     * Display the specified resource.
     */
    public function show(YourModel $item)
    {
        $user = auth()->user();

        // WAJIB: Data isolation check
        if (!$user->isSuperAdmin()) {
            if ($item->masjid_id !== $user->masjid_id) {
                abort(403, 'Unauthorized access to this resource');
            }
        }

        return view('your-module.show', compact('item'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, YourModel $item)
    {
        $user = auth()->user();

        // WAJIB: Data isolation check
        if (!$user->isSuperAdmin()) {
            if ($item->masjid_id !== $user->masjid_id) {
                abort(403, 'Unauthorized access to this resource');
            }
        }

        // Validation
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'boolean'
        ]);

        $item->update($validated);

        return redirect()->route('your-module.index')
                        ->with('success', 'Item berjaya dikemaskini');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(YourModel $item)
    {
        $user = auth()->user();

        // WAJIB: Data isolation check
        if (!$user->isSuperAdmin()) {
            if ($item->masjid_id !== $user->masjid_id) {
                abort(403, 'Unauthorized access to this resource');
            }
        }

        $item->delete();

        return redirect()->route('your-module.index')
                        ->with('success', 'Item berjaya dipadam');
    }
}
```

### Model Structure Template
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasMasjidScope;

class YourModel extends Model
{
    use SoftDeletes, HasMasjidScope;

    protected $fillable = [
        'name',
        'description',
        'status',
        'masjid_id', // WAJIB untuk data isolation
    ];

    protected $casts = [
        'status' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // WAJIB: Relationship dengan Masjid
    public function masjid()
    {
        return $this->belongsTo(Masjid::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    public function scopeInactive($query)
    {
        return $query->where('status', false);
    }
}
```

---

## 🔒 Data Isolation & Multi-Tenant

### WAJIB: Data Isolation Rules
```php
// 1. SETIAP model MESTI ada masjid_id field
Schema::table('your_table', function (Blueprint $table) {
    $table->unsignedBigInteger('masjid_id')->nullable();
    $table->foreign('masjid_id')->references('id')->on('masjids');
});

// 2. SETIAP controller MESTI check data isolation
if (!$user->isSuperAdmin()) {
    if ($item->masjid_id !== $user->masjid_id) {
        abort(403, 'Unauthorized access to this resource');
    }
}

// 3. SETIAP query MESTI filter by masjid_id
if ($user->isSuperAdmin()) {
    // Super Admin can see all data
    $query = YourModel::query();
} else {
    // Admin Masjid can ONLY see their own masjid data
    $userMasjidId = $user->masjid_id;
    if ($userMasjidId) {
        $query = YourModel::where('masjid_id', $userMasjidId);
    } else {
        $query = YourModel::whereRaw('1 = 0'); // No data
    }
}

// 4. SETIAP create operation MESTI auto-assign masjid_id
if (!$user->isSuperAdmin()) {
    $validated['masjid_id'] = $user->masjid_id;
}
```

### HasMasjidScope Trait
```php
<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait HasMasjidScope
{
    /**
     * Boot the trait
     */
    protected static function bootHasMasjidScope()
    {
        // Auto-apply masjid scope for non-Super Admin users
        static::addGlobalScope('masjid', function (Builder $builder) {
            $user = auth()->user();

            if ($user && !$user->isSuperAdmin()) {
                $builder->where('masjid_id', $user->masjid_id);
            }
        });
    }

    /**
     * Remove masjid scope
     */
    public function scopeWithoutMasjidScope($query)
    {
        return $query->withoutGlobalScope('masjid');
    }
}
```

### Route Protection
```php
// routes/web.php
Route::middleware(['auth', 'permission:module_name,action'])->group(function () {
    Route::get('/your-module', [YourController::class, 'index'])->name('your-module.index');
    Route::get('/your-module/create', [YourController::class, 'create'])->name('your-module.create');
    Route::post('/your-module', [YourController::class, 'store'])->name('your-module.store');
    Route::get('/your-module/{item}', [YourController::class, 'show'])->name('your-module.show');
    Route::get('/your-module/{item}/edit', [YourController::class, 'edit'])->name('your-module.edit');
    Route::put('/your-module/{item}', [YourController::class, 'update'])->name('your-module.update');
    Route::delete('/your-module/{item}', [YourController::class, 'destroy'])->name('your-module.destroy');
});

// Permission format: 'module_name,action'
// Actions: create, read, update, delete, approve, reject, suspend, reactivate
```

---

## ✅ Permission Matrix System

### Matrix Checkbox Implementation
```html
<!-- Permission Matrix Container -->
<div class="bg-gray-50 border border-gray-200 rounded-sm p-4 permission-matrix">
    <h3 class="text-sm font-medium text-gray-900 mb-4">Izin Akses</h3>

    <!-- Quick Actions -->
    <div class="mb-4 flex flex-wrap gap-2">
        <button type="button" onclick="selectAll()" class="px-3 py-1 bg-blue-600 text-white text-xs rounded hover:bg-blue-700">
            Pilih Semua
        </button>
        <button type="button" onclick="selectNone()" class="px-3 py-1 bg-gray-600 text-white text-xs rounded hover:bg-gray-700">
            Kosongkan
        </button>
        <button type="button" onclick="selectReadOnly()" class="px-3 py-1 bg-green-600 text-white text-xs rounded hover:bg-green-700">
            Lihat Sahaja
        </button>
    </div>

    <!-- Permission Table -->
    <div class="overflow-x-auto">
        <table class="w-full border border-gray-300 rounded-sm">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 border-b border-gray-300">Kategori</th>
                    <!-- Basic Actions -->
                    <th class="px-2 py-3 text-center text-xs font-medium text-gray-700 border-b border-gray-300 border-l border-gray-300">Tambah</th>
                    <th class="px-2 py-3 text-center text-xs font-medium text-gray-700 border-b border-gray-300 border-l border-gray-300">Lihat</th>
                    <th class="px-2 py-3 text-center text-xs font-medium text-gray-700 border-b border-gray-300 border-l border-gray-300">Kemaskini</th>
                    <th class="px-2 py-3 text-center text-xs font-medium text-gray-700 border-b border-gray-300 border-l border-gray-300">Padam</th>
                    <!-- Workflow Actions -->
                    <th class="px-2 py-3 text-center text-xs font-medium text-gray-700 border-b border-gray-300 border-l border-gray-300">Terima</th>
                    <th class="px-2 py-3 text-center text-xs font-medium text-gray-700 border-b border-gray-300 border-l border-gray-300">Tolak</th>
                    <th class="px-2 py-3 text-center text-xs font-medium text-gray-700 border-b border-gray-300 border-l border-gray-300">Gantung</th>
                    <th class="px-2 py-3 text-center text-xs font-medium text-gray-700 border-b border-gray-300 border-l border-gray-300">Aktifkan</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($modules as $moduleKey => $moduleName)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 text-sm font-medium text-gray-900 border-r border-gray-200">
                        {{ $moduleName }}
                    </td>

                    <!-- Basic Actions Checkboxes -->
                    @foreach(['create' => 'Tambah', 'read' => 'Lihat', 'update' => 'Kemaskini', 'delete' => 'Padam'] as $actionKey => $actionName)
                    <td class="px-2 py-3 text-center border-r border-gray-200">
                        @if($moduleKey === 'masjids' && !auth()->user()->isSuperAdmin())
                            <!-- Masjid module: Super Admin only -->
                            <span class="inline-flex items-center justify-center w-5 h-5 bg-red-100 text-red-400 rounded-full">
                                <span class="material-icons" style="font-size: 14px !important;">block</span>
                            </span>
                        @else
                            <input type="checkbox"
                                   name="permissions[{{ $moduleKey }}][{{ $actionKey }}]"
                                   value="1"
                                   {{ old("permissions.{$moduleKey}.{$actionKey}") ? 'checked' : '' }}
                                   class="permission-checkbox">
                        @endif
                    </td>
                    @endforeach

                    <!-- Workflow Actions Checkboxes -->
                    @foreach(['approve' => 'Terima', 'reject' => 'Tolak', 'suspend' => 'Gantung', 'reactivate' => 'Aktifkan'] as $actionKey => $actionName)
                    <td class="px-2 py-3 text-center border-r border-gray-200">
                        @if(in_array($moduleKey, ['users', 'kariah'])) <!-- Modules with workflow -->
                            <input type="checkbox"
                                   name="permissions[{{ $moduleKey }}][{{ $actionKey }}]"
                                   value="1"
                                   {{ old("permissions.{$moduleKey}.{$actionKey}") ? 'checked' : '' }}
                                   class="permission-checkbox">
                        @else
                            <!-- Not applicable -->
                            <span class="inline-flex items-center justify-center w-5 h-5 bg-gray-100 text-gray-400 rounded-full">
                                <span class="material-icons" style="font-size: 14px !important;">remove</span>
                            </span>
                        @endif
                    </td>
                    @endforeach
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
```

## 🔑 Super Admin Requirements for Kariah Module

### WAJIB: Super Admin Kariah Access
```php
// Super Admin MUST have these capabilities for kariah:

// 1. SEE ALL KARIAH across all masjids
public function index(Request $request)
{
    $user = auth()->user();
    $baseQuery = Kariah::query();

    if ($user->isSuperAdmin()) {
        // ✅ WAJIB: Super Admin sees ALL kariah from ALL masjids
        // No masjid_id filtering
    } else {
        // Admin Masjid only sees their own masjid kariah
        $baseQuery->where('masjid_id', $user->masjid_id);
    }
}

// 2. CREATE KARIAH for any masjid
public function store(Request $request)
{
    if (auth()->user()->isSuperAdmin()) {
        // ✅ WAJIB: Super Admin can specify masjid_id
        $validated['masjid_id'] = $request->masjid_id; // Can be any masjid
    } else {
        // Admin Masjid auto-assigned to their masjid
        $validated['masjid_id'] = auth()->user()->masjid_id;
    }
}

// 3. EDIT/DELETE any kariah from any masjid
public function show/edit/update/destroy(Kariah $kariah)
{
    $user = auth()->user();

    if (!$user->isSuperAdmin()) {
        // ✅ WAJIB: Data isolation check for Admin Masjid
        if ($kariah->masjid_id !== $user->masjid_id) {
            abort(403, 'Unauthorized access');
        }
    }
    // Super Admin bypasses all checks - can access any kariah
}

// 4. STATISTICS across all masjids
$totalKariah = $user->isSuperAdmin()
    ? Kariah::count() // All masjids
    : Kariah::where('masjid_id', $user->masjid_id)->count(); // Own masjid only
```

### WAJIB: Super Admin Form Fields
```blade
{{-- Super Admin gets additional masjid selection field --}}
@if(auth()->user()->isSuperAdmin())
<div>
    <label class="block text-xs font-medium text-gray-700 mb-1">Masjid <span class="text-red-500">*</span></label>
    <select name="masjid_id" class="w-full h-[32px] px-3 text-xs border border-gray-300 rounded focus:ring-2 focus:ring-blue-500">
        <option value="">Pilih Masjid</option>
        @foreach(\App\Models\Masjid::orderBy('nama')->get() as $masjid)
        <option value="{{ $masjid->id }}" {{ old('masjid_id', $kariah->masjid_id ?? '') == $masjid->id ? 'selected' : '' }}>
            {{ $masjid->nama }}
        </option>
        @endforeach
    </select>
</div>
@endif
```

### WAJIB: Super Admin Index View
```blade
{{-- Super Admin sees masjid column in table --}}
@if(auth()->user()->isSuperAdmin())
<th class="table-header">Masjid</th>
@endif

{{-- In table body --}}
@if(auth()->user()->isSuperAdmin())
<td class="table-data">{{ $kariah->masjid->nama ?? 'Tiada Masjid' }}</td>
@endif
```

### WAJIB: Checkbox CSS Override
```css
/* EMERGENCY CHECKBOX OVERRIDE - HIGHEST PRIORITY */
.permission-matrix input[type="checkbox"],
input.permission-checkbox {
    appearance: none !important;
    -webkit-appearance: none !important;
    -moz-appearance: none !important;
    background: white !important;
    background-color: white !important;
    background-image: none !important;
    border: 2px solid #d1d5db !important;
    border-radius: 2px !important;
    width: 16px !important;
    height: 16px !important;
    min-width: 16px !important;
    min-height: 16px !important;
    max-width: 16px !important;
    max-height: 16px !important;
    position: relative !important;
    cursor: pointer !important;
    transition: all 0.2s ease !important;
    margin: 0 !important;
    padding: 0 !important;
    vertical-align: middle !important;
    display: inline-block !important;
    box-sizing: border-box !important;
}

.permission-matrix input[type="checkbox"]:checked,
input.permission-checkbox:checked {
    background-color: #3b82f6 !important;
    border-color: #3b82f6 !important;
}

.permission-matrix input[type="checkbox"]:checked::before,
input.permission-checkbox:checked::before {
    content: '✓' !important;
    position: absolute !important;
    top: 50% !important;
    left: 50% !important;
    transform: translate(-50%, -50%) !important;
    color: white !important;
    font-size: 12px !important;
    font-weight: bold !important;
    line-height: 1 !important;
    display: block !important;
}

.permission-matrix input[type="checkbox"]:hover,
input.permission-checkbox:hover {
    border-color: #3b82f6 !important;
}
```

### WAJIB: JavaScript Functions
```javascript
<script>
function selectAll() {
    const checkboxes = document.querySelectorAll('input[type="checkbox"][name^="permissions"]');
    checkboxes.forEach(checkbox => checkbox.checked = true);
    enforceCheckboxStyling();
}

function selectNone() {
    const checkboxes = document.querySelectorAll('input[type="checkbox"][name^="permissions"]');
    checkboxes.forEach(checkbox => checkbox.checked = false);
    enforceCheckboxStyling();
}

function selectReadOnly() {
    selectNone();
    const readCheckboxes = document.querySelectorAll('input[type="checkbox"][name*="[read]"]');
    readCheckboxes.forEach(checkbox => checkbox.checked = true);
    enforceCheckboxStyling();
}

// WAJIB: Emergency checkbox styling enforcement
function enforceCheckboxStyling() {
    const checkboxes = document.querySelectorAll('input[type="checkbox"]');
    checkboxes.forEach(checkbox => {
        // Force inline styles to override any framework
        checkbox.style.setProperty('appearance', 'none', 'important');
        checkbox.style.setProperty('-webkit-appearance', 'none', 'important');
        checkbox.style.setProperty('-moz-appearance', 'none', 'important');
        checkbox.style.setProperty('background', 'white', 'important');
        checkbox.style.setProperty('border', '2px solid #d1d5db', 'important');
        checkbox.style.setProperty('border-radius', '2px', 'important');
        checkbox.style.setProperty('width', '16px', 'important');
        checkbox.style.setProperty('height', '16px', 'important');
        checkbox.style.setProperty('position', 'relative', 'important');
        checkbox.style.setProperty('cursor', 'pointer', 'important');

        // Handle checked state
        if (checkbox.checked) {
            checkbox.style.setProperty('background-color', '#3b82f6', 'important');
            checkbox.style.setProperty('border-color', '#3b82f6', 'important');
        }

        // Add event listeners for state changes
        checkbox.addEventListener('change', function() {
            if (this.checked) {
                this.style.setProperty('background-color', '#3b82f6', 'important');
                this.style.setProperty('border-color', '#3b82f6', 'important');
            } else {
                this.style.setProperty('background-color', 'white', 'important');
                this.style.setProperty('border-color', '#d1d5db', 'important');
            }
        });
    });
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    // Run checkbox styling enforcement multiple times to ensure override
    enforceCheckboxStyling();
    setTimeout(enforceCheckboxStyling, 100);
    setTimeout(enforceCheckboxStyling, 500);
    setTimeout(enforceCheckboxStyling, 1000);
});
</script>
```

### Permission Actions Mapping
```php
// Controller: Define available actions
$actions = [
    'basic' => [
        'create' => 'Tambah',
        'read' => 'Lihat',
        'update' => 'Kemaskini',
        'delete' => 'Padam'
    ],
    'workflow' => [
        'approve' => 'Terima',
        'reject' => 'Tolak',
        'suspend' => 'Gantung',
        'reactivate' => 'Aktifkan'
    ]
];

// Modules with different permission sets
$readOnlyModules = ['reports', 'statistics'];
$settingsOnlyModules = ['tetapan', 'integrations'];
$workflowModules = ['users', 'kariah', 'applications'];
$superAdminOnlyModules = ['masjids', 'roles'];

// Super Admin Access Levels:
// 1. FULL ACCESS: Super Admin can see ALL data across ALL masjids
//    - kariah: Can see/manage kariah from all masjids
//    - users: Can see/manage users from all masjids
//    - applications: Can see/manage applications from all masjids
//
// 2. EXCLUSIVE ACCESS: Only Super Admin can access these modules
//    - masjids: Only Super Admin can manage masjid registry
//    - roles: Only Super Admin can create/manage global roles
//
// 3. DATA ISOLATION BYPASS: Super Admin bypasses masjid_id filtering
//    - HasMasjidScope trait: if ($user->isSuperAdmin()) { no filtering }
//    - Controller queries: Super Admin sees all records
//    - Permission checks: Super Admin has all permissions
//
// 4. HARDCODED PERMISSIONS: Super Admin role must have all permissions
//    - kariah: create, read, update, delete, approve, reject, suspend, reactivate
//    - users: create, read, update, delete, suspend, reactivate
//    - masjids: create, read, update, delete, approve, reject, suspend, reactivate
//    - roles: create, read, update, delete
//    - All other modules: full permissions
```

---

## 🧪 Testing Guidelines

### WAJIB: Test Data Isolation
```php
// tests/Feature/YourModuleTest.php
public function test_admin_masjid_can_only_see_own_data()
{
    $masjid1 = Masjid::factory()->create();
    $masjid2 = Masjid::factory()->create();

    $admin1 = User::factory()->create(['masjid_id' => $masjid1->id]);
    $admin2 = User::factory()->create(['masjid_id' => $masjid2->id]);

    $item1 = YourModel::factory()->create(['masjid_id' => $masjid1->id]);
    $item2 = YourModel::factory()->create(['masjid_id' => $masjid2->id]);

    // Admin 1 should only see item 1
    $this->actingAs($admin1)
         ->get(route('your-module.index'))
         ->assertSee($item1->name)
         ->assertDontSee($item2->name);

    // Admin 2 should only see item 2
    $this->actingAs($admin2)
         ->get(route('your-module.index'))
         ->assertSee($item2->name)
         ->assertDontSee($item1->name);
}

public function test_super_admin_can_see_all_data()
{
    $superAdmin = User::factory()->superAdmin()->create();
    $masjid1 = Masjid::factory()->create();
    $masjid2 = Masjid::factory()->create();

    $item1 = YourModel::factory()->create(['masjid_id' => $masjid1->id]);
    $item2 = YourModel::factory()->create(['masjid_id' => $masjid2->id]);

    // Super Admin should see all items
    $this->actingAs($superAdmin)
         ->get(route('your-module.index'))
         ->assertSee($item1->name)
         ->assertSee($item2->name);
}
```

### Test Permission Matrix
```php
public function test_permission_matrix_saves_correctly()
{
    $superAdmin = User::factory()->superAdmin()->create();

    $permissions = [
        'users' => ['create' => '1', 'read' => '1'],
        'kariah' => ['read' => '1', 'update' => '1', 'approve' => '1']
    ];

    $response = $this->actingAs($superAdmin)
                     ->post(route('roles.store'), [
                         'name' => 'Test Role',
                         'permissions' => $permissions
                     ]);

    $role = Role::where('name', 'Test Role')->first();

    $this->assertTrue($role->hasPermission('users', 'create'));
    $this->assertTrue($role->hasPermission('users', 'read'));
    $this->assertFalse($role->hasPermission('users', 'delete'));
    $this->assertTrue($role->hasPermission('kariah', 'approve'));
}
```

---

## 📝 Summary Checklist

### ✅ Frontend Requirements
- [ ] Font: Poppins (10px-14px)
- [ ] Border radius: 0px-2px max
- [ ] Button height: 32px standard
- [ ] Responsive: Desktop table + Mobile cards
- [ ] Icons: Material Icons 16px
- [ ] Colors: Consistent scheme
- [ ] Typography: Proper classes

### ✅ Backend Requirements
- [ ] Data isolation: masjid_id filtering
- [ ] Permission checks: hasPermission()
- [ ] Route protection: middleware
- [ ] Model relationships: belongsTo(Masjid)
- [ ] Validation: proper rules
- [ ] Error handling: 403 for unauthorized

### ✅ Permission Matrix
- [ ] 8 actions: tambah, lihat, kemaskini, padam, terima, tolak, gantung, aktifkan
- [ ] Checkbox CSS override: !important
- [ ] JavaScript functions: selectAll, selectNone, selectReadOnly
- [ ] Module-specific rules: Super Admin only, workflow modules
- [ ] Emergency styling enforcement

### ✅ Testing
- [ ] Data isolation tests
- [ ] Permission matrix tests
- [ ] CRUD operation tests
- [ ] Authorization tests

**Ikut guide ini untuk consistency dan quality code yang tinggi!** 🚀
