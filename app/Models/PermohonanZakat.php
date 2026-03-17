<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasMasjidScope;

class PermohonanZakat extends Model
{
    use SoftDeletes, HasMasjidScope;

    protected $table = 'permohonan_zakat';

    protected $fillable = [
        'asnaf_id',
        'masjid_id',
        'no_permohonan',
        'tarikh_permohonan',
        'jenis_bantuan',
        'kategori_bantuan',
        'jumlah_dipohon',
        'sebab_permohonan',
        'dokumen_sokongan_path',
        'status',
        'tarikh_semakan',
        'disemak_oleh',
        'catatan_semakan',
        'tarikh_kelulusan',
        'diluluskan_oleh',
        'jumlah_diluluskan',
        'catatan_kelulusan',
        'minit_mesyuarat_path',
        'tarikh_mesyuarat',
        'no_mesyuarat',
        'sebab_penolakan',
        'tarikh_penolakan',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'tarikh_permohonan' => 'date',
        'tarikh_semakan' => 'date',
        'tarikh_kelulusan' => 'date',
        'tarikh_mesyuarat' => 'date',
        'tarikh_penolakan' => 'date',
        'jumlah_dipohon' => 'decimal:2',
        'jumlah_diluluskan' => 'decimal:2',
    ];

    // Relationships
    public function asnaf()
    {
        return $this->belongsTo(Asnaf::class);
    }

    public function masjid()
    {
        return $this->belongsTo(Masjid::class);
    }

    public function disemakOleh()
    {
        return $this->belongsTo(User::class, 'disemak_oleh');
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

    public function agihanZakat()
    {
        return $this->hasMany(AgihanZakat::class);
    }

    // Helper methods
    public static function generateNoPermohonan($masjidId)
    {
        $year = date('Y');
        $prefix = 'PZ-' . $year . '-';
        
        $lastPermohonan = self::where('masjid_id', $masjidId)
            ->where('no_permohonan', 'like', $prefix . '%')
            ->orderBy('no_permohonan', 'desc')
            ->first();
        
        if ($lastPermohonan) {
            $lastNumber = (int) substr($lastPermohonan->no_permohonan, -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        
        return $prefix . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'Menunggu' => '<span class="px-2 py-1 text-xs rounded" style="background-color: #FEF3C7; color: #92400E;">Menunggu</span>',
            'Dalam Semakan' => '<span class="px-2 py-1 text-xs rounded" style="background-color: #DBEAFE; color: #1E40AF;">Dalam Semakan</span>',
            'Diluluskan' => '<span class="px-2 py-1 text-xs rounded" style="background-color: #D1FAE5; color: #065F46;">Diluluskan</span>',
            'Ditolak' => '<span class="px-2 py-1 text-xs rounded" style="background-color: #FEE2E2; color: #991B1B;">Ditolak</span>',
            'Dibatalkan' => '<span class="px-2 py-1 text-xs rounded" style="background-color: #F3F4F6; color: #374151;">Dibatalkan</span>',
        ];
        
        return $badges[$this->status] ?? $this->status;
    }

    public function canBeEdited()
    {
        return in_array($this->status, ['Menunggu', 'Dalam Semakan']);
    }

    public function canBeApproved()
    {
        return in_array($this->status, ['Menunggu', 'Dalam Semakan']);
    }

    public function canBeRejected()
    {
        return in_array($this->status, ['Menunggu', 'Dalam Semakan']);
    }
}
