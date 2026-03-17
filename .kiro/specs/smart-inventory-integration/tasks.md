# Implementation Plan

- [x] 1. Database Schema Updates
- [x] 1.1 Create migration for tempahan_fasiliti lokasi destinasi fields
  - Add is_lokasi_luaran, lokasi_destinasi, nama_tempat_luaran, alamat_luaran_1, alamat_luaran_2, poskod_luaran, bandar_luaran, negeri_luaran
  - Add status_pemulangan enum field
  - Add tarikh_sebenar_pulangan datetime field
  - _Requirements: 4.4_

- [x] 1.2 Create migration for pergerakan_aset tempahan reference fields
  - Add tempahan_fasiliti_id foreign key (nullable)
  - Add tempahan_fasiliti_item_id foreign key (nullable)
  - Add kuantiti integer field
  - _Requirements: 1.4_

- [x] 1.3 Create migration for senarai_fasiliti kuantiti field
  - Add kuantiti_total integer field with default 1
  - Update existing records to set kuantiti_total = 1
  - _Requirements: 2.1_

- [x] 2. Model Updates
- [x] 2.1 Update TempahanFasiliti model
  - Add fillable fields for lokasi destinasi
  - Add relationship pergerakanAset() hasMany through items
  - Add accessor getStatusPemulanganAttribute()
  - _Requirements: 1.4, 8.1_

- [x] 2.2 Update PergerakanAset model
  - Add fillable fields for tempahan reference and kuantiti
  - Add relationship tempahanFasiliti() belongsTo
  - Add relationship tempahanFasilitiItem() belongsTo
  - _Requirements: 1.4, 8.2_

- [x] 2.3 Update SenariFasiliti model
  - Add fillable field kuantiti_total
  - Update checkAvailability() method to include pergerakan
  - Add getKuantitiTersedia() method
  - _Requirements: 2.2, 2.3_

- [x] 3. Create InventoryService
- [x] 3.1 Create InventoryService class
  - Create app/Services/InventoryService.php
  - Implement getAvailableQuantity() method
  - Implement createPergerakanFromTempahan() method
  - Implement processReturn() method
  - Implement processTempahanReturn() method
  - Implement markLateReturns() method
  - _Requirements: 1.1, 2.2, 2.3, 3.2, 3.3, 3.4, 7.1_

- [ ] 3.2 Write property test for availability calculation
  - **Property 2: Availability Calculation Accuracy**
  - **Validates: Requirements 2.1, 2.2, 2.3**

- [ ] 3.3 Write property test for return round-trip
  - **Property 4: Return Round-Trip Consistency**
  - **Validates: Requirements 3.2, 3.3, 3.4, 3.5**

- [ ] 4. Checkpoint - Ensure all tests pass
  - Ensure all tests pass, ask the user if questions arise.

- [x] 5. Controller Updates
- [x] 5.1 Update TempahanFasilitiController store method
  - Add validation for lokasi destinasi fields
  - Save lokasi destinasi data
  - _Requirements: 4.4_

- [x] 5.2 Update TempahanFasilitiController lulus method
  - Inject InventoryService
  - Call createPergerakanFromTempahan() on approval
  - _Requirements: 1.1, 1.2, 1.3_

- [ ] 5.3 Write property test for tempahan approval creates pergerakan
  - **Property 1: Tempahan Approval Creates Correct Pergerakan**
  - **Validates: Requirements 1.1, 1.2, 1.3, 1.4, 8.3**

- [x] 5.4 Add TempahanFasilitiController pulang method
  - Create route POST /tempahan-fasiliti/{id}/pulang
  - Validate kondisi_selepas and catatan
  - Call InventoryService processTempahanReturn()
  - Update tempahan status to Selesai
  - _Requirements: 3.2, 3.3, 3.4, 3.5_

- [x] 5.5 Add TempahanFasilitiController checkAvailability API
  - Create route GET /api/fasiliti/{id}/availability
  - Accept tarikh_mula and tarikh_tamat parameters
  - Return available quantity as JSON
  - _Requirements: 2.1_

- [ ] 5.6 Write property test for overbooking prevention
  - **Property 3: Overbooking Prevention**
  - **Validates: Requirements 2.4**

- [ ] 5.7 Update PergerakanAsetController pulang method
  - Check if pergerakan has tempahan reference
  - Update tempahan status_pemulangan if all items returned
  - _Requirements: 3.5_

- [ ] 6. Checkpoint - Ensure all tests pass
  - Ensure all tests pass, ask the user if questions arise.

- [x] 7. View Updates - Tempahan Form
- [x] 7.1 Update tempahan-fasiliti/create.blade.php
  - Add Section 3: Lokasi Destinasi
  - Add radio buttons for is_lokasi_luaran (Dalaman/Luaran)
  - Add conditional fields for dalaman (dropdown) and luaran (full address)
  - Add AJAX to fetch available quantity on fasiliti select
  - Display available quantity badge
  - _Requirements: 4.1, 4.2, 4.3, 2.1_

- [ ] 7.2 Update tempahan-fasiliti/edit.blade.php
  - Add same Lokasi Destinasi section as create
  - Pre-populate existing values
  - _Requirements: 4.1, 4.2, 4.3_

- [x] 8. View Updates - Tempahan Index
- [x] 8.1 Update tempahan-fasiliti/index.blade.php
  - Add column Status Pemulangan after Status Tempahan
  - Add badge styling (Belum Pulang=orange, Sudah Pulang=green, Lewat=red)
  - Add filter dropdown for status_pemulangan
  - Add icon "assignment_return" for pulang action (for Lulus status)
  - _Requirements: 5.1, 5.2, 5.3, 5.4, 6.1_

- [x] 8.2 Add pulang modal to tempahan-fasiliti/index.blade.php
  - Create modal with kondisi_selepas dropdown and catatan textarea
  - Add JavaScript to handle modal open/submit
  - _Requirements: 6.2, 6.3_

- [ ] 8.3 Write property test for filter correctness
  - **Property 7: Filter Correctness**
  - **Validates: Requirements 5.5**

- [x] 9. View Updates - Tempahan Show
- [x] 9.1 Update tempahan-fasiliti/show.blade.php
  - Add Section: Lokasi Destinasi (display saved data)
  - Add Section: Pergerakan Aset Berkaitan (list related pergerakan)
  - Add Pulangkan button with modal (for Lulus status, Belum Pulang)
  - _Requirements: 8.1, 6.1, 6.2_

- [ ] 9.2 Write property test for bidirectional link
  - **Property 6: Tempahan-Pergerakan Bidirectional Link**
  - **Validates: Requirements 8.1, 8.2**

- [x] 10. View Updates - Pergerakan Show
- [x] 10.1 Update pergerakan-aset/show.blade.php
  - Add link to tempahan if tempahan_fasiliti_id exists
  - Display "Dicipta dari Tempahan: TP-XXXX-XXXX" with link
  - _Requirements: 8.2_

- [x] 11. Late Detection Scheduler
- [x] 11.1 Create scheduled command for late detection
  - Create app/Console/Commands/MarkLateReturns.php
  - Call InventoryService markLateReturns()
  - Register in app/Console/Kernel.php to run daily
  - _Requirements: 7.1_

- [ ] 11.2 Write property test for late detection
  - **Property 5: Late Detection Accuracy**
  - **Validates: Requirements 7.1**

- [x] 12. Update Statistics
- [x] 12.1 Update TempahanFasilitiController index statistics
  - Add count for Lewat Pulang tempahan
  - Add count for Belum Pulang tempahan
  - _Requirements: 7.2_

- [ ] 13. Final Checkpoint - Ensure all tests pass
  - Ensure all tests pass, ask the user if questions arise.
