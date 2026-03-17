# Implementation Plan

- [x] 1. Update Controller to support calendar data structure
  - [x] 1.1 Add helper method `transformToCalendarData()` in JadualImamBilalController ✅
    - Create method to transform flat jadual list to calendar structure indexed by date and waktu
    - Extract unique imam and bilal names for legend
    - _Requirements: 1.5, 2.1, 2.2_
  - [x] 1.2 Add helper method `assignColors()` for legend color assignment ✅
    - Define color palette array with 10 distinct colors
    - Assign colors to unique names cyclically
    - _Requirements: 2.3, 2.4_
  - [x] 1.3 Modify `exportPdf()` method to return calendar-formatted data ✅
    - Calculate days in selected month
    - Build calendar data structure with schedules, legend, and metadata
    - Pass new data structure to view
    - _Requirements: 1.1, 3.1, 3.2, 3.3, 3.4_
  - [ ] 1.4 Write property test for calendar data transformation
    - **Property 1: Calendar rows match days in month**
    - **Validates: Requirements 1.1, 3.4**
  - [ ] 1.5 Write property test for filter functionality
    - **Property 6: Filter functionality**
    - **Validates: Requirements 3.1, 3.2**

- [x] 2. Create calendar view components (Inline in print.blade.php)
  - [x] 2.1 Legend section implemented inline ✅
    - Display imam legend with colored circle indicators
    - Display bilal legend with colored square indicators
    - _Requirements: 2.1, 2.2, 5.1, 5.2, 5.3, 5.4_
  - [x] 2.2 Calendar cell implemented inline ✅
    - Display imam name with assigned color
    - Display bilal name with assigned color
    - Show status indicator based on status_imam and status_bilal
    - Handle "Ganti" status with replacement name display
    - _Requirements: 1.5, 2.3, 2.4, 2.5, 5.1, 5.2, 5.3, 5.4_
  - [ ] 2.3 Write property test for status-based styling
    - **Property 7: Status-based styling**
    - **Validates: Requirements 5.1, 5.2, 5.3, 5.4, 2.5**

- [ ] 3. Update main index view with calendar grid
  - [ ] 3.1 Replace existing table with calendar grid layout
    - Create table with tarikh (1-31) as row headers
    - Create waktu solat (Subuh, Zohor, Asar, Maghrib, Isyak, Jumaat) as column headers
    - Integrate x-calendar-cell component for each cell
    - Handle Friday column visibility based on date
    - _Requirements: 1.1, 1.2, 1.3, 1.4_
  - [ ] 3.2 Add legend section above calendar grid
    - Integrate x-calendar-legend component
    - Position legend for easy reference
    - _Requirements: 2.1, 2.2_
  - [ ] 3.3 Update filter section for month/year selection
    - Keep existing month/year dropdowns
    - Ensure filter updates calendar view correctly
    - _Requirements: 3.1, 3.2, 3.3_
  - [ ] 3.4 Write property test for Friday column visibility
    - **Property 2: Friday column visibility**
    - **Validates: Requirements 1.3, 1.4**
  - [ ] 3.5 Write property test for schedule data placement
    - **Property 3: Schedule data placement**
    - **Validates: Requirements 1.5**

- [ ] 4. Checkpoint - Ensure all tests pass
  - Ensure all tests pass, ask the user if questions arise.

- [x] 5. Add responsive design and print support
  - [x] 5.1 Add responsive CSS for mobile view ✅
    - Make calendar horizontally scrollable on mobile
    - Ensure legend is visible and readable on small screens
    - _Requirements: 4.1, 4.2_
  - [x] 5.2 Update print view (print.blade.php) with calendar format ✅
    - Format calendar grid for A3 Landscape paper size
    - Include legend and month/year header with Hijri date
    - Bahasa Melayu day names (Isnin, Selasa, etc.)
    - Friday rows highlighted in yellow
    - _Requirements: 4.3, 4.4_

- [ ] 6. Final integration and cleanup
  - [ ] 6.1 Test calendar view with existing data
    - Verify all existing jadual records display correctly
    - Test with different months and years
    - Verify action buttons (Tambah, Auto-Generate, Cetak) still work
    - _Requirements: All_
  - [ ] 6.2 Write property test for legend completeness
    - **Property 4: Legend completeness**
    - **Validates: Requirements 2.1, 2.2**
  - [ ] 6.3 Write property test for color consistency
    - **Property 5: Color consistency**
    - **Validates: Requirements 2.3, 2.4**

- [ ] 7. Final Checkpoint - Ensure all tests pass
  - Ensure all tests pass, ask the user if questions arise.

## Implementation Status

### ✅ COMPLETED (Print View - export-pdf)
- Calendar grid layout with dates (1-31) as rows
- Waktu solat (Subuh, Zohor, Asar, Maghrib, Isyak) as columns
- Legend showing all AJK members with color codes
- Bahasa Melayu day names (Isnin, Selasa, Rabu, Khamis, Jumaat, Sabtu, Ahad)
- Hijri/Islamic date display
- A3 Landscape print format
- Friday rows highlighted in yellow
- Status indicators (Batal = strikethrough, Ganti = orange)

### ⏳ PENDING
- Apply same calendar view to index.blade.php (main page)
- Property tests

### ⚠️ NOTE
If calendar cells appear empty, it means no schedule data exists for that month. Use "Auto-Generate" function to create schedule data.
