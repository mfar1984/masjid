<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

trait MasjidScope
{
    /**
     * Boot the MasjidScope trait for a model.
     */
    protected static function bootMasjidScope()
    {
        // Apply global scope to automatically filter by masjid_id
        static::addGlobalScope('masjid', function (Builder $builder) {
            $user = auth()->user();
            
            if ($user) {
                // Super Admin can see all data
                if ($user->isSuperAdmin()) {
                    // No filtering for Super Admin
                    return;
                }
                
                // Admin Masjid and other roles can only see their masjid data
                if ($user->masjid_id) {
                    $builder->where('masjid_id', $user->masjid_id);
                } else {
                    // If user has no masjid_id, show no data
                    $builder->whereRaw('1 = 0');
                }
            }
        });

        // Automatically set masjid_id when creating new records
        static::creating(function (Model $model) {
            $user = auth()->user();
            
            if ($user && $user->masjid_id && !$model->masjid_id) {
                $model->masjid_id = $user->masjid_id;
            }
            
            // Set created_by if the field exists
            if ($user && $model->isFillable('created_by') && !$model->created_by) {
                $model->created_by = $user->id;
            }
        });

        // Automatically set updated_by when updating records
        static::updating(function (Model $model) {
            $user = auth()->user();
            
            // Set updated_by if the field exists
            if ($user && $model->isFillable('updated_by')) {
                $model->updated_by = $user->id;
            }
        });
    }

    /**
     * Scope to get records for a specific masjid (for Super Admin use)
     */
    public function scopeForMasjid(Builder $query, $masjidId)
    {
        return $query->withoutGlobalScope('masjid')->where('masjid_id', $masjidId);
    }

    /**
     * Scope to get all records without masjid filtering (for Super Admin use)
     */
    public function scopeWithoutMasjidScope(Builder $query)
    {
        return $query->withoutGlobalScope('masjid');
    }

    /**
     * Get the masjid relationship
     */
    public function masjid()
    {
        return $this->belongsTo(\App\Models\Masjid::class);
    }
}
