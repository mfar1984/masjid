<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Masjid extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombor_daftar',
        'nama',
        'nama_penuh',
        'kod_masjid',
        'alamat',
        'poskod',
        'bandar',
        'negeri',
        'telefon',
        'faks',
        'email',
        'laman_web',
        'latitude',
        'longitude',
        'kategori',
        'status',
        'tarikh_ditubuhkan',

        'kapasiti_jemaah',
        'pendaftar_nama',
        'pendaftar_telefon',
        'pendaftar_email',
        'pendaftar_jawatan',
        'diluluskan_oleh',
        'tarikh_diluluskan',
        'catatan_kelulusan',
        'settings',
        'logo_path',
        'attachment_path',
        'suspended_at',
        'suspended_by',
    ];

    protected $casts = [
        'settings' => 'array',
        'tarikh_ditubuhkan' => 'date',
        'tarikh_diluluskan' => 'datetime',
        'suspended_at' => 'datetime',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'bilangan_kariah' => 'integer',
        'kapasiti_jemaah' => 'integer',
    ];

    // Relationships
    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function roles()
    {
        return $this->hasMany(Role::class);
    }

    public function diluluskanOleh()
    {
        return $this->belongsTo(User::class, 'diluluskan_oleh');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeByNegeri($query, $negeri)
    {
        return $query->where('negeri', $negeri);
    }

    public function scopeByKategori($query, $kategori)
    {
        return $query->where('kategori', $kategori);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('nama', 'like', "%{$search}%")
              ->orWhere('nama_penuh', 'like', "%{$search}%")
              ->orWhere('kod_masjid', 'like', "%{$search}%")
              ->orWhere('alamat', 'like', "%{$search}%")
              ->orWhere('bandar', 'like', "%{$search}%")
              ->orWhere('negeri', 'like', "%{$search}%")
              ->orWhere('telefon', 'like', "%{$search}%");
        });
    }

    // Accessors
    public function getFullAddressAttribute()
    {
        $parts = array_filter([
            $this->alamat,
            $this->poskod,
            $this->bandar,
            $this->negeri
        ]);
        
        return implode(', ', $parts);
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'active' => '<span class="badge badge-success badge-sm" style="font-family: Poppins; font-size: 10px;">🟢 Aktif</span>',
            'pending' => '<span class="badge badge-warning badge-sm" style="font-family: Poppins; font-size: 10px;">🟡 Menunggu</span>',
            'rejected' => '<span class="badge badge-error badge-sm" style="font-family: Poppins; font-size: 10px;">❌ Ditolak</span>',
            'inactive' => '<span class="badge badge-neutral badge-sm" style="font-family: Poppins; font-size: 10px;">⚪ Tidak Aktif</span>',
            'suspended' => '<span class="badge badge-neutral badge-sm" style="font-family: Poppins; font-size: 10px;">⚫ Digantung</span>',
        ];

        return $badges[$this->status] ?? $badges['pending'];
    }

    public function getKategoriIconAttribute()
    {
        $icons = [
            'masjid' => '🕌',
            'surau' => '🏛️',
            'musolla' => '🏢',
        ];

        return $icons[$this->kategori] ?? $icons['masjid'];
    }



    // Methods
    public function approve($approvedBy, $catatan = null)
    {
        $this->update([
            'status' => 'active',
            'diluluskan_oleh' => $approvedBy,
            'tarikh_diluluskan' => now(),
            'catatan_kelulusan' => $catatan,
        ]);
    }

    public function generateKodMasjid()
    {
        if ($this->kod_masjid) {
            return $this->kod_masjid;
        }

        $negeriCode = $this->getNegeriCode($this->negeri);
        $sequence = static::where('negeri', $this->negeri)->count() + 1;
        $kodMasjid = $negeriCode . str_pad($sequence, 3, '0', STR_PAD_LEFT);
        
        $this->update(['kod_masjid' => $kodMasjid]);
        
        return $kodMasjid;
    }

    private function getNegeriCode($negeri)
    {
        $codes = [
            'Kuala Lumpur' => 'KL',
            'Selangor' => 'SLG',
            'Johor' => 'JHR',
            'Perak' => 'PRK',
            'Pulau Pinang' => 'PNG',
            'Kedah' => 'KDH',
            'Kelantan' => 'KTN',
            'Terengganu' => 'TRG',
            'Pahang' => 'PHG',
            'Negeri Sembilan' => 'NSN',
            'Melaka' => 'MLK',
            'Sabah' => 'SBH',
            'Sarawak' => 'SRW',
            'Perlis' => 'PLS',
            'Putrajaya' => 'PJY',
            'Labuan' => 'LBN',
        ];

        return $codes[$negeri] ?? 'MSJ';
    }

    /**
     * Get all attachments for this masjid.
     */
    public function attachments(): HasMany
    {
        return $this->hasMany(MasjidAttachment::class);
    }

    /**
     * Get the count of attachments.
     */
    public function getAttachmentsCountAttribute(): int
    {
        return $this->attachments()->count();
    }

    // Media Collections
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('logo')
            ->singleFile()
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/gif']);
    }
}
