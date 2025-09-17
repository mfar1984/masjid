<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'permissions',
        'is_system_role',
        'is_active',
        'masjid_id',
    ];

    protected $casts = [
        'permissions' => 'array',
        'is_system_role' => 'boolean',
        'is_active' => 'boolean',
        'masjid_id' => 'integer',
    ];

    // Relationships
    public function masjid()
    {
        return $this->belongsTo(Masjid::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeSystemRoles($query)
    {
        return $query->where('is_system_role', true)->whereNull('masjid_id');
    }

    public function scopeCustomRoles($query)
    {
        return $query->where('is_system_role', false);
    }

    public function scopeGlobalRoles($query)
    {
        return $query->whereNull('masjid_id');
    }

    public function scopeForMasjid($query, $masjidId)
    {
        return $query->where('masjid_id', $masjidId);
    }

    public function scopeMasjidRoles($query, $masjidId = null)
    {
        if ($masjidId) {
            return $query->where('masjid_id', $masjidId);
        }
        return $query->whereNotNull('masjid_id');
    }

    // Accessors
    public function getStatusBadgeAttribute()
    {
        if ($this->is_active) {
            return '<span class="badge badge-success badge-sm" style="font-family: Poppins; font-size: 10px;">🟢 Aktif</span>';
        } else {
            return '<span class="badge badge-neutral badge-sm" style="font-family: Poppins; font-size: 10px;">⚪ Tidak Aktif</span>';
        }
    }

    public function getTypeIconAttribute()
    {
        if ($this->is_system_role) {
            return '🔒'; // System role
        } elseif ($this->masjid_id) {
            return '🕌'; // Masjid-specific role
        } else {
            return '👥'; // Global custom role
        }
    }

    public function getScopeTypeAttribute()
    {
        if ($this->is_system_role) {
            return 'System';
        } elseif ($this->masjid_id) {
            return 'Masjid';
        } else {
            return 'Global';
        }
    }

    public function getPermissionCountAttribute()
    {
        return is_array($this->permissions) ? count(array_filter($this->permissions, function($permission) {
            return is_array($permission) ? count(array_filter($permission)) > 0 : $permission;
        })) : 0;
    }
}
