<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PendaftaranPeserta extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'pendaftaran_peserta';

    protected $fillable = [
        'masjid_id',
        'program_id',
        'jadual_id',
        'nama_peserta',
        'no_ic',
        'no_telefon',
        'email',
        'alamat',
        'tarikh_daftar',
        'status_bayaran',
        'jumlah_bayaran',
        'status_kehadiran',
        'catatan',
        'created_by',
    ];

    protected $casts = [
        'tarikh_daftar' => 'date',
        'jumlah_bayaran' => 'decimal:2',
    ];

    public function masjid()
    {
        return $this->belongsTo(Masjid::class);
    }

    public function program()
    {
        return $this->belongsTo(SenaraiProgram::class, 'program_id');
    }

    public function jadual()
    {
        return $this->belongsTo(JadualProgram::class, 'jadual_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
