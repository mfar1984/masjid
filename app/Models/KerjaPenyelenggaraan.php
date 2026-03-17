<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasMasjidScope;

class KerjaPenyelenggaraan extends Model
{
    use SoftDeletes, HasMasjidScope;

    protected $table = 'kerja_penyelenggaraan';

    protected $fillable = [
        'masjid_id',
        'no_kerja',
        'jadual_penyelenggaraan_id',
        'senarai_aset_id',
        'senarai_fasiliti_id',
        'jenis_item',
        'tarikh_kerja',
        'masa_mula',
        'masa_tamat',
        'jenis_kerja',
        'penerangan_kerja',
        'vendor_nama',
        'vendor_telefon',
        'vendor_alamat',
        'kos',
        'transaksi_kewangan_id',
        'kondisi_sebelum',
        'kondisi_selepas',
        'status',
        'gambar_sebelum',
        'gambar_selepas',
        'dokumen_path',
        'catatan',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'tarikh_kerja' => 'date',
        'kos' => 'decimal:2',
    ];

    // Relationships
    public function masjid()
    {
        return $this->belongsTo(Masjid::class);
    }

    public function jadualPenyelenggaraan()
    {
        return $this->belongsTo(JadualPenyelenggaraan::class, 'jadual_penyelenggaraan_id');
    }

    public function senariAset()
    {
        return $this->belongsTo(SenariAset::class, 'senarai_aset_id');
    }

    public function senariFasiliti()
    {
        return $this->belongsTo(SenariFasiliti::class, 'senarai_fasiliti_id');
    }

    public function transaksiKewangan()
    {
        return $this->belongsTo(TransaksiKewangan::class, 'transaksi_kewangan_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // Scopes
    public function scopeSelesai($query)
    {
        return $query->where('status', 'Selesai');
    }

    public function scopeDirancang($query)
    {
        return $query->where('status', 'Dirancang');
    }

    public function scopeSedangBerjalan($query)
    {
        return $query->where('status', 'Sedang Berjalan');
    }

    public function scopeByJenisItem($query, $jenis)
    {
        return $query->where('jenis_item', $jenis);
    }

    // Accessors
    public function getNamaAttribute()
    {
        return $this->no_kerja ?? 'Kerja #' . $this->id;
    }

    public function getItemNamaAttribute()
    {
        if ($this->jenis_item === 'Aset') {
            return $this->senariAset->nama_aset ?? '-';
        }
        return $this->senariFasiliti->nama_fasiliti ?? '-';
    }

    // Auto-generate no_kerja
    public static function generateNoKerja($masjidId)
    {
        $year = date('Y');
        $prefix = 'KP-' . $year . '-';

        $last = self::where('masjid_id', $masjidId)
            ->where('no_kerja', 'like', $prefix . '%')
            ->orderBy('no_kerja', 'desc')
            ->first();

        $nextNumber = $last ? intval(substr($last->no_kerja, -4)) + 1 : 1;

        return $prefix . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }
}
