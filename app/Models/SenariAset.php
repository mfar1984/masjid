<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasMasjidScope;

class SenariAset extends Model
{
    use SoftDeletes, HasMasjidScope;

    protected $table = 'senarai_aset';

    protected $fillable = [
        'masjid_id',
        'no_aset',
        'kategori_aset_id',
        'nama_aset',
        'kod_aset',
        'jenis_aset',
        'tarikh_perolehan',
        'cara_perolehan',
        'pembekal',
        'no_invois',
        'harga_perolehan',
        'jenama',
        'model',
        'no_siri',
        'warna',
        'saiz',
        'spesifikasi',
        'lokasi_semasa',
        'lokasi_terperinci',
        'tempoh_jaminan',
        'tarikh_tamat_jaminan',
        'no_polisi_insurans',
        'syarikat_insurans',
        'tarikh_tamat_insurans',
        'status_aset',
        'kondisi_aset',
        'gambar_aset',
        'invois_path',
        'warranty_card_path',
        'manual_path',
        'insurans_path',
        'dokumen_lain',
        'catatan',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'tarikh_perolehan' => 'date',
        'tarikh_tamat_jaminan' => 'date',
        'tarikh_tamat_insurans' => 'date',
        'harga_perolehan' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function masjid()
    {
        return $this->belongsTo(Masjid::class);
    }

    public function kategoriAset()
    {
        return $this->belongsTo(KategoriAset::class);
    }

    public function pergerakanAset()
    {
        return $this->hasMany(PergerakanAset::class, 'senarai_aset_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function deletedBy()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }

    // Scopes
    public function scopeAktif($query)
    {
        return $query->where('status_aset', 'Aktif');
    }

    public function scopeRosak($query)
    {
        return $query->where('status_aset', 'Rosak');
    }

    public function scopeDipinjam($query)
    {
        return $query->where('status_aset', 'Dipinjam');
    }

    public function scopeDisewa($query)
    {
        return $query->where('status_aset', 'Disewa');
    }

    public function scopeByKategori($query, $kategoriId)
    {
        return $query->where('kategori_aset_id', $kategoriId);
    }

    public function scopeByLokasi($query, $lokasi)
    {
        return $query->where('lokasi_semasa', $lokasi);
    }

    // Accessors
    public function getUmurAsetAttribute()
    {
        if (!$this->tarikh_perolehan) {
            return null;
        }
        
        return $this->tarikh_perolehan->diffInYears(now());
    }

    public function getIsWarrantyValidAttribute()
    {
        if (!$this->tarikh_tamat_jaminan) {
            return false;
        }
        
        return $this->tarikh_tamat_jaminan->isFuture();
    }

    public function getIsInsuranceValidAttribute()
    {
        if (!$this->tarikh_tamat_insurans) {
            return false;
        }
        
        return $this->tarikh_tamat_insurans->isFuture();
    }

    /**
     * Auto-generate kod_aset (globally unique across all masjids)
     * Format: {MASJID_PREFIX}-{KATEGORI_KOD}-{TAHUN}-{NOMBOR}
     * Example: PUTRA-KRS-2025-0001
     * 
     * @param int $masjidId
     * @param string $masjidPrefix - Short prefix for masjid (e.g., PUTRA, NEGERI)
     * @param string $kategoriKod - Category code (e.g., KRS for Kerusi, MJA for Meja)
     * @return string
     */
    public static function generateKodAset($masjidId, $masjidPrefix, $kategoriKod)
    {
        $year = date('Y');
        $prefix = strtoupper($masjidPrefix) . '-' . strtoupper($kategoriKod) . '-' . $year . '-';

        // Search globally because kod_aset has global unique constraint
        // Use withoutGlobalScopes() to bypass HasMasjidScope trait
        $lastAset = self::withoutGlobalScopes()
            ->withTrashed()
            ->where('kod_aset', 'like', $prefix . '%')
            ->orderByRaw('CAST(SUBSTRING(kod_aset, ?) AS UNSIGNED) DESC', [strlen($prefix) + 1])
            ->first();

        $nextNumber = 1;
        if ($lastAset && $lastAset->kod_aset) {
            $numberPart = substr($lastAset->kod_aset, strlen($prefix));
            $nextNumber = intval($numberPart) + 1;
        }

        return $prefix . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Get the next available number for kod_aset with given prefix
     * Used for bulk asset creation
     */
    public static function getNextKodAsetNumber($masjidId, $prefix)
    {
        // Find the highest existing kod_aset with this prefix globally
        $lastAset = self::withoutGlobalScopes()
            ->withTrashed()
            ->where('kod_aset', 'like', $prefix . '%')
            ->orderByRaw('CAST(SUBSTRING(kod_aset, ?) AS UNSIGNED) DESC', [strlen($prefix) + 1])
            ->first();

        if (!$lastAset || !$lastAset->kod_aset) {
            return 1;
        }

        // Extract the number part after the prefix
        $numberPart = substr($lastAset->kod_aset, strlen($prefix));
        $lastNumber = intval($numberPart);

        return $lastNumber + 1;
    }

    /**
     * Legacy method - kept for backward compatibility
     * Now generates kod_aset instead of no_aset
     */
    public static function generateNoAset($masjidId)
    {
        $year = date('Y');
        $prefix = 'AST-' . $year . '-';

        $lastAset = self::withoutGlobalScopes()
            ->withTrashed()
            ->where('no_aset', 'like', $prefix . '%')
            ->orderByRaw('CAST(SUBSTRING(no_aset, ?) AS UNSIGNED) DESC', [strlen($prefix) + 1])
            ->first();

        $nextNumber = 1;
        if ($lastAset && $lastAset->no_aset) {
            $numberPart = substr($lastAset->no_aset, strlen($prefix));
            $nextNumber = intval($numberPart) + 1;
        }

        return $prefix . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }
}
