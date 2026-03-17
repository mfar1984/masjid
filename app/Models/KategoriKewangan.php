<?php

namespace App\Models;

use App\Traits\HasMasjidScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KategoriKewangan extends Model
{
    use HasMasjidScope, SoftDeletes;

    protected $table = 'kategori_kewangan';

    protected $fillable = [
        'masjid_id',
        'jenis_kategori',
        'nama_kategori',
        'kod_kategori',
        'keterangan',
        'urutan',
        'status',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'urutan' => 'integer',
    ];

    // Relationships
    public function masjid()
    {
        return $this->belongsTo(Masjid::class);
    }

    public function transaksiKewangan()
    {
        return $this->hasMany(TransaksiKewangan::class);
    }

    public function kutipanDana()
    {
        return $this->hasMany(KutipanDana::class);
    }

    public function perbelanjaan()
    {
        return $this->hasMany(Perbelanjaan::class);
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
        return $query->where('jenis_kategori', 'Pendapatan');
    }

    public function scopePerbelanjaan($query)
    {
        return $query->where('jenis_kategori', 'Perbelanjaan');
    }

    public function scopeKategoriPendapatan($query)
    {
        return $query->where('jenis_kategori', 'kategori_pendapatan');
    }

    public function scopeKategoriPerbelanjaan($query)
    {
        return $query->where('jenis_kategori', 'kategori_perbelanjaan');
    }

    public function scopeKaedahBayaran($query)
    {
        return $query->where('jenis_kategori', 'kaedah_bayaran');
    }

    public function scopeJenisAkaun($query)
    {
        return $query->where('jenis_kategori', 'jenis_akaun');
    }

    public function scopeNamaBank($query)
    {
        return $query->where('jenis_kategori', 'nama_bank');
    }

    public function scopeAktif($query)
    {
        return $query->where('status', 'Aktif');
    }

    public function scopeTidakAktif($query)
    {
        return $query->where('status', 'Tidak Aktif');
    }
}
