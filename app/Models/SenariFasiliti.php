<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasMasjidScope;

class SenariFasiliti extends Model
{
    use SoftDeletes, HasMasjidScope;

    protected $table = 'senarai_fasiliti';

    protected $fillable = [
        'masjid_id',
        'kod_fasiliti',
        'nama_fasiliti',
        'jenis_fasiliti',
        'kategori_fasiliti',
        'senarai_aset_id',
        'kapasiti_maksimum',
        'kuantiti_total',
        'luas_kawasan',
        'kemudahan',
        'spesifikasi',
        'harga_sewa_sejam',
        'harga_sewa_sehari',
        'harga_sewa_separuh_hari',
        'deposit_diperlukan',
        'syarat_tempahan',
        'peraturan_penggunaan',
        'had_minimum_tempahan',
        'had_maksimum_tempahan',
        'gambar_fasiliti',
        'dokumen_peraturan',
        'status_fasiliti',
        'catatan',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'harga_sewa_sejam' => 'decimal:2',
        'harga_sewa_sehari' => 'decimal:2',
        'harga_sewa_separuh_hari' => 'decimal:2',
        'deposit_diperlukan' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function masjid()
    {
        return $this->belongsTo(Masjid::class);
    }

    public function senariAset()
    {
        return $this->belongsTo(SenariAset::class, 'senarai_aset_id');
    }

    public function tempahanFasiliti()
    {
        return $this->hasMany(TempahanFasiliti::class, 'senarai_fasiliti_id');
    }

    public function tempahanItems()
    {
        return $this->hasMany(TempahanFasilitiItem::class, 'senarai_fasiliti_id');
    }

    public function pembayaranSewa()
    {
        return $this->hasMany(PembayaranSewa::class, 'senarai_fasiliti_id');
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
    public function scopeTersedia($query)
    {
        return $query->where('status_fasiliti', 'Tersedia');
    }

    public function scopeByJenis($query, $jenis)
    {
        return $query->where('jenis_fasiliti', $jenis);
    }

    // Methods
    public static function generateKodFasiliti($masjidId)
    {
        $year = date('Y');
        $lastFasiliti = self::where('masjid_id', $masjidId)
            ->where('kod_fasiliti', 'like', "FS-{$year}-%")
            ->orderBy('kod_fasiliti', 'desc')
            ->first();

        if ($lastFasiliti) {
            $lastNumber = (int) substr($lastFasiliti->kod_fasiliti, -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return "FS-{$year}-" . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Check availability for specific date/time range
     * Returns available quantity
     * Includes both tempahan items and pergerakan aset not from tempahan
     */
    public function checkAvailability($tarikhMula, $tarikhTamat, $excludeTempahanId = null)
    {
        // 1. Calculate booked from tempahan items
        $tempahanQuery = \DB::table('tempahan_fasiliti_items')
            ->join('tempahan_fasiliti', 'tempahan_fasiliti_items.tempahan_fasiliti_id', '=', 'tempahan_fasiliti.id')
            ->where('tempahan_fasiliti_items.senarai_fasiliti_id', $this->id)
            ->where('tempahan_fasiliti_items.status_item', 'Aktif')
            ->whereIn('tempahan_fasiliti.status_tempahan', ['Baharu', 'Dalam Semakan', 'Lulus'])
            ->where(function($q) use ($tarikhMula, $tarikhTamat) {
                $q->whereBetween('tempahan_fasiliti.tarikh_mula', [$tarikhMula, $tarikhTamat])
                  ->orWhereBetween('tempahan_fasiliti.tarikh_tamat', [$tarikhMula, $tarikhTamat])
                  ->orWhere(function($q2) use ($tarikhMula, $tarikhTamat) {
                      $q2->where('tempahan_fasiliti.tarikh_mula', '<=', $tarikhMula)
                         ->where('tempahan_fasiliti.tarikh_tamat', '>=', $tarikhTamat);
                  });
            });

        if ($excludeTempahanId) {
            $tempahanQuery->where('tempahan_fasiliti.id', '!=', $excludeTempahanId);
        }

        $totalBookedFromTempahan = $tempahanQuery->sum('tempahan_fasiliti_items.quantity');

        // 2. Calculate booked from pergerakan aset (not from tempahan)
        $pergerakanBooked = 0;
        if ($this->senarai_aset_id) {
            $pergerakanBooked = \DB::table('pergerakan_aset')
                ->where('senarai_aset_id', $this->senarai_aset_id)
                ->whereNull('tempahan_fasiliti_id') // Not from tempahan
                ->whereIn('status_pulangan', ['Belum Pulang', 'Lewat'])
                ->whereNull('deleted_at')
                ->sum('kuantiti');
        }

        $available = $this->kuantiti_total - $totalBookedFromTempahan - $pergerakanBooked;

        return max(0, $available);
    }

    /**
     * Get kuantiti tersedia (alias for checkAvailability with current date)
     */
    public function getKuantitiTersedia($tarikhMula = null, $tarikhTamat = null)
    {
        $tarikhMula = $tarikhMula ?? now();
        $tarikhTamat = $tarikhTamat ?? now()->addDay();
        
        return $this->checkAvailability($tarikhMula, $tarikhTamat);
    }

    /**
     * Get price based on unit tempoh
     */
    public function getPriceByUnit($unitTempoh)
    {
        switch ($unitTempoh) {
            case 'Jam':
                return $this->harga_sewa_sejam ?? 0;
            case 'Separuh Hari':
                return $this->harga_sewa_separuh_hari ?? 0;
            case 'Hari':
                return $this->harga_sewa_sehari ?? 0;
            default:
                return 0;
        }
    }
}
