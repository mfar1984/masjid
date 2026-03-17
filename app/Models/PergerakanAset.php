<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasMasjidScope;

class PergerakanAset extends Model
{
    use SoftDeletes, HasMasjidScope;

    protected $table = 'pergerakan_aset';

    protected $fillable = [
        'masjid_id',
        'no_pergerakan',
        'senarai_aset_id',
        // Tempahan reference fields
        'tempahan_fasiliti_id',
        'tempahan_fasiliti_item_id',
        'kuantiti',
        // Pulangan tracking fields
        'kuantiti_dipulangkan',
        'kuantiti_hilang',
        'kuantiti_rosak',
        'nilai_ganti_rugi',
        'transaksi_kewangan_id',
        'tarikh_selesai_pulangan',
        'diselesaikan_oleh',
        'tarikh_pergerakan',
        'jenis_pergerakan',
        'lokasi_asal',
        'lokasi_destinasi',
        'is_lokasi_luaran',
        'nama_tempat_luaran',
        'alamat_luaran_1',
        'alamat_luaran_2',
        'poskod_luaran',
        'bandar_luaran',
        'negeri_luaran',
        'nama_peminjam',
        'no_ic_peminjam',
        'no_telefon_peminjam',
        'organisasi_peminjam',
        'tarikh_jangka_pulangan',
        'tarikh_sebenar_pulangan',
        'status_pulangan',
        'kondisi_sebelum',
        'kondisi_selepas',
        'surat_kebenaran_path',
        'gambar_sebelum',
        'gambar_selepas',
        'borang_pinjaman_path',
        'require_approval',
        'diluluskan_oleh',
        'tarikh_diluluskan',
        'catatan_kelulusan',
        'sebab_pergerakan',
        'catatan',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'tarikh_pergerakan' => 'datetime',
        'tarikh_jangka_pulangan' => 'date',
        'tarikh_sebenar_pulangan' => 'datetime',
        'tarikh_selesai_pulangan' => 'datetime',
        'tarikh_diluluskan' => 'datetime',
        'is_lokasi_luaran' => 'boolean',
        'require_approval' => 'boolean',
        'nilai_ganti_rugi' => 'decimal:2',
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

    /**
     * Relationship to tempahan fasiliti
     */
    public function tempahanFasiliti()
    {
        return $this->belongsTo(TempahanFasiliti::class, 'tempahan_fasiliti_id');
    }

    /**
     * Relationship to tempahan fasiliti item
     */
    public function tempahanFasilitiItem()
    {
        return $this->belongsTo(TempahanFasilitiItem::class, 'tempahan_fasiliti_item_id');
    }

    public function diluluskanOleh()
    {
        return $this->belongsTo(User::class, 'diluluskan_oleh');
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
    public function scopeBelumPulang($query)
    {
        return $query->where('status_pulangan', 'Belum Pulang');
    }

    public function scopeSudahPulang($query)
    {
        return $query->where('status_pulangan', 'Sudah Pulang');
    }

    public function scopeLewat($query)
    {
        return $query->where('status_pulangan', 'Lewat');
    }

    public function scopeSebahagian($query)
    {
        return $query->where('status_pulangan', 'Sebahagian');
    }

    public function scopeHilang($query)
    {
        return $query->where('status_pulangan', 'Hilang');
    }

    // Relationship to transaksi kewangan (ganti rugi)
    public function transaksiKewangan()
    {
        return $this->belongsTo(TransaksiKewangan::class, 'transaksi_kewangan_id');
    }

    public function diselesaikanOleh()
    {
        return $this->belongsTo(User::class, 'diselesaikan_oleh');
    }

    public function scopeLuaran($query)
    {
        return $query->where('is_lokasi_luaran', true);
    }

    public function scopeDalaman($query)
    {
        return $query->where('is_lokasi_luaran', false);
    }

    public function scopeByJenis($query, $jenis)
    {
        return $query->where('jenis_pergerakan', $jenis);
    }

    // Accessors
    public function getNamaAttribute()
    {
        // Return no_pergerakan as nama for action-icons component
        return $this->no_pergerakan ?? 'Pergerakan #' . $this->id;
    }

    public function getIsLewatAttribute()
    {
        if (!$this->tarikh_jangka_pulangan || $this->status_pulangan !== 'Belum Pulang') {
            return false;
        }
        
        return $this->tarikh_jangka_pulangan->isPast();
    }

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

    // Auto-generate no_pergerakan
    public static function generateNoPergerakan($masjidId)
    {
        $year = date('Y');
        $prefix = 'PG-' . $year . '-';

        $lastPergerakan = self::where('masjid_id', $masjidId)
            ->where('no_pergerakan', 'like', $prefix . '%')
            ->orderBy('no_pergerakan', 'desc')
            ->first();

        $nextNumber = $lastPergerakan ? intval(substr($lastPergerakan->no_pergerakan, -4)) + 1 : 1;

        return $prefix . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }
}
