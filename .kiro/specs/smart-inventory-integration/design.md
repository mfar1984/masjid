# Design Document: Smart Inventory Integration

## Overview

Feature ini mengintegrasikan modul Tempahan Fasiliti dengan Pergerakan Aset untuk mewujudkan sistem inventori pintar. Sistem akan automatik mencipta rekod pergerakan aset apabila tempahan diluluskan, mengira kuantiti tersedia berdasarkan tarikh, dan mengembalikan kuantiti ke inventori apabila aset dipulangkan.

## Architecture

```
┌─────────────────────────────────────────────────────────────────┐
│                    TEMPAHAN FASILITI                            │
│  ┌─────────────┐    ┌─────────────┐    ┌─────────────┐         │
│  │   Create    │───▶│   Approve   │───▶│  Complete   │         │
│  │  Tempahan   │    │  Tempahan   │    │  Tempahan   │         │
│  └─────────────┘    └──────┬──────┘    └──────▲──────┘         │
│                            │                   │                │
│                            │ Auto-create       │ On Return      │
│                            ▼                   │                │
│  ┌─────────────────────────────────────────────┴──────────────┐│
│  │                   PERGERAKAN ASET                          ││
│  │  ┌─────────────┐    ┌─────────────┐    ┌─────────────┐    ││
│  │  │ Belum Pulang│───▶│    Lewat    │───▶│Sudah Pulang │    ││
│  │  └─────────────┘    └─────────────┘    └─────────────┘    ││
│  └────────────────────────────────────────────────────────────┘│
│                                                                 │
│  ┌─────────────────────────────────────────────────────────────┐│
│  │                 INVENTORY CALCULATION                       ││
│  │  Total Kuantiti - Tempahan Aktif - Pergerakan Belum Pulang ││
│  │  = Kuantiti Tersedia                                       ││
│  └─────────────────────────────────────────────────────────────┘│
└─────────────────────────────────────────────────────────────────┘
```

## Components and Interfaces

### 1. Database Changes

#### Table: tempahan_fasiliti (Add columns)
```sql
-- Lokasi Destinasi fields
is_lokasi_luaran BOOLEAN DEFAULT false
lokasi_destinasi VARCHAR(255) -- untuk lokasi dalaman
nama_tempat_luaran VARCHAR(255)
alamat_luaran_1 VARCHAR(255)
alamat_luaran_2 VARCHAR(255)
poskod_luaran VARCHAR(10)
bandar_luaran VARCHAR(100)
negeri_luaran VARCHAR(100)

-- Status Pemulangan
status_pemulangan ENUM('Belum Pulang', 'Sudah Pulang', 'Lewat', 'Sebahagian') DEFAULT 'Belum Pulang'
tarikh_sebenar_pulangan DATETIME
```

#### Table: pergerakan_aset (Add columns)
```sql
-- Reference to tempahan
tempahan_fasiliti_id BIGINT UNSIGNED NULLABLE
tempahan_fasiliti_item_id BIGINT UNSIGNED NULLABLE
kuantiti INTEGER DEFAULT 1
```

#### Table: senarai_fasiliti (Add column)
```sql
kuantiti_total INTEGER DEFAULT 1 -- Total unit available
```

### 2. Model Changes

#### TempahanFasiliti Model
- Add relationship: `pergerakanAset()` - hasMany through items
- Add method: `markAsReturned()` - update status and create return records
- Add accessor: `getStatusPemulanganAttribute()` - calculate from pergerakan

#### PergerakanAset Model
- Add relationship: `tempahanFasiliti()` - belongsTo
- Add relationship: `tempahanFasilitiItem()` - belongsTo

#### SenariFasiliti Model
- Add method: `getKuantitiTersediaAttribute($tarikhMula, $tarikhTamat)` - calculate available quantity
- Update method: `checkAvailability()` - include pergerakan in calculation

### 3. Service Class: InventoryService

```php
class InventoryService
{
    // Calculate available quantity for date range
    public function getAvailableQuantity($fasilitiId, $tarikhMula, $tarikhTamat, $excludeTempahanId = null): int
    
    // Create pergerakan records when tempahan approved
    public function createPergerakanFromTempahan(TempahanFasiliti $tempahan): Collection
    
    // Process return and update inventory
    public function processReturn($pergerakanId, $kondisiSelepas, $catatan = null): bool
    
    // Process return from tempahan (all items)
    public function processTempahanReturn(TempahanFasiliti $tempahan, $kondisiSelepas, $catatan = null): bool
    
    // Auto-detect and mark late returns
    public function markLateReturns(): int
}
```

### 4. Controller Changes

#### TempahanFasilitiController
- Update `store()`: Save lokasi destinasi fields
- Update `lulus()`: Call InventoryService to create pergerakan
- Add `pulang()`: Process return action
- Add `checkAvailability()`: API endpoint for AJAX availability check

#### PergerakanAsetController
- Update `pulang()`: Also update related tempahan if exists

### 5. View Changes

#### tempahan-fasiliti/create.blade.php & edit.blade.php
- Add Section 3: Lokasi Destinasi (similar to pergerakan-aset form)
- Add AJAX to show available quantity when fasiliti selected

#### tempahan-fasiliti/index.blade.php
- Add column: Status Pemulangan
- Add filter: Status Pemulangan
- Add icon: Pulangkan (for approved tempahan)

#### tempahan-fasiliti/show.blade.php
- Add section: Pergerakan Aset Berkaitan
- Add button: Pulangkan (with modal)

#### pergerakan-aset/show.blade.php
- Add link to tempahan if exists

## Data Models

### Availability Calculation Flow
```
1. Get total kuantiti from senarai_fasiliti.kuantiti_total
2. Subtract: Active tempahan items for overlapping dates
   - WHERE status_tempahan IN ('Baharu', 'Dalam Semakan', 'Lulus')
   - WHERE date ranges overlap
3. Subtract: Pergerakan aset belum pulang (not from tempahan)
   - WHERE status_pulangan IN ('Belum Pulang', 'Lewat')
   - WHERE tempahan_fasiliti_id IS NULL
4. Result = Available quantity
```

### Return Flow
```
1. User clicks "Pulangkan" on tempahan or pergerakan
2. Modal shows: Kondisi Selepas, Catatan
3. On submit:
   a. Update pergerakan_aset.status_pulangan = 'Sudah Pulang'
   b. Update pergerakan_aset.tarikh_sebenar_pulangan = now()
   c. Update pergerakan_aset.kondisi_selepas
   d. If all items returned, update tempahan.status_tempahan = 'Selesai'
   e. Update tempahan.status_pemulangan accordingly
```

## Correctness Properties

*A property is a characteristic or behavior that should hold true across all valid executions of a system-essentially, a formal statement about what the system should do. Properties serve as the bridge between human-readable specifications and machine-verifiable correctness guarantees.*

### Property 1: Tempahan Approval Creates Correct Pergerakan
*For any* approved tempahan with items, the system should create pergerakan_aset records where:
- Each item has a corresponding pergerakan record
- Lokasi destinasi matches tempahan lokasi destinasi
- Status pulangan is "Belum Pulang"
- tempahan_fasiliti_id references the tempahan
- jenis_pergerakan is "Pinjaman" or "Sewa" based on tempahan type
**Validates: Requirements 1.1, 1.2, 1.3, 1.4, 8.3**

### Property 2: Availability Calculation Accuracy
*For any* fasiliti and date range, the available quantity should equal:
`kuantiti_total - (active tempahan items for overlapping dates) - (pergerakan belum pulang not from tempahan)`
**Validates: Requirements 2.1, 2.2, 2.3**

### Property 3: Overbooking Prevention
*For any* tempahan request where requested quantity exceeds available quantity, the system should reject the request
**Validates: Requirements 2.4**

### Property 4: Return Round-Trip Consistency
*For any* tempahan that is approved then fully returned, the available quantity should return to the original value before the tempahan was made
**Validates: Requirements 3.2, 3.3, 3.4, 3.5**

### Property 5: Late Detection Accuracy
*For any* tempahan where tarikh_tamat has passed and status_pemulangan is "Belum Pulang", the system should mark it as "Lewat"
**Validates: Requirements 7.1**

### Property 6: Tempahan-Pergerakan Bidirectional Link
*For any* pergerakan created from tempahan, viewing the tempahan should show the pergerakan, and viewing the pergerakan should link back to the tempahan
**Validates: Requirements 8.1, 8.2**

### Property 7: Filter Correctness
*For any* filter by status_pemulangan, all returned results should have the matching status
**Validates: Requirements 5.5**

## Error Handling

1. **Insufficient Quantity**: Show clear error message with available quantity
2. **Concurrent Booking**: Use database transactions with locking
3. **Invalid Date Range**: Validate tarikh_mula < tarikh_tamat
4. **Missing Lokasi**: Require lokasi destinasi for all tempahan
5. **Return Without Pergerakan**: Handle edge case where pergerakan doesn't exist

## Testing Strategy

### Unit Tests
- Test InventoryService methods individually
- Test availability calculation with various scenarios
- Test return processing logic

### Property-Based Tests
Using **Pest PHP** with property-based testing capabilities:

1. **Property 1**: Generate random tempahan, approve, verify pergerakan created correctly
2. **Property 2**: Generate random bookings, verify availability calculation
3. **Property 3**: Generate overbooking scenarios, verify rejection
4. **Property 4**: Generate book-return cycles, verify quantity restoration
5. **Property 5**: Generate past-due tempahan, verify late detection
6. **Property 6**: Generate tempahan with pergerakan, verify bidirectional links
7. **Property 7**: Generate mixed status data, verify filter accuracy

Each property test should run minimum 100 iterations with random data.
