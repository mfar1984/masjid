<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\HasMasjidScope;

class Kariah extends Model
{
    use HasFactory, HasMasjidScope;

    protected $table = 'kariah';

    protected $fillable = [
        'nama',
        'no_ic',
        'telefon',
        'bangsa',
        'jantina',
        'tarikh_keahlian',
        'status',
        'alamat',
        'email',
        'ic_depan_path',
        'ic_belakang_path',
        'masjid_id', // WAJIB untuk data isolation
        'created_by',
        'updated_by',
        // Workflow fields
        'diluluskan_oleh',
        'tarikh_diluluskan',
        'catatan_kelulusan',
        'suspended_at',
        'suspended_by',
    ];

    protected $casts = [
        'tarikh_keahlian' => 'date',
        'tarikh_diluluskan' => 'datetime',
        'suspended_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // WAJIB: Relationship dengan Masjid
    public function masjid(): BelongsTo
    {
        return $this->belongsTo(Masjid::class);
    }

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
     * Get jantina options
     */
    public static function getJantinaOptions()
    {
        return [
            'Lelaki' => 'Lelaki',
            'Perempuan' => 'Perempuan',
            'Tidak Dinyatakan' => 'Tidak Dinyatakan'
        ];
    }

    /**
     * Get status options
     */
    public static function getStatusOptions()
    {
        return [
            'Aktif' => 'Aktif',
            'Tidak Aktif' => 'Tidak Aktif',
            'Menunggu' => 'Menunggu',
            'Ditolak' => 'Ditolak',
            'Digantung' => 'Digantung'
        ];
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
