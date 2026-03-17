<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JadualPenyusutan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'jadual_penyusutan';

    protected $fillable = [
        'masjid_id',
        'kategori_aset_id',
        'kadar_susut_tahunan',
        'kaedah_susut',
        'tempoh_guna_tahun',
        'status',
        'catatan',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'kadar_susut_tahunan' => 'decimal:2',
    ];

    public function masjid()
    {
        return $this->belongsTo(Masjid::class);
    }

    public function kategoriAset()
    {
        return $this->belongsTo(KategoriAset::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
