# TEMPAHAN FASILITI - MULTIPLE ITEMS FEATURE
## Phase 1: Database & Backend - COMPLETE ✅

**Date**: 15 December 2025
**Status**: Phase 1 COMPLETE, Moving to Phase 2

---

## ✅ COMPLETED TASKS

### 1. Database Migrations ✅

**Migration 1**: `2025_12_14_195106_add_multiple_items_support_to_tempahan_fasiliti.php`
- ✅ Added `kuantiti_total` to `senarai_fasiliti` (default: 1)
- ✅ Added `is_countable` to `senarai_fasiliti` (default: false)
- ✅ Created `tempahan_fasiliti_items` table with fields:
  - `tempahan_fasiliti_id` (FK)
  - `senarai_fasiliti_id` (FK)
  - `quantity` (int, default: 1)
  - `harga_per_unit` (decimal)
  - `subtotal` (decimal)
  - `status_item` (enum: Aktif, Dibatalkan)
  - Cancellation fields: `dibatalkan_oleh`, `tarikh_dibatalkan`, `sebab_batal_item`
- ✅ Made `senarai_fasiliti_id` nullable in `tempahan_fasiliti` (for backward compatibility)

**Migration 2**: `2025_12_14_195455_seed_fasiliti_inventory_data.php`
- ✅ Updated existing fasiliti based on `jenis_fasiliti`:
  - Dewan, Bilik, Padang, Tempat Letak Kereta → `kuantiti_total=1`, `is_countable=false`
  - Aset → `kuantiti_total=1`, `is_countable=true` (admin can update later)
  - Lain-lain → `kuantiti_total=1`, `is_countable=false`

### 2. Models ✅

**TempahanFasilitiItem Model** - NEW
- ✅ Created model with relationships:
  - `tempahanFasiliti()` - belongsTo
  - `senariFasiliti()` - belongsTo
  - `dibatalkanOleh()` - belongsTo User
- ✅ Scopes: `aktif()`, `dibatalkan()`
- ✅ Method: `cancelItem($userId, $reason)` - cancel individual item

**TempahanFasiliti Model** - UPDATED
- ✅ Added relationships:
  - `items()` - hasMany TempahanFasilitiItem
  - `activeItems()` - hasMany with status filter
- ✅ Added methods:
  - `recalculateTotal()` - recalculate total after item cancellation
  - `getTotalItemsAttribute()` - count active items
  - `getTotalQuantityAttribute()` - sum quantities

**SenariFasiliti Model** - UPDATED
- ✅ Added relationship:
  - `tempahanItems()` - hasMany TempahanFasilitiItem
- ✅ Added methods:
  - `checkAvailability($tarikhMula, $tarikhTamat, $excludeTempahanId)` - check available quantity
  - `getPriceByUnit($unitTempoh)` - get price based on unit

### 3. Controller & Routes ✅

**TempahanFasilitiController** - UPDATED
- ✅ Added method: `checkAvailability(Request $request)` - AJAX endpoint
  - Returns: available, total, booked, is_countable, nama_fasiliti, jenis_fasiliti

**Routes** - UPDATED
- ✅ Added route: `GET tempahan-fasiliti/check-availability`
  - Name: `tempahan-fasiliti.check-availability`
  - Middleware: `auth`, `verified`, `permission:operasi,read`

---

## 📊 DATABASE STRUCTURE

### Table: `senarai_fasiliti` (UPDATED)
```sql
+ kuantiti_total INT DEFAULT 1
+ is_countable BOOLEAN DEFAULT FALSE
```

### Table: `tempahan_fasiliti_items` (NEW)
```sql
id BIGINT PRIMARY KEY
tempahan_fasiliti_id BIGINT FK
senarai_fasiliti_id BIGINT FK
quantity INT DEFAULT 1
harga_per_unit DECIMAL(10,2)
subtotal DECIMAL(10,2)
status_item ENUM('Aktif', 'Dibatalkan')
dibatalkan_oleh BIGINT FK (users)
tarikh_dibatalkan DATETIME
sebab_batal_item TEXT
created_at TIMESTAMP
updated_at TIMESTAMP
```

### Table: `tempahan_fasiliti` (UPDATED)
```sql
senarai_fasiliti_id → NULLABLE (for backward compatibility)
```

---

## 🔧 API ENDPOINT

### Check Availability
**Endpoint**: `GET /tempahan-fasiliti/check-availability`

**Parameters**:
- `fasiliti_id` (required) - ID of fasiliti
- `tarikh_mula` (required) - Start datetime
- `tarikh_tamat` (required) - End datetime
- `exclude_tempahan_id` (optional) - Exclude specific tempahan (for edit mode)

**Response**:
```json
{
  "available": 500,
  "total": 1000,
  "booked": 500,
  "is_countable": true,
  "nama_fasiliti": "Kerusi Plastik",
  "jenis_fasiliti": "Aset"
}
```

---

## 🎯 BUSINESS LOGIC IMPLEMENTED

### Availability Checking Algorithm
```php
1. Query tempahan_fasiliti_items for specific fasiliti
2. Join with tempahan_fasiliti
3. Filter by:
   - status_item = 'Aktif'
   - status_tempahan IN ('Baharu', 'Dalam Semakan', 'Lulus')
   - Date/time overlap:
     a) tarikh_mula BETWEEN [start, end]
     b) tarikh_tamat BETWEEN [start, end]
     c) tarikh_mula <= start AND tarikh_tamat >= end
4. Sum quantity from matching records
5. Calculate: available = kuantiti_total - booked
6. Return max(0, available)
```

### Item Types
1. **Unique Items** (is_countable = false)
   - Examples: Dewan, Bilik, Padang
   - kuantiti_total = 1
   - If booked → available = 0 → hide from dropdown

2. **Countable Items** (is_countable = true)
   - Examples: Kerusi, Meja, Microphone
   - kuantiti_total > 1
   - Show: "Kerusi (500 / 1000 available)"
   - Allow partial booking

---

## 🧪 TESTING DONE

### Database
- ✅ Migrations run successfully
- ✅ Tables created with correct structure
- ✅ Existing fasiliti updated with inventory data
- ✅ Foreign keys and indexes created

### Models
- ✅ Relationships working
- ✅ Methods tested via tinker
- ✅ Availability calculation logic verified

### API Endpoint
- ✅ Route registered
- ✅ Controller method created
- ✅ JSON response format correct

---

## 📝 NEXT STEPS - Phase 2: Frontend

### Tasks Remaining:
1. ❌ Update `create.blade.php` - Dynamic item selection
2. ❌ Update `edit.blade.php` - Edit existing items
3. ❌ Update `show.blade.php` - Display all items
4. ❌ Add JavaScript for dynamic item management
5. ❌ Add JavaScript for real-time availability checking
6. ❌ Add JavaScript for auto-calculate total price
7. ❌ Update `store()` method to handle multiple items
8. ❌ Update `update()` method to handle multiple items
9. ❌ Update `lulus()` method for multiple items
10. ❌ Add item cancellation functionality

---

## 💡 KEY FEATURES READY

✅ **Inventory Management**
- Track total quantity per fasiliti
- Distinguish between unique and countable items
- Real-time availability calculation

✅ **Individual Item Cancellation**
- Cancel specific items without cancelling whole booking
- Track cancellation history per item
- Auto-recalculate total after cancellation

✅ **Backward Compatibility**
- Old tempahan records still work
- Gradual migration possible
- No breaking changes

✅ **Multi-Masjid Support**
- Availability checking respects masjid_id
- Data isolation maintained

---

## 🚀 READY FOR PHASE 2

Phase 1 is complete and tested. The database structure and backend logic are ready.

**Next**: Implement frontend (create/edit forms with dynamic item selection)

**Estimated Time for Phase 2**: 4-6 hours
