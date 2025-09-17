<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MasjidAttachment extends Model
{
    protected $fillable = [
        'masjid_id',
        'original_name',
        'file_path',
        'file_type',
        'file_size',
        'description'
    ];

    protected $casts = [
        'file_size' => 'integer',
    ];

    /**
     * Get the masjid that owns the attachment.
     */
    public function masjid(): BelongsTo
    {
        return $this->belongsTo(Masjid::class);
    }

    /**
     * Get the file size in human readable format.
     */
    public function getFormattedFileSizeAttribute(): string
    {
        $bytes = $this->file_size;
        if ($bytes === 0) return '0 Bytes';

        $k = 1024;
        $sizes = ['Bytes', 'KB', 'MB', 'GB'];
        $i = floor(log($bytes) / log($k));

        return round($bytes / pow($k, $i), 2) . ' ' . $sizes[$i];
    }

    /**
     * Get the file URL for download.
     */
    public function getFileUrlAttribute(): string
    {
        return asset('storage/' . $this->file_path);
    }

    /**
     * Check if file is an image.
     */
    public function getIsImageAttribute(): bool
    {
        return in_array(strtolower($this->file_type), ['png', 'jpeg', 'jpg']);
    }

    /**
     * Check if file is a PDF.
     */
    public function getIsPdfAttribute(): bool
    {
        return strtolower($this->file_type) === 'pdf';
    }
}
