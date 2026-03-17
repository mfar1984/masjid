# TEMPAHAN FASILITI - MULTIPLE ITEMS BOOKING FEATURE

**Date**: 15 December 2025
**Feature**: Multiple Items per Booking dengan Inventory Management
**Status**: PROPOSAL 📋

---

## 🎯 REQUIREMENT SUMMARY

**User Request**:
> "saya nak masukkan dewan, selepas itu saya kena masukkan meja, kerusi, PA System dan lain-lain. boleh atau tidak saya masukkan semua senarai ini sekaligus untuk 1 tempahan atas 1 nama."

**Key Requirements**:
1. ✅ **Multiple Items per Booking** - Satu tempahan boleh ada banyak item (Dewan + Meja + Kerusi + PA System)
2. ✅ **Availability Checking** - Check availability berdasarkan tarikh dan masa
3. ✅ **Inventory Management** - Track quantity available vs booked
4. ✅ **Real-time Stock Display** - Show remaining quantity untuk item yang ada stock

---

## 📊 CURRENT vs PROPOSED SYSTEM

### Current System (Sekarang) ❌
```
1 Tempahan = 1 Fasiliti sahaja
- Tempahan #001: Dewan Utama
- Tempahan #002: Meja (50 unit) 
- Tempahan #003: Kerusi (100 unit)
- Tempahan #004: PA System

Problem: Kena buat 4 tempahan berasingan untuk 1 event!
```

### Proposed System (Cadangan) ✅
```
1 Tempahan = Multiple Items
- Tempahan #001:
  * Dewan Utama (1 unit)
  * Meja (50 unit dari 100 available)
  * Kerusi (200 unit dari 1000 available)
  * PA System (1 unit dari 1 available)
  
Solution: Semua item dalam 1 tempahan untuk 1 nama penyewa!
```

---

## 🔍 BUSINESS LOGIC

### 1. Unique Items (Dewan, Bilik, Padang)
**Characteristics**:
- Quantity = 1 (unique, cannot be shared)
- If booked on specific date/time → NOT AVAILABLE for others
- Example: Dewan Utama, Bilik Mesyuarat A

**Availability Logic**:
```
IF item.quantity = 1 AND item is booked on date/time
THEN hide from dropdown (not available)
ELSE show in dropdown
```

### 2. Countable Items (Meja, Kerusi, Equipment)
**Characteristics**:
- Quantity > 1 (can be partially booked)
- Track: Total Stock vs Booked vs Available
- Example: 1000 Kerusi, 100 Meja, 5 Microphones

**Availability Logic**:
```
Total Stock: 1000 kerusi
Already Booked on same date/time: 500 kerusi
Available: 500 kerusi

Display: "Kerusi (500 / 1000 available)"
```

### 3. Unique Equipment (PA System, Projector)
**Characteristics**:
- Quantity = 1 (unique equipment)
- If booked → NOT AVAILABLE
- Example: PA System, LCD Projector

**Availability Logic**:
```
IF equipment is booked on date/time
THEN hide from dropdown
ELSE show in dropdown
```

---

## 🗄️ DATABASE CHANGES NEEDED

### New Table: `tempahan_fasiliti_items`
```sql
CREATE TABLE tempahan_fasiliti_items (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    tempahan_fasiliti_id BIGINT UNSIGNED NOT NULL,
    senarai_fasiliti_id BIGINT UNSIGNED NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    harga_per_unit DECIMAL(10,2) NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    
    FOREIGN KEY (tempahan_fasiliti_id) REFERENCES tempahan_fasiliti(id) ON DELETE CASCADE,
    FOREIGN KEY (senarai_fasiliti_id) REFERENCES senarai_fasiliti(id) ON DELETE RESTRICT
);
```

### Update Table: `senarai_fasiliti`
```sql
ALTER TABLE senarai_fasiliti 
ADD COLUMN kuantiti_total INT DEFAULT 1 COMMENT 'Total quantity available',
ADD COLUMN is_countable BOOLEAN DEFAULT FALSE COMMENT 'TRUE if item can be counted (meja, kerusi), FALSE if unique (dewan)';
```

### Update Table: `tempahan_fasiliti`
```sql
-- Remove single fasiliti reference
ALTER TABLE tempahan_fasiliti 
DROP FOREIGN KEY tempahan_fasiliti_senarai_fasiliti_id_foreign;

ALTER TABLE tempahan_fasiliti 
DROP COLUMN senarai_fasiliti_id;

-- Keep pricing fields for total calculation
-- harga_sewa, deposit, jumlah_bayaran will be sum of all items
```

---

## 🎨 UI/UX CHANGES

### Create/Edit Form - Section 2: Pilih Fasiliti & Aset

**Before (Current)**:
```html
<select name="senarai_fasiliti_id">
    <option>Dewan Utama</option>
</select>
```

**After (Proposed)**:
```html
<!-- Dynamic Item Selection -->
<div id="items-container">
    <div class="item-row">
        <select name="items[0][fasiliti_id]" class="fasiliti-select">
            <option value="">-- Pilih Fasiliti/Aset --</option>
            <option value="1" data-type="unique" data-available="1">Dewan Utama (Available)</option>
            <option value="2" data-type="countable" data-available="500" data-total="1000">Kerusi (500 / 1000 available)</option>
            <option value="3" data-type="unique" data-available="0" disabled>PA System (Not Available)</option>
        </select>
        
        <input type="number" name="items[0][quantity]" min="1" max="500" value="1" class="quantity-input">
        
        <span class="availability-info">Available: 500 units</span>
        
        <button type="button" class="remove-item">Remove</button>
    </div>
</div>

<button type="button" id="add-item">+ Tambah Item</button>
```

**Features**:
- ✅ Add multiple items dynamically
- ✅ Show availability in real-time
- ✅ Disable items that are fully booked
- ✅ Show remaining quantity for countable items
- ✅ Auto-calculate total price

---

## 💻 BACKEND LOGIC

### 1. Check Availability (AJAX Endpoint)
```php
// Route: GET /api/check-availability
public function checkAvailability(Request $request)
{
    $fasilitiId = $request->fasiliti_id;
    $tarikhMula = $request->tarikh_mula;
    $tarikhTamat = $request->tarikh_tamat;
    
    $fasiliti = SenariFasiliti::find($fasilitiId);
    
    // Get total booked on same date/time
    $totalBooked = DB::table('tempahan_fasiliti_items')
        ->join('tempahan_fasiliti', 'tempahan_fasiliti_items.tempahan_fasiliti_id', '=', 'tempahan_fasiliti.id')
        ->where('tempahan_fasiliti_items.senarai_fasiliti_id', $fasilitiId)
        ->where('tempahan_fasiliti.status_tempahan', '!=', 'Dibatalkan')
        ->where('tempahan_fasiliti.status_tempahan', '!=', 'Ditolak')
        ->where(function($q) use ($tarikhMula, $tarikhTamat) {
            $q->whereBetween('tempahan_fasiliti.tarikh_mula', [$tarikhMula, $tarikhTamat])
              ->orWhereBetween('tempahan_fasiliti.tarikh_tamat', [$tarikhMula, $tarikhTamat])
              ->orWhere(function($q2) use ($tarikhMula, $tarikhTamat) {
                  $q2->where('tempahan_fasiliti.tarikh_mula', '<=', $tarikhMula)
                     ->where('tempahan_fasiliti.tarikh_tamat', '>=', $tarikhTamat);
              });
        })
        ->sum('tempahan_fasiliti_items.quantity');
    
    $available = $fasiliti->kuantiti_total - $totalBooked;
    
    return response()->json([
        'available' => $available,
        'total' => $fasiliti->kuantiti_total,
        'booked' => $totalBooked,
        'is_countable' => $fasiliti->is_countable
    ]);
}
```

### 2. Store Tempahan with Multiple Items
```php
public function store(Request $request)
{
    DB::beginTransaction();
    try {
        // 1. Create tempahan
        $tempahan = TempahanFasiliti::create([...]);
        
        // 2. Add items
        $totalHarga = 0;
        foreach ($request->items as $item) {
            $fasiliti = SenariFasiliti::find($item['fasiliti_id']);
            $quantity = $item['quantity'];
            
            // Check availability
            $available = $this->checkAvailabilityInternal($fasiliti->id, $request->tarikh_mula, $request->tarikh_tamat);
            if ($available < $quantity) {
                throw new \Exception("Insufficient quantity for {$fasiliti->nama_fasiliti}");
            }
            
            // Calculate price based on unit_tempoh
            $hargaPerUnit = $this->calculatePrice($fasiliti, $request->unit_tempoh);
            $subtotal = $hargaPerUnit * $quantity * $request->tempoh_sewa;
            
            // Create item
            TempahanFasilitiItem::create([
                'tempahan_fasiliti_id' => $tempahan->id,
                'senarai_fasiliti_id' => $fasiliti->id,
                'quantity' => $quantity,
                'harga_per_unit' => $hargaPerUnit,
                'subtotal' => $subtotal
            ]);
            
            $totalHarga += $subtotal;
        }
        
        // 3. Update tempahan total
        $tempahan->update([
            'harga_sewa' => $totalHarga,
            'jumlah_bayaran' => $totalHarga + $request->deposit
        ]);
        
        DB::commit();
        return redirect()->route('tempahan-fasiliti.index')->with('success', 'Tempahan berjaya!');
        
    } catch (\Exception $e) {
        DB::rollBack();
        return back()->with('error', $e->getMessage());
    }
}
```

---

## 📱 JAVASCRIPT LOGIC

### Real-time Availability Check
```javascript
// When date/time changes, refresh availability
$('#tarikh_mula, #tarikh_tamat').on('change', function() {
    refreshAllAvailability();
});

function refreshAllAvailability() {
    const tarikhMula = $('#tarikh_mula').val();
    const tarikhTamat = $('#tarikh_tamat').val();
    
    if (!tarikhMula || !tarikhTamat) return;
    
    // Refresh all fasiliti dropdowns
    $('.fasiliti-select').each(function() {
        const select = $(this);
        
        // Get all options
        select.find('option').each(function() {
            const option = $(this);
            const fasilitiId = option.val();
            
            if (!fasilitiId) return;
            
            // AJAX check availability
            $.get('/api/check-availability', {
                fasiliti_id: fasilitiId,
                tarikh_mula: tarikhMula,
                tarikh_tamat: tarikhTamat
            }, function(data) {
                if (data.is_countable) {
                    // Countable item - show quantity
                    option.text(`${option.data('name')} (${data.available} / ${data.total} available)`);
                    option.prop('disabled', data.available <= 0);
                    option.data('available', data.available);
                } else {
                    // Unique item - show available or not
                    if (data.available > 0) {
                        option.text(`${option.data('name')} (Available)`);
                        option.prop('disabled', false);
                    } else {
                        option.text(`${option.data('name')} (Not Available)`);
                        option.prop('disabled', true);
                    }
                }
            });
        });
    });
}

// When fasiliti selected, update quantity max
$('.fasiliti-select').on('change', function() {
    const selected = $(this).find(':selected');
    const available = selected.data('available');
    const isCountable = selected.data('type') === 'countable';
    
    const quantityInput = $(this).closest('.item-row').find('.quantity-input');
    const availabilityInfo = $(this).closest('.item-row').find('.availability-info');
    
    if (isCountable) {
        quantityInput.attr('max', available);
        quantityInput.prop('disabled', false);
        availabilityInfo.text(`Available: ${available} units`);
    } else {
        quantityInput.val(1);
        quantityInput.attr('max', 1);
        quantityInput.prop('disabled', true);
        availabilityInfo.text('Unique item');
    }
});

// Add new item row
$('#add-item').on('click', function() {
    const newRow = $('.item-row:first').clone();
    newRow.find('select').val('');
    newRow.find('input').val(1);
    $('#items-container').append(newRow);
});

// Remove item row
$(document).on('click', '.remove-item', function() {
    if ($('.item-row').length > 1) {
        $(this).closest('.item-row').remove();
    }
});
```

---

## 🎯 BENEFITS

### For Users:
- ✅ **Convenience** - Book multiple items in one go
- ✅ **Time Saving** - No need multiple bookings
- ✅ **Clear Pricing** - See total cost for all items
- ✅ **Real-time Info** - Know what's available instantly

### For Admin:
- ✅ **Better Tracking** - All items for one event in one record
- ✅ **Inventory Control** - Automatic stock management
- ✅ **Prevent Overbooking** - System checks availability
- ✅ **Accurate Reporting** - Better utilization reports

---

## 📋 IMPLEMENTATION CHECKLIST

### Phase 1: Database (Priority: HIGH)
- [ ] Create migration for `tempahan_fasiliti_items` table
- [ ] Update `senarai_fasiliti` table (add kuantiti_total, is_countable)
- [ ] Update `tempahan_fasiliti` table (remove single fasiliti_id)
- [ ] Create TempahanFasilitiItem model with relationships
- [ ] Seed sample data for testing

### Phase 2: Backend (Priority: HIGH)
- [ ] Create availability checking endpoint (AJAX)
- [ ] Update TempahanFasilitiController::store() for multiple items
- [ ] Update TempahanFasilitiController::update() for multiple items
- [ ] Add validation for quantity vs availability
- [ ] Update pricing calculation logic
- [ ] Update auto-create PembayaranSewa logic

### Phase 3: Frontend (Priority: HIGH)
- [ ] Update create.blade.php - dynamic item rows
- [ ] Update edit.blade.php - show/edit existing items
- [ ] Update show.blade.php - display all items in table
- [ ] Add JavaScript for dynamic item management
- [ ] Add JavaScript for real-time availability checking
- [ ] Add JavaScript for auto-calculate total price

### Phase 4: Testing (Priority: MEDIUM)
- [ ] Test unique item booking (Dewan)
- [ ] Test countable item booking (Kerusi, Meja)
- [ ] Test availability checking with overlapping dates
- [ ] Test overbooking prevention
- [ ] Test price calculation
- [ ] Test edit existing tempahan with items

### Phase 5: UI/UX Polish (Priority: LOW)
- [ ] Add loading indicators for AJAX calls
- [ ] Add confirmation dialogs
- [ ] Add success/error notifications
- [ ] Add item icons/images
- [ ] Improve mobile responsiveness

---

## 🚀 ESTIMATED EFFORT

**Total Time**: 8-12 hours
- Database: 1-2 hours
- Backend: 3-4 hours
- Frontend: 3-4 hours
- Testing: 1-2 hours

**Complexity**: Medium-High
**Risk**: Low (well-defined requirements)

---

## 💡 FUTURE ENHANCEMENTS

1. **Package Deals** - Pre-defined packages (e.g., "Wedding Package" = Dewan + 500 Kerusi + PA System)
2. **Recurring Bookings** - Book same items for multiple dates
3. **Waitlist** - Allow users to join waitlist if item not available
4. **Priority Booking** - VIP users get priority access
5. **Bulk Discount** - Discount for booking many items

---

## ❓ QUESTIONS FOR USER

1. **Deposit Calculation** - Deposit based on total price or per item?
2. **Cancellation** - Can user cancel individual items or must cancel whole booking?
3. **Partial Return** - If user returns some items early, refund partial amount?
4. **Damage Charges** - How to handle if items damaged?

---

**Status**: AWAITING APPROVAL
**Next Step**: Get user confirmation to proceed with implementation

Adakah anda setuju dengan design ini? Saya boleh teruskan dengan implementation jika anda approve.
