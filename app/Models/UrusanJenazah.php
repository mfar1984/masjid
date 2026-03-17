<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UrusanJenazah extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'urusan_jenazah';

    protected $fillable = [
        'masjid_id',
        'no_rujukan',
        'nama_simati',
        'no_ic_simati',
        'jantina',
        'umur',
        'alamat_simati',
        'tarikh_meninggal',
        'masa_meninggal',
        'tempat_meninggal',
        'sebab_kematian',
        'nama_waris',
        'no_telefon_waris',
        'hubungan_waris',
        'tarikh_mandi_kafan',
        'tarikh_solat_jenazah',
        'imam_solat',
        'tarikh_kebumi',
        'lokasi_kubur',
        'no_kubur',
        'kos_pengurusan',
        'status_bayaran',
        'status',
        'catatan',
        'created_by',
    ];

    protected $casts = [
        'tarikh_meninggal' => 'date',
        'tarikh_mandi_kafan' => 'datetime',
        'tarikh_solat_jenazah' => 'datetime',
        'tarikh_kebumi' => 'datetime',
        'kos_pengurusan' => 'decimal:2',
    ];

    public function masjid()
    {
        return $this->belongsTo(Masjid::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Generate no rujukan
    public static function generateNoRujukan($masjidId)
    {
        $year = date('Y');
        $count = self::where('masjid_id', $masjidId)
            ->whereYear('created_at', $year)
            ->count() + 1;
        
        return 'JNZ-' . $year . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);
    }
}
