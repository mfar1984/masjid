<?php

namespace App\Models;

use App\Traits\HasMasjidScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Storage;

class Document extends Model
{
    use HasFactory, HasMasjidScope;

    protected $fillable = [
        'name',
        'description',
        'original_filename',
        'file_path',
        'file_extension',
        'mime_type',
        'file_size',
        'file_hash',
        'folder_id',
        'metadata',
        'is_starred',
        'is_shared',
        'download_count',
        'last_accessed_at',
        'version',
        'parent_document_id',
        'masjid_id', // WAJIB untuk data isolation
        'created_by',
        'updated_by',
        'status',
        'deleted_at',
        'deleted_by',
    ];

    protected $casts = [
        'metadata' => 'array',
        'is_starred' => 'boolean',
        'is_shared' => 'boolean',
        'download_count' => 'integer',
        'version' => 'integer',
        'file_size' => 'integer',
        'last_accessed_at' => 'datetime',
        'deleted_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // WAJIB: Relationship dengan Masjid
    public function masjid(): BelongsTo
    {
        return $this->belongsTo(Masjid::class);
    }

    // Folder relationship
    public function folder(): BelongsTo
    {
        return $this->belongsTo(DocumentFolder::class, 'folder_id');
    }

    // Version control relationships
    public function parentDocument(): BelongsTo
    {
        return $this->belongsTo(Document::class, 'parent_document_id');
    }

    public function versions(): HasMany
    {
        return $this->hasMany(Document::class, 'parent_document_id')->orderBy('version', 'desc');
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
    public function scopeStarred($query)
    {
        return $query->where('is_starred', true);
    }

    public function scopeShared($query)
    {
        return $query->where('is_shared', true);
    }

    public function scopeByExtension($query, $extension)
    {
        return $query->where('file_extension', $extension);
    }

    public function scopeByMimeType($query, $mimeType)
    {
        return $query->where('mime_type', 'like', $mimeType . '%');
    }

    public function scopeRecent($query, $days = 7)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
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
    public function getFileSizeHumanAttribute(): string
    {
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }

    public function getFileUrlAttribute(): string
    {
        return Storage::url($this->file_path);
    }

    public function getDownloadUrlAttribute(): string
    {
        return route('documents.download', $this->id);
    }

    public function getPreviewUrlAttribute(): ?string
    {
        // Only certain file types can be previewed
        $previewableTypes = ['image/', 'application/pdf', 'text/'];

        foreach ($previewableTypes as $type) {
            if (str_starts_with($this->mime_type, $type)) {
                // Check if file actually exists before returning preview URL
                if (Storage::exists('public/' . $this->file_path)) {
                    return route('documents.preview', $this->id);
                }
                // For test data without actual files, return placeholder
                return null;
            }
        }

        return null;
    }

    public function isImage(): bool
    {
        return str_starts_with($this->mime_type, 'image/');
    }

    public function isPdf(): bool
    {
        return $this->mime_type === 'application/pdf';
    }

    public function isText(): bool
    {
        return str_starts_with($this->mime_type, 'text/');
    }

    public function isPreviewable(): bool
    {
        return $this->isImage() || $this->isPdf() || $this->isText();
    }

    public function incrementDownloadCount(): void
    {
        $this->increment('download_count');
        $this->update(['last_accessed_at' => now()]);
    }

    public function createVersion(array $data): Document
    {
        $data['parent_document_id'] = $this->id;
        $data['version'] = $this->versions()->max('version') + 1;
        $data['masjid_id'] = $this->masjid_id;
        $data['folder_id'] = $this->folder_id;

        return Document::create($data);
    }

    public function getLatestVersion(): Document
    {
        return $this->versions()->first() ?? $this;
    }
}
