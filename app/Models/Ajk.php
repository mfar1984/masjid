<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\HasMasjidScope;

class Ajk extends Model
{
    use HasFactory, HasMasjidScope;

    protected $table = 'ajk';

    protected $fillable = [
        'nama',
        'no_ic',
        'telefon',
        'email',
        'alamat',
        'jantina',
        'jawatan',
        'jawatan_custom', // Custom jawatan for "Ahli Jawatankuasa"
        'urutan', // Urutan untuk struktur organisasi PDF
        'tarikh_lantikan',
        'tarikh_tamat',
        'tempoh_jawatan',
        'status',
        'is_archived',
        'archived_at',
        'archived_by',
        'ic_depan_path',
        'ic_belakang_path',
        'surat_lantikan_path',
        'gambar_path', // Gambar untuk carta organisasi
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
        'tarikh_lantikan' => 'date',
        'tarikh_tamat' => 'date',
        'tarikh_diluluskan' => 'datetime',
        'suspended_at' => 'datetime',
        'archived_at' => 'datetime',
        'is_archived' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // WAJIB: Relationship dengan Masjid
    public function masjid(): BelongsTo
    {
        return $this->belongsTo(Masjid::class);
    }

    /**
     * Get the user who created this AJK record.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated this AJK record.
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Scope a query to only include active AJK.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'Aktif');
    }

    /**
     * Scope a query to only include inactive AJK.
     */
    public function scopeInactive($query)
    {
        return $query->where('status', 'Tidak Aktif');
    }

    /**
     * Scope a query to only include archived AJK.
     */
    public function scopeArchived($query)
    {
        return $query->where('is_archived', true);
    }

    /**
     * Scope a query to exclude archived AJK.
     */
    public function scopeNotArchived($query)
    {
        return $query->where('is_archived', false);
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
     * Get jawatan options
     */
    public static function getJawatanOptions()
    {
        return [
            'Penasihat' => 'Penasihat',
            'Pengerusi' => 'Pengerusi',
            'Naib Pengerusi' => 'Naib Pengerusi',
            'Setiausaha' => 'Setiausaha',
            'Bendahari' => 'Bendahari',
            'Penolong Setiausaha' => 'Penolong Setiausaha',
            'Penolong Bendahari' => 'Penolong Bendahari',
            'Ahli Jawatankuasa' => 'Ahli Jawatankuasa',
            'Imam' => 'Imam',
            'Imam 1' => 'Imam 1',
            'Imam 2' => 'Imam 2',
            'Imam 3' => 'Imam 3',
            'Imam 4' => 'Imam 4',
            'Imam 5' => 'Imam 5',
            'Bilal' => 'Bilal',
            'Bilal 1' => 'Bilal 1',
            'Bilal 2' => 'Bilal 2',
            'Bilal 3' => 'Bilal 3',
            'Bilal 4' => 'Bilal 4',
            'Bilal 5' => 'Bilal 5',
            'Siak' => 'Siak',
        ];
    }

    /**
     * Get formatted tarikh lantikan.
     */
    public function getTarikhLantikanFormattedAttribute()
    {
        return $this->tarikh_lantikan ? $this->tarikh_lantikan->format('d/m/Y') : '--';
    }

    /**
     * Get formatted tarikh tamat.
     */
    public function getTarikhTamatFormattedAttribute()
    {
        return $this->tarikh_tamat ? $this->tarikh_tamat->format('d/m/Y') : '--';
    }

    /**
     * Get formatted tarikh kemaskini.
     */
    public function getTarikhKemaskiniFormattedAttribute()
    {
        return $this->updated_at->format('d/m/Y');
    }

    /**
     * Get full jawatan display (with custom if applicable)
     */
    public function getJawatanFullAttribute()
    {
        if ($this->jawatan === 'Ahli Jawatankuasa' && $this->jawatan_custom) {
            return 'Ahli Jawatankuasa - ' . $this->jawatan_custom;
        }
        return $this->jawatan;
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
        } catch (\Exception $e) {
            return '--';
        }
    }
}
