<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\MasjidScope;

class Tetapan extends Model
{
    use HasFactory, MasjidScope;

    protected $table = 'tetapan';

    protected $fillable = [
        'kunci',
        'nama',
        'nilai',
        'jenis',
        'penerangan',
        'boleh_edit',
        'kategori',
        'susunan',
        'masjid_id',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'boleh_edit' => 'boolean',
        'susunan' => 'integer',
        'masjid_id' => 'integer',
    ];

    // Relationships
    public function masjid(): BelongsTo
    {
        return $this->belongsTo(Masjid::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // Scopes
    public function scopeSearch($query, $search)
    {
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('kunci', 'like', "%{$search}%")
                  ->orWhere('penerangan', 'like', "%{$search}%");
            });
        }
        return $query;
    }

    public function scopeFilterByKategori($query, $kategori)
    {
        if ($kategori) {
            $query->where('kategori', $kategori);
        }
        return $query;
    }

    public function scopeFilterByJenis($query, $jenis)
    {
        if ($jenis) {
            $query->where('jenis', $jenis);
        }
        return $query;
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('kategori')->orderBy('susunan')->orderBy('nama');
    }

    // Accessors
    public function getFormattedNilaiAttribute()
    {
        switch ($this->jenis) {
            case 'boolean':
                return $this->nilai ? 'Ya' : 'Tidak';
            case 'date':
                return $this->nilai ? date('d/m/Y', strtotime($this->nilai)) : '-';
            default:
                return $this->nilai;
        }
    }

    // Static method to get setting value for current user's masjid
    public static function get($kunci, $default = null, $masjidId = null)
    {
        $user = auth()->user();

        // Determine target masjid_id
        if ($masjidId !== null) {
            // Explicitly set masjid_id (can be null for Super Admin personal settings)
            $targetMasjidId = $masjidId;
        } else {
            // Use user's masjid_id
            $targetMasjidId = $user->masjid_id ?? null;
            
            // For non-Super Admin, masjid_id is required
            if (!$targetMasjidId && !$user->isSuperAdmin()) {
                return $default;
            }
        }

        $query = static::withoutMasjidScope()->where('kunci', $kunci);
        
        // Handle null masjid_id (Super Admin personal settings)
        if ($targetMasjidId === null) {
            $query->whereNull('masjid_id');
        } else {
            $query->where('masjid_id', $targetMasjidId);
        }

        $setting = $query->first();

        return $setting ? $setting->nilai : $default;
    }

    // Static method to set setting value for current user's masjid
    public static function set($kunci, $nilai, $nama = null, $masjidId = null)
    {
        $user = auth()->user();

        // Determine target masjid_id
        if ($masjidId !== null) {
            // Explicitly set masjid_id (can be null for Super Admin personal settings)
            $targetMasjidId = $masjidId;
        } else {
            // Use user's masjid_id
            $targetMasjidId = $user->masjid_id ?? null;
            
            // For non-Super Admin, masjid_id is required
            if (!$targetMasjidId && !$user->isSuperAdmin()) {
                return false;
            }
        }

        return static::withoutMasjidScope()->updateOrCreate(
            [
                'masjid_id' => $targetMasjidId, // Can be null for Super Admin personal
                'kunci' => $kunci
            ],
            [
                'nama' => $nama ?? ucfirst(str_replace('_', ' ', $kunci)),
                'nilai' => $nilai,
                'jenis' => 'text',
                'kategori' => 'custom',
                'susunan' => 999,
                'boleh_edit' => true,
                'updated_by' => $user->id,
                'created_by' => $user->id
            ]
        );
    }

    // Helper methods for common settings
    public static function getSystemName($masjidId = null)
    {
        return static::get('nama_sistem', 'E-Masjid', $masjidId);
    }

    public static function getSystemVersion($masjidId = null)
    {
        return static::get('versi_sistem', '1.0.0', $masjidId);
    }

    public static function getSystemAddress($masjidId = null)
    {
        return static::get('alamat_sistem', '', $masjidId);
    }

    public static function getDefaultLatitude($masjidId = null)
    {
        return static::get('default_latitude', 2.3000, $masjidId);
    }

    public static function getDefaultLongitude($masjidId = null)
    {
        return static::get('default_longitude', 111.8167, $masjidId);
    }

    public static function getPrayerZone($masjidId = null)
    {
        return static::get('prayer_zone', 'SWK08', $masjidId);
    }

    // System settings helper methods
    public static function getMaxLoginAttempts($masjidId = null)
    {
        return static::get('max_login_attempts', 5, $masjidId);
    }

    public static function getSessionTimeout($masjidId = null)
    {
        return static::get('session_timeout', 60, $masjidId);
    }

    // reCAPTCHA helper methods
    public static function getRecaptchaSiteKey($masjidId = null)
    {
        return static::get('recaptcha_site_key', '', $masjidId);
    }

    public static function getRecaptchaSecretKey($masjidId = null)
    {
        return static::get('recaptcha_secret_key', '', $masjidId);
    }

    public static function isRecaptchaEnabled($masjidId = null)
    {
        return static::get('recaptcha_enabled', false, $masjidId);
    }
}
