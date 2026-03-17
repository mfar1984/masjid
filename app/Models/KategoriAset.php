<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasMasjidScope;

class KategoriAset extends Model
{
    use SoftDeletes, HasMasjidScope;

    protected $table = 'kategori_aset';

    protected $fillable = [
        'masjid_id',
        'kod_kategori',
        'nama_kategori',
        'jenis_kategori',
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

    public function senariAset()
    {
        return $this->hasMany(SenariAset::class);
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
        return $query->where('status', 'Aktif');
    }

    public function scopeByJenis($query, $jenis)
    {
        return $query->where('jenis_kategori', $jenis);
    }

    public function scopeTanahBangunan($query)
    {
        return $query->where('jenis_kategori', 'Tanah & Bangunan');
    }

    public function scopeKenderaan($query)
    {
        return $query->where('jenis_kategori', 'Kenderaan');
    }

    public function scopePeralatan($query)
    {
        return $query->where('jenis_kategori', 'Peralatan');
    }

    public function scopePerabot($query)
    {
        return $query->where('jenis_kategori', 'Perabot');
    }

    public function scopeElektronik($query)
    {
        return $query->where('jenis_kategori', 'Elektronik');
    }
}
