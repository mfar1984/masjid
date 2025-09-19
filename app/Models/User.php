<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'masjid_id',
        'role_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the masjid this user belongs to.
     */
    public function masjid(): BelongsTo
    {
        return $this->belongsTo(Masjid::class);
    }

    /**
     * Get the role this user has.
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Check if user has a specific role name.
     */
    public function hasRole(string $roleName): bool
    {
        return $this->role && $this->role->name === $roleName;
    }

    /**
     * Check if user has any of the given roles.
     */
    public function hasAnyRole(array $roleNames): bool
    {
        return $this->role && in_array($this->role->name, $roleNames);
    }

    /**
     * Check if user is Super Admin.
     */
    public function isSuperAdmin(): bool
    {
        return $this->hasRole('Super Admin');
    }

    /**
     * Check if user is Admin Masjid.
     */
    public function isAdminMasjid(): bool
    {
        return $this->hasRole('Admin Masjid');
    }

    /**
     * Check if user has permission for specific module and action.
     */
    public function hasPermission(string $module, string $action): bool
    {
        // Super Admin has all permissions
        if ($this->isSuperAdmin()) {
            return true;
        }

        // Get user's role
        $role = $this->role;

        if (!$role || !$role->permissions) {
            return false;
        }

        // Check if role has permission for this module and action
        // Permission values can be boolean true, string "1", or integer 1
        return isset($role->permissions[$module][$action]) &&
               in_array($role->permissions[$module][$action], [true, "1", 1], true);
    }

    /**
     * Get status badge HTML for display.
     */
    public function getStatusBadgeAttribute(): string
    {
        if ($this->email_verified_at) {
            return '<span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-green-100 text-green-800">
                        <span class="material-icons mr-1" style="font-size: 12px !important;">verified_user</span>
                        Aktif
                    </span>';
        } else {
            return '<span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-orange-100 text-orange-800">
                        <span class="material-icons mr-1" style="font-size: 12px !important;">pending</span>
                        Pending
                    </span>';
        }
    }

    /**
     * Mark the given user's email as verified.
     */
    public function markEmailAsVerified(): bool
    {
        return $this->forceFill([
            'email_verified_at' => $this->freshTimestamp(),
        ])->save();
    }

    /**
     * Determine if the user has verified their email address.
     */
    public function hasVerifiedEmail(): bool
    {
        return ! is_null($this->email_verified_at);
    }

    /**
     * Check if user's role is active.
     */
    public function hasActiveRole(): bool
    {
        return $this->role && $this->role->is_active;
    }

    /**
     * Get user avatar initials.
     */
    public function getInitialsAttribute(): string
    {
        $words = explode(' ', $this->name);
        if (count($words) >= 2) {
            return strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1));
        }
        return strtoupper(substr($this->name, 0, 2));
    }

    /**
     * Get role icon based on user role.
     */
    public function getRoleIconAttribute(): string
    {
        if (!$this->role) {
            return '👤';
        }

        switch ($this->role->name) {
            case 'Super Admin':
                return '👑';
            case 'Admin Masjid':
                return '🕌';
            case 'Moderator':
                return '⚖️';
            case 'Staf':
                return '👨‍💼';
            default:
                return '👤';
        }
    }

    /**
     * Get the kariah records created by this user.
     */
    public function createdKariah(): HasMany
    {
        return $this->hasMany(Kariah::class, 'created_by');
    }

    /**
     * Get the kariah records updated by this user.
     */
    public function updatedKariah(): HasMany
    {
        return $this->hasMany(Kariah::class, 'updated_by');
    }

    /**
     * Get storage usage for this user's masjid
     */
    public function getStorageUsage(): array
    {
        if (!$this->masjid_id) {
            return [
                'used' => 0,
                'total' => 1073741824, // 1GB default
                'percentage' => 0,
                'formatted_used' => '0 B',
                'formatted_total' => '1 GB',
            ];
        }

        // Calculate total file size for this masjid
        $totalSize = Document::where('masjid_id', $this->masjid_id)->sum('file_size') ?? 0;
        $totalLimit = 1073741824; // 1GB limit per masjid
        $percentage = $totalLimit > 0 ? min(100, ($totalSize / $totalLimit) * 100) : 0;

        return [
            'used' => $totalSize,
            'total' => $totalLimit,
            'percentage' => round($percentage, 1),
            'formatted_used' => $this->formatFileSize($totalSize),
            'formatted_total' => $this->formatFileSize($totalLimit),
        ];
    }

    /**
     * Format file size in human readable format
     */
    private function formatFileSize($bytes): string
    {
        if ($bytes == 0) return '0 B';

        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $factor = floor(log($bytes, 1024));

        return round($bytes / pow(1024, $factor), 1) . ' ' . $units[$factor];
    }
}
