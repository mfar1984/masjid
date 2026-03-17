<?php

namespace App\Models;

use App\Traits\HasMasjidScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AkaunBank extends Model
{
    use HasMasjidScope, SoftDeletes;

    protected $table = 'akaun_bank';

    protected $fillable = [
        'masjid_id',
        'nama_bank',
        'no_akaun',
        'jenis_akaun',
        'nama_pemegang_akaun',
        'cawangan',
        'baki_awal',
        'baki_semasa',
        'status',
        'catatan',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'baki_awal' => 'decimal:2',
        'baki_semasa' => 'decimal:2',
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
    public function scopeAktif($query)
    {
        return $query->where('status', 'Aktif');
    }

    public function scopeTidakAktif($query)
    {
        return $query->where('status', 'Tidak Aktif');
    }

    // Helper Methods
    public function updateBaki($jumlah, $jenis = 'tambah')
    {
        if ($jenis === 'tambah') {
            $this->baki_semasa += $jumlah;
        } else {
            $this->baki_semasa -= $jumlah;
        }
        $this->save();
    }
}
