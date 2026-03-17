<?php

namespace App\Models;

use App\Traits\HasMasjidScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TransaksiKewangan extends Model
{
    use HasMasjidScope, SoftDeletes;

    protected $table = 'transaksi_kewangan';

    protected $fillable = [
        'masjid_id',
        'no_transaksi',
        'tarikh_transaksi',
        'jenis_transaksi',
        'kategori_kewangan_id',
        'akaun_bank_id',
        'jumlah',
        'kaedah_bayaran',
        'no_rujukan',
        'keterangan',
        'dokumen',
        'rujukan_id',
        'rujukan_type',
        'status',
        'catatan',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'tarikh_transaksi' => 'date',
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

    // Polymorphic relationship
    public function rujukan()
    {
        return $this->morphTo();
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
    public function scopePendapatan($query)
    {
        return $query->where('jenis_transaksi', 'Pendapatan');
    }

    public function scopePerbelanjaan($query)
    {
        return $query->where('jenis_transaksi', 'Perbelanjaan');
    }

    public function scopeSelesai($query)
    {
        return $query->where('status', 'Selesai');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'Pending');
    }

    public function scopeBulanIni($query)
    {
        return $query->whereMonth('tarikh_transaksi', now()->month)
                     ->whereYear('tarikh_transaksi', now()->year);
    }

    public function scopeTahunIni($query)
    {
        return $query->whereYear('tarikh_transaksi', now()->year);
    }

    // Helper Methods
    public static function generateNoTransaksi($masjidId)
    {
        $year = date('Y');
        $prefix = 'TRX-' . $year . '-';
        
        $lastTransaction = self::where('masjid_id', $masjidId)
            ->where('no_transaksi', 'like', $prefix . '%')
            ->orderBy('id', 'desc')
            ->first();

        if ($lastTransaction) {
            $lastNumber = (int) substr($lastTransaction->no_transaksi, -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $prefix . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }
}
