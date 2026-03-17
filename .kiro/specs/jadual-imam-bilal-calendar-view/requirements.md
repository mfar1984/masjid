# Requirements Document

## Introduction

Penambahbaikan paparan Jadual Imam & Bilal daripada format jadual biasa (row per rekod) kepada format kalendar bulanan yang lebih teratur dan mudah dibaca. Format baru akan memaparkan tarikh sebagai row header (1-31), waktu solat sebagai column header, dengan legend untuk menunjukkan nama imam/bilal.

## Glossary

- **Jadual Imam Bilal**: Sistem pengurusan jadual tugas imam dan bilal untuk solat di masjid
- **Waktu Solat**: Waktu solat fardhu (Subuh, Zohor, Asar, Maghrib, Isyak) dan Jumaat
- **Legend**: Petunjuk warna/kod yang menunjukkan nama imam dan bilal
- **Calendar View**: Paparan dalam format kalendar dengan tarikh sebagai baris dan waktu solat sebagai lajur
- **Cell**: Sel dalam jadual yang menunjukkan imam/bilal untuk tarikh dan waktu tertentu

## Requirements

### Requirement 1

**User Story:** As a masjid administrator, I want to view the imam and bilal schedule in a monthly calendar format, so that I can easily see the entire month's schedule at a glance.

#### Acceptance Criteria

1. WHEN a user views the jadual imam bilal page THEN the system SHALL display a calendar grid with dates (1-31) as row headers on the left side
2. WHEN displaying the calendar grid THEN the system SHALL show waktu solat (Subuh, Zohor, Asar, Maghrib, Isyak, Jumaat) as column headers at the top
3. WHEN a date falls on a Friday THEN the system SHALL display the Jumaat column for that row
4. WHEN a date does not fall on a Friday THEN the system SHALL hide or grey out the Jumaat column for that row
5. WHEN displaying schedule data THEN the system SHALL show imam and bilal names in each cell corresponding to the date and waktu solat

### Requirement 2

**User Story:** As a masjid administrator, I want to see a legend showing all imam and bilal names with color codes, so that I can quickly identify who is assigned to each slot.

#### Acceptance Criteria

1. WHEN displaying the calendar view THEN the system SHALL show a legend section listing all unique imam names with assigned colors
2. WHEN displaying the calendar view THEN the system SHALL show a legend section listing all unique bilal names with assigned colors
3. WHEN displaying cell content THEN the system SHALL use the corresponding color from the legend to highlight imam names
4. WHEN displaying cell content THEN the system SHALL use the corresponding color from the legend to differentiate bilal names
5. WHEN an imam or bilal has status "Ganti" THEN the system SHALL display the replacement name with a visual indicator

### Requirement 3

**User Story:** As a masjid administrator, I want to filter the calendar view by month and year, so that I can view schedules for different periods.

#### Acceptance Criteria

1. WHEN a user selects a month from the filter THEN the system SHALL update the calendar to show only that month's schedule
2. WHEN a user selects a year from the filter THEN the system SHALL update the calendar to show the selected year's data
3. WHEN the page loads without filter parameters THEN the system SHALL default to the current month and year
4. WHEN changing month/year filter THEN the system SHALL adjust the number of date rows to match the selected month (28-31 days)

### Requirement 4

**User Story:** As a masjid administrator, I want the calendar view to be responsive and printable, so that I can use it on different devices and print physical copies.

#### Acceptance Criteria

1. WHEN viewing on desktop THEN the system SHALL display the full calendar grid with all columns visible
2. WHEN viewing on mobile THEN the system SHALL provide a scrollable horizontal view or alternative compact layout
3. WHEN printing the calendar THEN the system SHALL format the output to fit on standard paper sizes
4. WHEN printing THEN the system SHALL include the legend and month/year header on the printed output

### Requirement 5

**User Story:** As a masjid administrator, I want to see visual indicators for schedule status, so that I can quickly identify completed, pending, or cancelled schedules.

#### Acceptance Criteria

1. WHEN a schedule has status "Selesai" THEN the system SHALL display a green indicator or checkmark
2. WHEN a schedule has status "Dijadual" THEN the system SHALL display the default styling without special indicator
3. WHEN a schedule has status "Batal" THEN the system SHALL display a red indicator or strikethrough
4. WHEN a schedule has status "Ganti" THEN the system SHALL display an orange indicator with replacement name
