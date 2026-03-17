<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JadualProgram extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'jadual_program';

    protected $fillable = [
        'masjid_id',
        'program_id',
        'tarikh',
        'masa_mula',
        'masa_tamat',
        'lokasi',
        'penceramah',
        'catatan',
        'status',
        'created_by',
    ];

    protected $casts = [
        'tarikh' => 'date',
    ];

    public function masjid()
    {
        return $this->belongsTo(Masjid::class);
    }

    public function program()
    {
        return $this->belongsTo(SenaraiProgram::class, 'program_id');
    }

    public function peserta()
    {
        return $this->hasMany(PendaftaranPeserta::class, 'jadual_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
