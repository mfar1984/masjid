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
        'parent_folder_id',
        'path',
        'sort_order',
        'is_shared',
        'is_starred',
        'masjid_id', // WAJIB untuk data isolation
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'is_shared' => 'boolean',
        'is_starred' => 'boolean',
        'sort_order' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

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
        return $query->where('is_shared', true);
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
}
