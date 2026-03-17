<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PermohonanPelupusan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'permohonan_pelupusan';

    protected $fillable = [
        'masjid_id',
        'senarai_aset_id',
        'no_rujukan',
        'tarikh_permohonan',
        'sebab_pelupusan',
        'kaedah_pelupusan',
        'nilai_pelupusan',
        'status',
        'diluluskan_oleh',
        'tarikh_kelulusan',
        'catatan_kelulusan',
        'tarikh_pelupusan',
        'catatan',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'tarikh_permohonan' => 'date',
        'tarikh_kelulusan' => 'date',
        'tarikh_pelupusan' => 'date',
        'nilai_pelupusan' => 'decimal:2',
    ];

    public function masjid()
    {
        return $this->belongsTo(Masjid::class);
    }

    public function senariAset()
    {
        return $this->belongsTo(SenariAset::class, 'senarai_aset_id');
    }

    public function diluluskanOleh()
    {
        return $this->belongsTo(User::class, 'diluluskan_oleh');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public static function generateNoRujukan($masjidId)
    {
        $year = date('Y');
        $prefix = 'PLP';
        $lastRecord = self::where('masjid_id', $masjidId)
            ->whereYear('created_at', $year)
            ->orderBy('id', 'desc')
            ->first();
        
        $nextNumber = $lastRecord ? (int)substr($lastRecord->no_rujukan, -4) + 1 : 1;
        return $prefix . $year . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }
}
