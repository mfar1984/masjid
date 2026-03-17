<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasMasjidScope;

class TempahanFasiliti extends Model
{
    use SoftDeletes, HasMasjidScope;

    protected $table = 'tempahan_fasiliti';

    protected $fillable = [
        'masjid_id',
        'no_tempahan',
        'senarai_fasiliti_id',
        'nama_penyewa',
        'no_ic_penyewa',
        'no_telefon_penyewa',
        'emel_penyewa',
        'alamat_penyewa_1',
        'alamat_penyewa_2',
        'poskod_penyewa',
        'bandar_penyewa',
        'negeri_penyewa',
        'organisasi_penyewa',
        'tarikh_tempahan',
        'tarikh_mula',
        'tarikh_tamat',
        'tempoh_sewa',
        'unit_tempoh',
        'tujuan_tempahan',
        'jenis_acara',
        'bilangan_jangka_peserta',
        'harga_sewa',
        'deposit',
        'jumlah_bayaran',
        'surat_permohonan_path',
        'salinan_ic_path',
        'surat_sokongan_path',
        'dokumen_lain',
        'status_tempahan',
        'disemak_oleh',
        'tarikh_disemak',
        'catatan_semakan',
        'diluluskan_oleh',
        'tarikh_diluluskan',
        'catatan_kelulusan',
        // Lokasi Destinasi fields
        'is_lokasi_luaran',
        'lokasi_destinasi',
        'nama_tempat_luaran',
        'alamat_luaran_1',
        'alamat_luaran_2',
        'poskod_luaran',
        'bandar_luaran',
        'negeri_luaran',
        // Status Pemulangan
        'status_pemulangan',
        'tarikh_sebenar_pulangan',
        'ditolak_oleh',
        'tarikh_ditolak',
        'sebab_tolak',
        'dibatalkan_oleh',
        'tarikh_dibatalkan',
        'sebab_batal',
        'catatan',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'tarikh_tempahan' => 'date',
        'tarikh_mula' => 'datetime',
        'tarikh_tamat' => 'datetime',
        'tarikh_disemak' => 'datetime',
        'tarikh_diluluskan' => 'datetime',
        'tarikh_ditolak' => 'datetime',
        'tarikh_dibatalkan' => 'datetime',
        'tarikh_sebenar_pulangan' => 'datetime',
        'harga_sewa' => 'decimal:2',
        'deposit' => 'decimal:2',
        'jumlah_bayaran' => 'decimal:2',
        'is_lokasi_luaran' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function masjid()
    {
        return $this->belongsTo(Masjid::class);
    }

    public function senariFasiliti()
    {
        return $this->belongsTo(SenariFasiliti::class, 'senarai_fasiliti_id');
    }

    public function items()
    {
        return $this->hasMany(TempahanFasilitiItem::class, 'tempahan_fasiliti_id');
    }

    public function activeItems()
    {
        return $this->hasMany(TempahanFasilitiItem::class, 'tempahan_fasiliti_id')
            ->where('status_item', 'Aktif');
    }

    public function pembayaranSewa()
    {
        return $this->hasOne(PembayaranSewa::class);
    }

    /**
     * Get pergerakan aset related to this tempahan through items
     */
    public function pergerakanAset()
    {
        return $this->hasMany(PergerakanAset::class, 'tempahan_fasiliti_id');
    }

    public function disemakOleh()
    {
        return $this->belongsTo(User::class, 'disemak_oleh');
    }

    public function diluluskanOleh()
    {
        return $this->belongsTo(User::class, 'diluluskan_oleh');
    }

    public function ditolakOleh()
    {
        return $this->belongsTo(User::class, 'ditolak_oleh');
    }

    public function dibatalkanOleh()
    {
        return $this->belongsTo(User::class, 'dibatalkan_oleh');
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
    public function scopeBaharu($query)
    {
        return $query->where('status_tempahan', 'Baharu');
    }

    public function scopeLulus($query)
    {
        return $query->where('status_tempahan', 'Lulus');
    }

    public function scopeAktif($query)
    {
        return $query->where('status_tempahan', 'Lulus')
                     ->where('tarikh_tamat', '>=', now());
    }

    // Methods
    public static function generateNoTempahan($masjidId)
    {
        $year = date('Y');
        $lastTempahan = self::where('masjid_id', $masjidId)
            ->where('no_tempahan', 'like', "TP-{$year}-%")
            ->orderBy('no_tempahan', 'desc')
            ->first();

        if ($lastTempahan) {
            $lastNumber = (int) substr($lastTempahan->no_tempahan, -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return "TP-{$year}-" . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    public function recalculateTotal()
    {
        $activeItems = $this->activeItems;
        $totalHargaSewa = $activeItems->sum('subtotal');
        
        $this->update([
            'harga_sewa' => $totalHargaSewa,
            'jumlah_bayaran' => $totalHargaSewa + ($this->deposit ?? 0),
        ]);
    }

    public function getTotalItemsAttribute()
    {
        return $this->activeItems()->count();
    }

    public function getTotalQuantityAttribute()
    {
        return $this->activeItems()->sum('quantity');
    }

    /**
     * Get nama attribute for delete modal compatibility
     * Returns no_tempahan as the identifier
     */
    public function getNamaAttribute()
    {
        return $this->no_tempahan;
    }

    /**
     * Get alamat penuh lokasi luaran
     */
    public function getAlamatPenuhLuaranAttribute()
    {
        if (!$this->is_lokasi_luaran) {
            return null;
        }
        
        $parts = array_filter([
            $this->nama_tempat_luaran,
            $this->alamat_luaran_1,
            $this->alamat_luaran_2,
            $this->poskod_luaran . ' ' . $this->bandar_luaran,
            $this->negeri_luaran,
        ]);
        
        return implode(', ', $parts);
    }

    /**
     * Check if tempahan is late for return
     */
    public function getIsLewatAttribute()
    {
        if ($this->status_tempahan !== 'Lulus' || $this->status_pemulangan === 'Sudah Pulang') {
            return false;
        }
        
        return $this->tarikh_tamat && $this->tarikh_tamat->isPast();
    }

    /**
     * Scopes for status pemulangan
     */
    public function scopeBelumPulang($query)
    {
        return $query->where('status_pemulangan', 'Belum Pulang');
    }

    public function scopeSudahPulang($query)
    {
        return $query->where('status_pemulangan', 'Sudah Pulang');
    }

    public function scopeLewatPulang($query)
    {
        return $query->where('status_pemulangan', 'Lewat');
    }
}
