<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SenaraiPenceramah extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'senarai_penceramah';

    protected $fillable = [
        'masjid_id',
        'nama',
        'no_ic',
        'no_telefon',
        'email',
        'alamat',
        'negara',
        'negeri',
        'no_sijil_tauliah',
        'tarikh_tamat_tauliah',
        'pihak_pengeluar',
        'bidang_kepakaran',
        'gambar',
        'dokumen_sijil',
        'status',
        'catatan',
        'created_by',
    ];

    protected $casts = [
        'tarikh_tamat_tauliah' => 'date',
    ];

    public function masjid()
    {
        return $this->belongsTo(Masjid::class);
    }

    public function jadualCeramah()
    {
        return $this->hasMany(JadualCeramah::class, 'penceramah_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Check if tauliah is valid
    public function isTauliahValid()
    {
        if (!$this->tarikh_tamat_tauliah) {
            return true; // No expiry set
        }
        return $this->tarikh_tamat_tauliah >= now();
    }
}
