<?php

namespace App\Models;

use App\Traits\HasMasjidScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KutipanDana extends Model
{
    use HasMasjidScope, SoftDeletes;

    protected $table = 'kutipan_dana';

    protected $fillable = [
        'masjid_id',
        'no_kutipan',
        'tarikh_kutipan',
        'jenis_kutipan',
        'kariah_id',
        'bulan_kutipan',
        'nama_penderma',
        'no_telefon_penderma',
        'alamat_penderma',
        'jenis_derma',
        'jenis_zakat',
        'nama_pembayar',
        'no_kp_pembayar',
        'kategori_kewangan_id',
        'jenis_derma_id',
        'akaun_bank_id',
        'jumlah',
        'kaedah_bayaran',
        'no_rujukan',
        'no_resit',
        'tujuan',
        'dokumen',
        'catatan',
        'transaksi_kewangan_id',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'tarikh_kutipan' => 'date',
        'jumlah' => 'decimal:2',
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

    public function jenisDerma()
    {
        return $this->belongsTo(KategoriKewangan::class, 'jenis_derma_id');
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
    public function scopeKutipanKariah($query)
    {
        return $query->where('jenis_kutipan', 'Kutipan Kariah');
    }

    public function scopeDermaSumbangan($query)
    {
        return $query->where('jenis_kutipan', 'Derma & Sumbangan');
    }

    public function scopeKutipanZakat($query)
    {
        return $query->where('jenis_kutipan', 'Kutipan Zakat');
    }

    public function scopeKutipanLain($query)
    {
        return $query->where('jenis_kutipan', 'Kutipan Lain-lain');
    }

    public function scopeBulanIni($query)
    {
        return $query->whereMonth('tarikh_kutipan', now()->month)
                     ->whereYear('tarikh_kutipan', now()->year);
    }

    public function scopeTahunIni($query)
    {
        return $query->whereYear('tarikh_kutipan', now()->year);
    }

    // Helper Methods
    public static function generateNoKutipan($masjidId)
    {
        $year = date('Y');
        $prefix = 'KUT-' . $year . '-';
        
        $lastKutipan = self::where('masjid_id', $masjidId)
            ->where('no_kutipan', 'like', $prefix . '%')
            ->orderBy('id', 'desc')
            ->first();

        if ($lastKutipan) {
            $lastNumber = (int) substr($lastKutipan->no_kutipan, -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $prefix . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }
}
