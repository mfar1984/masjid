<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JadualImamBilal extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'jadual_imam_bilal';

    protected $fillable = [
        'masjid_id',
        'tarikh',
        'waktu_solat',
        'imam_ajk_id',
        'nama_imam',
        'status_imam',
        'imam_ganti',
        'bilal_ajk_id',
        'nama_bilal',
        'status_bilal',
        'bilal_ganti',
        'jenis_jadual',
        'batch_id',
        'catatan',
        'created_by',
    ];

    protected $casts = [
        'tarikh' => 'date',
    ];

    public function masjid()
    {
        return $this->belongsTo(Masjid::class);
    }

    public function imamAjk()
    {
        return $this->belongsTo(Ajk::class, 'imam_ajk_id');
    }

    public function bilalAjk()
    {
        return $this->belongsTo(Ajk::class, 'bilal_ajk_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Get display name for imam (format: "Imam Nama")
    public function getImamDisplayAttribute()
    {
        if ($this->imamAjk) {
            return 'Imam ' . $this->imamAjk->nama;
        }
        if ($this->nama_imam) {
            return 'Imam ' . $this->nama_imam;
        }
        return '-';
    }

    // Get display name for bilal (format: "Bilal Nama")
    public function getBilalDisplayAttribute()
    {
        if ($this->bilalAjk) {
            return 'Bilal ' . $this->bilalAjk->nama;
        }
        if ($this->nama_bilal) {
            return 'Bilal ' . $this->nama_bilal;
        }
        return '-';
    }

    // Get short name for imam (without prefix)
    public function getImamShortNameAttribute()
    {
        if ($this->imamAjk) {
            return $this->imamAjk->nama;
        }
        return $this->nama_imam ?? '-';
    }

    // Get short name for bilal (without prefix)
    public function getBilalShortNameAttribute()
    {
        if ($this->bilalAjk) {
            return $this->bilalAjk->nama;
        }
        return $this->nama_bilal ?? '-';
    }

    // Scope for masjid
    public function scopeForMasjid($query, $masjidId)
    {
        return $query->where('masjid_id', $masjidId);
    }

    // Scope for date range
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('tarikh', [$startDate, $endDate]);
    }

    // Scope for waktu solat
    public function scopeWaktuSolat($query, $waktu)
    {
        return $query->where('waktu_solat', $waktu);
    }
}
