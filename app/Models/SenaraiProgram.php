<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SenaraiProgram extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'senarai_program';

    protected $fillable = [
        'masjid_id',
        'nama_program',
        'kod_program',
        'jenis_program',
        'kategori',
        'penerangan',
        'lokasi',
        'kapasiti',
        'yuran',
        'status',
        'created_by',
    ];

    protected $casts = [
        'yuran' => 'decimal:2',
    ];

    public function masjid()
    {
        return $this->belongsTo(Masjid::class);
    }

    public function jadual()
    {
        return $this->hasMany(JadualProgram::class, 'program_id');
    }

    public function peserta()
    {
        return $this->hasMany(PendaftaranPeserta::class, 'program_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
