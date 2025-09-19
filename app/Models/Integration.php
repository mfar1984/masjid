<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Traits\MasjidScope;

class Integration extends Model
{
    use HasFactory, MasjidScope;

    protected $fillable = [
        'nama',
        'jenis',
        'status',
        'konfigurasi',
        'penerangan',
        'url_endpoint',
        'api_key',
        'terakhir_sync',
        'masjid_id',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'konfigurasi' => 'array',
        'terakhir_sync' => 'datetime',
    ];

    // Relationships
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

    // Accessors
    public function getTerakhirSyncFormattedAttribute()
    {
        return $this->terakhir_sync ? $this->terakhir_sync->format('d/m/Y H:i') : '-';
    }

    public function getCreatedAtFormattedAttribute()
    {
        return $this->created_at->format('d/m/Y H:i');
    }

    public function getUpdatedAtFormattedAttribute()
    {
        return $this->updated_at->format('d/m/Y H:i');
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'Aktif' => 'bg-green-100 text-green-800',
            'Tidak Aktif' => 'bg-red-100 text-red-800',
            'Dalam Pembangunan' => 'bg-yellow-100 text-yellow-800',
        ];

        return $badges[$this->status] ?? 'bg-gray-100 text-gray-800';
    }

    // Scopes
    public function scopeSearch($query, $search)
    {
        return $query->where(function($q) use ($search) {
            $q->where('nama', 'like', "%{$search}%")
              ->orWhere('jenis', 'like', "%{$search}%")
              ->orWhere('penerangan', 'like', "%{$search}%");
        });
    }

    public function scopeFilterByStatus($query, $status)
    {
        if ($status) {
            return $query->where('status', $status);
        }
        return $query;
    }

    public function scopeFilterByType($query, $jenis)
    {
        if ($jenis) {
            return $query->where('jenis', $jenis);
        }
        return $query;
    }

    // Helper Methods
    public static function getJenisOptions()
    {
        return [
            'Email' => 'Email (SMTP)',
            'Weather' => 'Cuaca',
            'API' => 'API',
            'Database' => 'Database',
            'File' => 'File',
            'Webhook' => 'Webhook',
        ];
    }

    public static function getStatusOptions()
    {
        return [
            'Aktif' => 'Aktif',
            'Tidak Aktif' => 'Tidak Aktif', 
            'Dalam Pembangunan' => 'Dalam Pembangunan',
        ];
    }
}