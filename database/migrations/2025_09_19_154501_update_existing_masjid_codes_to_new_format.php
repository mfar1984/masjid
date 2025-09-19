<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Masjid;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update all existing masjid codes to new 6-character format
        $masjids = Masjid::whereNotNull('kod_masjid')->get();

        foreach ($masjids as $masjid) {
            // Store old code for reference
            $oldCode = $masjid->kod_masjid;

            // Generate new 6-character unique code
            $newCode = $this->generateUniqueCode();

            // Update with new code
            $masjid->update(['kod_masjid' => $newCode]);

            // Log the change for reference
            \Log::info("Updated Masjid Code: {$masjid->nama} | Old: {$oldCode} | New: {$newCode}");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Note: This migration is not easily reversible as we don't store old codes
        // If needed, restore from backup or re-run seeder
        \Log::warning('Masjid code migration rollback attempted - not reversible without backup');
    }

    /**
     * Generate a unique 6-character code with uppercase letters and numbers
     */
    private function generateUniqueCode()
    {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $maxAttempts = 100;
        $attempts = 0;

        do {
            $code = '';
            for ($i = 0; $i < 6; $i++) {
                $code .= $characters[random_int(0, strlen($characters) - 1)];
            }

            $attempts++;

            // Check if code already exists
            $exists = Masjid::where('kod_masjid', $code)->exists();

        } while ($exists && $attempts < $maxAttempts);

        if ($attempts >= $maxAttempts) {
            throw new \Exception('Unable to generate unique kod_masjid after ' . $maxAttempts . ' attempts');
        }

        return $code;
    }
};
