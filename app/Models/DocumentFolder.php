<?php

namespace App\Models;

use App\Traits\HasMasjidScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class DocumentFolder extends Model
{
    use HasFactory, HasMasjidScope;

    protected $fillable = [
        'name',
        'description',
        'color',
        'hash_token',
        'parent_folder_id',
        'path',
        'sort_order',
        'is_shared',
        'is_starred',
        'masjid_id', // WAJIB untuk data isolation
        'created_by',
        'updated_by',
        'status',
        'deleted_at',
        'deleted_by',
    ];

    protected $casts = [
        'is_shared' => 'boolean',
        'is_starred' => 'boolean',
        'sort_order' => 'integer',
        'deleted_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Attributes that should not be saved to database (computed attributes)
     */
    protected $appends = [];

    /**
     * Override save method to prevent computed attributes from being saved
     */
    public function save(array $options = [])
    {
        // Remove computed attributes before saving
        $this->offsetUnset('total_documents');
        $this->offsetUnset('total_size');

        return parent::save($options);
    }

    // WAJIB: Relationship dengan Masjid
    public function masjid(): BelongsTo
    {
        return $this->belongsTo(Masjid::class);
    }

    // Parent folder relationship (self-referencing)
    public function parentFolder(): BelongsTo
    {
        return $this->belongsTo(DocumentFolder::class, 'parent_folder_id');
    }

    // Child folders relationship
    public function childFolders(): HasMany
    {
        return $this->hasMany(DocumentFolder::class, 'parent_folder_id')->orderBy('sort_order');
    }

    // Documents in this folder
    public function documents(): HasMany
    {
        return $this->hasMany(Document::class, 'folder_id')->orderBy('name');
    }

    // Sharing relationships (polymorphic)
    public function shares(): MorphMany
    {
        return $this->morphMany(DocumentShare::class, 'shareable');
    }

    // User relationships
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function deleter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }

    // Scopes
    public function scopeRootFolders($query)
    {
        return $query->whereNull('parent_folder_id');
    }

    public function scopeStarred($query)
    {
        return $query->where('is_starred', true);
    }

    public function scopeShared($query)
    {
        $user = auth()->user();

        if (!$user || $user->isSuperAdmin()) {
            // Super Admin sees no shared items (they own everything)
            return $query->whereRaw('1 = 0');
        }

        // Show folders shared WITH current user's masjid
        // Remove masjid global scope since we want to see items from OTHER masjids
        return $query->withoutGlobalScope('masjid')
                    ->whereHas('shares', function ($shareQuery) use ($user) {
                        $shareQuery->where('shared_with_masjid_id', $user->masjid_id)
                                  ->where('status', 'active')
                                  ->where(function ($q) {
                                      $q->whereNull('expires_at')
                                        ->orWhere('expires_at', '>', now());
                                  });
                    });
    }

    // Status scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeTrash($query)
    {
        return $query->where('status', 'trash');
    }

    public function scopeSpam($query)
    {
        return $query->where('status', 'spam');
    }

    // Helper methods
    public function getFullPathAttribute(): string
    {
        if ($this->path) {
            return $this->path;
        }

        $path = $this->name;
        $parent = $this->parentFolder;

        while ($parent) {
            $path = $parent->name . '/' . $path;
            $parent = $parent->parentFolder;
        }

        return $path;
    }

    public function updatePath(): void
    {
        $this->path = $this->getFullPathAttribute();
        $this->save();

        // Update all child folders' paths
        foreach ($this->childFolders as $child) {
            $child->updatePath();
        }
    }

    public function getTotalSize($userMasjidId = null): int
    {
        $query = Document::withoutGlobalScope('masjid')->where('folder_id', $this->id);

        // Apply masjid filtering based on user context
        if ($userMasjidId !== null) {
            // For specific masjid users, only count their documents
            $query->where('masjid_id', $userMasjidId);
        }
        // For Super Admin (userMasjidId = null), count all documents

        $size = $query->sum('file_size');

        foreach ($this->childFolders as $child) {
            $size += $child->getTotalSize($userMasjidId);
        }

        return $size;
    }

    public function getTotalDocuments($userMasjidId = null): int
    {
        $query = Document::withoutGlobalScope('masjid')->where('folder_id', $this->id);

        // Apply masjid filtering based on user context
        if ($userMasjidId !== null) {
            // For specific masjid users, only count their documents
            $query->where('masjid_id', $userMasjidId);
        }
        // For Super Admin (userMasjidId = null), count all documents

        $count = $query->count();

        foreach ($this->childFolders as $child) {
            $count += $child->getTotalDocuments($userMasjidId);
        }

        return $count;
    }

    /**
     * Generate unique hash token for Google Drive style URLs
     */
    public function generateHashToken(): string
    {
        do {
            $token = \Illuminate\Support\Str::random(32);
        } while (self::where('hash_token', $token)->exists());

        $this->hash_token = $token;
        $this->save();

        return $token;
    }

    /**
     * Get hash token, generate if not exists
     */
    public function getHashToken(): string
    {
        if (!$this->hash_token) {
            return $this->generateHashToken();
        }

        return $this->hash_token;
    }

    /**
     * Get Google Drive style URL
     */
    public function getPublicUrl(): string
    {
        return route('documents.folder', $this->getHashToken());
    }

    /**
     * Find folder by hash token
     */
    public static function findByHashToken(string $token): ?self
    {
        return self::where('hash_token', $token)->first();
    }
}
