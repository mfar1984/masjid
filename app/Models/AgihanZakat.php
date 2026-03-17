<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasMasjidScope;

class AgihanZakat extends Model
{
    use SoftDeletes, HasMasjidScope;

    protected $table = 'agihan_zakat';

    protected $fillable = [
        'permohonan_zakat_id',
        'asnaf_id',
        'masjid_id',
        'no_agihan',
        'tarikh_agihan',
        'jumlah_diagihkan',
        'kaedah_bayaran',
        'no_rujukan',
        'nama_bank',
        'no_akaun',
        'status',
        'tarikh_bayaran',
        'bukti_bayaran_path',
        'catatan',
        'created_by',
        'updated_by',
        'dibayar_oleh',
    ];

    protected $casts = [
        'tarikh_agihan' => 'date',
        'tarikh_bayaran' => 'date',
        'jumlah_diagihkan' => 'decimal:2',
    ];

    // Relationships
    public function permohonanZakat()
    {
        return $this->belongsTo(PermohonanZakat::class);
    }

    public function asnaf()
    {
        return $this->belongsTo(Asnaf::class);
    }

    public function masjid()
    {
        return $this->belongsTo(Masjid::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function dibayarOleh()
    {
        return $this->belongsTo(User::class, 'dibayar_oleh');
    }

    // Scopes
    public function scopeBelumBayar($query)
    {
        return $query->where('status', 'Belum Bayar');
    }

    public function scopeSudahBayar($query)
    {
        return $query->where('status', 'Sudah Bayar');
    }

    public function scopeDibatalkan($query)
    {
        return $query->where('status', 'Dibatalkan');
    }

    // Helper methods
    public static function generateNoAgihan($masjidId)
    {
        $year = date('Y');
        $prefix = 'AG-' . $year . '-';
        
        $lastAgihan = self::where('masjid_id', $masjidId)
            ->where('no_agihan', 'like', $prefix . '%')
            ->orderBy('no_agihan', 'desc')
            ->first();
        
        if ($lastAgihan) {
            $lastNumber = (int) substr($lastAgihan->no_agihan, -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        
        return $prefix . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    public function canBeEdited()
    {
        return $this->status === 'Belum Bayar';
    }

    public function canBePaid()
    {
        return $this->status === 'Belum Bayar';
    }

    public function canBeCancelled()
    {
        return $this->status === 'Belum Bayar';
    }

    // Accessors
    public function getTarikhAgihanFormattedAttribute()
    {
        return $this->tarikh_agihan ? $this->tarikh_agihan->format('d/m/Y') : '-';
    }

    public function getTarikhBayaranFormattedAttribute()
    {
        return $this->tarikh_bayaran ? $this->tarikh_bayaran->format('d/m/Y') : '-';
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'Belum Bayar' => '<span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-orange-100 text-orange-800">Belum Bayar</span>',
            'Sudah Bayar' => '<span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-green-100 text-green-800">Sudah Bayar</span>',
            'Dibatalkan' => '<span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-red-100 text-red-800">Dibatalkan</span>',
        ];
        
        return $badges[$this->status] ?? $this->status;
    }
}
