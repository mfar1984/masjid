<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Str;

class DocumentShare extends Model
{
    use HasFactory;

    protected $fillable = [
        'shareable_type',
        'shareable_id',
        'shared_by_masjid_id',
        'shared_by_user_id',
        'shared_with_masjid_id',
        'shared_with_user_id',
        'permission_level',
        'can_download',
        'can_share_further',
        'notify_on_access',
        'share_token',
        'expires_at',
        'is_public_link',
        'password_hash',
        'access_count',
        'last_accessed_at',
        'first_accessed_at',
        'status',
    ];

    protected $casts = [
        'can_download' => 'boolean',
        'can_share_further' => 'boolean',
        'notify_on_access' => 'boolean',
        'is_public_link' => 'boolean',
        'access_count' => 'integer',
        'expires_at' => 'datetime',
        'last_accessed_at' => 'datetime',
        'first_accessed_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Polymorphic relationship to shareable item (Document or DocumentFolder)
    public function shareable(): MorphTo
    {
        return $this->morphTo();
    }

    // Masjid relationships
    public function sharedByMasjid(): BelongsTo
    {
        return $this->belongsTo(Masjid::class, 'shared_by_masjid_id');
    }

    public function sharedWithMasjid(): BelongsTo
    {
        return $this->belongsTo(Masjid::class, 'shared_with_masjid_id');
    }

    // User relationships
    public function sharedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'shared_by_user_id');
    }

    public function sharedWithUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'shared_with_user_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeNotExpired($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('expires_at')
              ->orWhere('expires_at', '>', now());
        });
    }

    public function scopeForMasjid($query, $masjidId)
    {
        return $query->where('shared_with_masjid_id', $masjidId);
    }

    public function scopeByToken($query, $token)
    {
        return $query->where('share_token', $token);
    }

    public function scopePublicLinks($query)
    {
        return $query->where('is_public_link', true);
    }

    // Helper methods
    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    public function isActive(): bool
    {
        return $this->status === 'active' && !$this->isExpired();
    }

    public function canAccess(User $user): bool
    {
        if (!$this->isActive()) {
            return false;
        }

        // Check if user is from the target masjid
        if ($this->shared_with_masjid_id !== $user->masjid_id) {
            return false;
        }

        // If shared with specific user, check user match
        if ($this->shared_with_user_id && $this->shared_with_user_id !== $user->id) {
            return false;
        }

        return true;
    }

    public function canDownload(User $user): bool
    {
        return $this->canAccess($user) && $this->can_download;
    }

    public function canEdit(User $user): bool
    {
        return $this->canAccess($user) && in_array($this->permission_level, ['edit', 'full_access']);
    }

    public function canShareFurther(User $user): bool
    {
        return $this->canAccess($user) && $this->can_share_further;
    }

    public function generateShareToken(): string
    {
        $this->share_token = Str::random(64);
        $this->save();

        return $this->share_token;
    }

    public function getPublicShareUrl(): string
    {
        if (!$this->share_token) {
            $this->generateShareToken();
        }

        return route('documents.public-share', $this->share_token);
    }

    public function recordAccess(?User $user = null): void
    {
        $this->increment('access_count');

        $now = now();
        $this->last_accessed_at = $now;

        if (!$this->first_accessed_at) {
            $this->first_accessed_at = $now;
        }

        $this->save();

        // Send notification if enabled
        if ($this->notify_on_access && $user) {
            // TODO: Implement notification system
        }
    }

    public function revoke(): void
    {
        $this->status = 'revoked';
        $this->save();
    }

    public function getPermissionLevelTextAttribute(): string
    {
        return match($this->permission_level) {
            'view' => 'Lihat Sahaja',
            'comment' => 'Lihat & Komen',
            'edit' => 'Lihat & Edit',
            'full_access' => 'Akses Penuh',
            default => 'Tidak Diketahui'
        };
    }

    public function getStatusTextAttribute(): string
    {
        return match($this->status) {
            'active' => 'Aktif',
            'revoked' => 'Dibatalkan',
            'expired' => 'Tamat Tempoh',
            default => 'Tidak Diketahui'
        };
    }
}
