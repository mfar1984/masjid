<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JadualCeramah extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'jadual_ceramah';

    protected $fillable = [
        'masjid_id',
        'penceramah_id',
        'tarikh',
        'masa_mula',
        'masa_tamat',
        'tajuk_ceramah',
        'jenis_ceramah',
        'lokasi',
        'jenis_bayaran',
        'kadar_bayaran',
        'status_bayaran',
        'tarikh_bayaran',
        'kos_pengangkutan',
        'kos_penginapan',
        'kos_makan_minum',
        'kos_lain',
        'catatan_kos',
        'status',
        'catatan',
        'transaksi_id',
        'created_by',
    ];

    protected $casts = [
        'tarikh' => 'date',
        'tarikh_bayaran' => 'date',
        'kadar_bayaran' => 'decimal:2',
        'kos_pengangkutan' => 'decimal:2',
        'kos_penginapan' => 'decimal:2',
        'kos_makan_minum' => 'decimal:2',
        'kos_lain' => 'decimal:2',
    ];

    public function masjid()
    {
        return $this->belongsTo(Masjid::class);
    }

    public function penceramah()
    {
        return $this->belongsTo(SenaraiPenceramah::class, 'penceramah_id');
    }

    public function transaksi()
    {
        return $this->belongsTo(TransaksiKewangan::class, 'transaksi_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Calculate total kos
    public function getJumlahKosAttribute()
    {
        return ($this->kadar_bayaran ?? 0) +
               ($this->kos_pengangkutan ?? 0) +
               ($this->kos_penginapan ?? 0) +
               ($this->kos_makan_minum ?? 0) +
               ($this->kos_lain ?? 0);
    }
}
