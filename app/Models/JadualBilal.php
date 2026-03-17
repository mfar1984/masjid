<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JadualBilal extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'jadual_bilal';

    protected $fillable = [
        'masjid_id',
        'ajk_id',
        'nama_bilal',
        'tarikh',
        'waktu_solat',
        'status',
        'nama_ganti',
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

    public function ajk()
    {
        return $this->belongsTo(Ajk::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Get display name
    public function getNamaDisplayAttribute()
    {
        if ($this->ajk) {
            return $this->ajk->nama;
        }
        return $this->nama_bilal ?? '-';
    }
}
