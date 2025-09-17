<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Kariah extends Model
{
    use HasFactory;

    protected $table = 'kariah';

    protected $fillable = [
        'nama',
        'no_ic',
        'telefon',
        'bangsa',
        'tarikh_keahlian',
        'status',
        'zon',
        'alamat',
        'email',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'tarikh_keahlian' => 'date',
    ];

    /**
     * Get the user who created this kariah record.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated this kariah record.
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Scope a query to only include active kariah.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'Aktif');
    }

    /**
     * Scope a query to only include inactive kariah.
     */
    public function scopeInactive($query)
    {
        return $query->where('status', 'Tidak Aktif');
    }

    /**
     * Scope a query to filter by zone.
     */
    public function scopeByZone($query, $zone)
    {
        return $query->where('zon', $zone);
    }

    /**
     * Get formatted tarikh keahlian.
     */
    public function getTarikhKeahlianFormattedAttribute()
    {
        return $this->tarikh_keahlian->format('d/m/Y');
    }

    /**
     * Get formatted tarikh kemaskini.
     */
    public function getTarikhKemaskiniFormattedAttribute()
    {
        return $this->updated_at->format('d/m/Y');
    }

    /**
     * Get age calculated from IC number.
     */
    public function getUmurAttribute()
    {
        if (!$this->no_ic || strlen($this->no_ic) < 6) {
            return '--';
        }

        try {
            // Extract year from IC (first 2 digits)
            $year = substr($this->no_ic, 0, 2);
            
            // Determine century based on year
            $currentYear = date('Y');
            $currentCentury = (int)($currentYear / 100);
            $currentYearLastTwo = $currentYear % 100;
            
            if ($year > $currentYearLastTwo) {
                // Year is in previous century (e.g., 90 = 1990)
                $fullYear = ($currentCentury - 1) . $year;
            } else {
                // Year is in current century (e.g., 05 = 2005)
                $fullYear = $currentCentury . $year;
            }
            
            // Calculate age
            $age = $currentYear - $fullYear;
            
            return $age > 0 ? $age . ' tahun' : '--';
        } catch (Exception $e) {
            return '--';
        }
    }
}
