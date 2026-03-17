<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentAccessRequest extends Model
{
    protected $fillable = [
        'share_token',
        'item_type',
        'item_id',
        'requester_masjid_id',
        'requester_user_id',
        'reason',
        'requested_permission',
        'status',
        'reviewed_by_user_id',
        'review_notes',
        'reviewed_at'
    ];

    protected $casts = [
        'reviewed_at' => 'datetime'
    ];

    // Relationships
    public function requesterMasjid()
    {
        return $this->belongsTo(Masjid::class, 'requester_masjid_id');
    }

    public function requesterUser()
    {
        return $this->belongsTo(User::class, 'requester_user_id');
    }

    public function reviewedByUser()
    {
        return $this->belongsTo(User::class, 'reviewed_by_user_id');
    }
}
