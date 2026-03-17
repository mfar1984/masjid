<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KategoriAsnaf extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'kategori_asnaf';

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

    // Relationships
    public function masjid()
    {
        return $this->belongsTo(Masjid::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function deleter()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }

    // Scopes
    public function scopeBangsa($query)
    {
        return $query->where('jenis_kategori', 'bangsa');
    }

    public function scopeAgama($query)
    {
        return $query->where('jenis_kategori', 'agama');
    }

    public function scopeStatusPerkahwinan($query)
    {
        return $query->where('jenis_kategori', 'status_perkahwinan');
    }

    public function scopeNegeri($query)
    {
        return $query->where('jenis_kategori', 'negeri');
    }

    public function scopeKategoriAsnaf($query)
    {
        return $query->where('jenis_kategori', 'kategori_asnaf');
    }

    public function scopeStatusPekerjaan($query)
    {
        return $query->where('jenis_kategori', 'status_pekerjaan');
    }

    public function scopeStatusKesihatan($query)
    {
        return $query->where('jenis_kategori', 'status_kesihatan');
    }

    public function scopeKewarganegaraan($query)
    {
        return $query->where('jenis_kategori', 'kewarganegaraan');
    }

    public function scopeAktif($query)
    {
        return $query->where('status', 'Aktif');
    }
}
