<?php

namespace App\Models;

use App\Traits\HasMasjidScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProgramKebajikan extends Model
{
    use HasFactory, SoftDeletes, HasMasjidScope;

    protected $table = 'program_kebajikan';

    protected $fillable = [
        'masjid_id',
        'kod_program',
        'nama_program',
        'kategori_program',
        'jenis_bantuan',
        'had_maksimum',
        'had_minimum',
        'tempoh_bantuan',
        'syarat_kelayakan',
        'dokumen_diperlukan',
        'status_program',
        'tarikh_mula',
        'tarikh_tamat',
        'catatan',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'tarikh_mula' => 'date',
        'tarikh_tamat' => 'date',
        'had_maksimum' => 'decimal:2',
        'had_minimum' => 'decimal:2',
    ];

    // Relationships
    public function masjid()
    {
        return $this->belongsTo(Masjid::class);
    }

    public function permohonanBantuan()
    {
        return $this->hasMany(PermohonanBantuan::class);
    }

    public function pembayaranBantuan()
    {
        return $this->hasMany(PembayaranBantuan::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function deleter()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }

    // Auto-generate kod_program
    public static function generateKodProgram($masjidId)
    {
        $year = date('Y');
        $prefix = 'KB-' . $year . '-';

        $lastProgram = self::where('masjid_id', $masjidId)
            ->where('kod_program', 'like', $prefix . '%')
            ->orderBy('kod_program', 'desc')
            ->first();

        $nextNumber = $lastProgram ? intval(substr($lastProgram->kod_program, -4)) + 1 : 1;

        return $prefix . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }
}
