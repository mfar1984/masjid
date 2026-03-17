<?php

namespace App\Models;

use App\Traits\HasMasjidScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Perbelanjaan extends Model
{
    use HasMasjidScope, SoftDeletes;

    protected $table = 'perbelanjaan';

    protected $fillable = [
        'masjid_id',
        'no_perbelanjaan',
        'tarikh_perbelanjaan',
        'jenis_perbelanjaan',
        'jenis_bil',
        'no_bil',
        'bacaan_meter_lama',
        'bacaan_meter_baru',
        'tarikh_akhir',
        'jenis_penyelenggaraan',
        'kontraktor',
        'no_telefon_kontraktor',
        'kerja_dilakukan',
        'nama_kakitangan',
        'jawatan',
        'gaji_pokok',
        'elaun',
        'potongan',
        'kategori_kewangan_id',
        'jenis_bil_id',
        'akaun_bank_id',
        'jumlah',
        'kaedah_bayaran',
        'no_rujukan',
        'pembekal_vendor',
        'keterangan',
        'dokumen',
        'catatan',
        'status_kelulusan',
        'diluluskan_oleh',
        'tarikh_diluluskan',
        'transaksi_kewangan_id',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'tarikh_perbelanjaan' => 'date',
        'tarikh_akhir' => 'date',
        'tarikh_diluluskan' => 'datetime',
        'jumlah' => 'decimal:2',
        'gaji_pokok' => 'decimal:2',
        'elaun' => 'decimal:2',
        'potongan' => 'decimal:2',
        'dokumen' => 'array',
    ];

    // Relationships
    public function masjid()
    {
        return $this->belongsTo(Masjid::class);
    }

    public function kategoriKewangan()
    {
        return $this->belongsTo(KategoriKewangan::class);
    }

    public function akaunBank()
    {
        return $this->belongsTo(AkaunBank::class);
    }

    public function transaksiKewangan()
    {
        return $this->belongsTo(TransaksiKewangan::class);
    }

    public function jenisBil()
    {
        return $this->belongsTo(KategoriKewangan::class, 'jenis_bil_id');
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
    public function scopeUtilitiBil($query)
    {
        return $query->where('jenis_perbelanjaan', 'Utiliti & Bil');
    }

    public function scopePenyelenggaraan($query)
    {
        return $query->where('jenis_perbelanjaan', 'Penyelenggaraan');
    }

    public function scopeGajiElaun($query)
    {
        return $query->where('jenis_perbelanjaan', 'Gaji & Elaun');
    }

    public function scopePerbelanjaanLain($query)
    {
        return $query->where('jenis_perbelanjaan', 'Perbelanjaan Lain');
    }

    public function scopePending($query)
    {
        return $query->where('status_kelulusan', 'Pending');
    }

    public function scopeDiluluskan($query)
    {
        return $query->where('status_kelulusan', 'Diluluskan');
    }

    public function scopeDitolak($query)
    {
        return $query->where('status_kelulusan', 'Ditolak');
    }

    public function scopeBulanIni($query)
    {
        return $query->whereMonth('tarikh_perbelanjaan', now()->month)
                     ->whereYear('tarikh_perbelanjaan', now()->year);
    }

    public function scopeTahunIni($query)
    {
        return $query->whereYear('tarikh_perbelanjaan', now()->year);
    }

    // Helper Methods
    public static function generateNoPerbelanjaan($masjidId)
    {
        $year = date('Y');
        $prefix = 'BLJ-' . $year . '-';
        
        $lastPerbelanjaan = self::where('masjid_id', $masjidId)
            ->where('no_perbelanjaan', 'like', $prefix . '%')
            ->orderBy('id', 'desc')
            ->first();

        if ($lastPerbelanjaan) {
            $lastNumber = (int) substr($lastPerbelanjaan->no_perbelanjaan, -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $prefix . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }
}
