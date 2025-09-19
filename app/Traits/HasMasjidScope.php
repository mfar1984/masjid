<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait HasMasjidScope
{
    /**
     * Boot the trait
     */
    protected static function bootHasMasjidScope()
    {
        // Auto-apply masjid scope for non-Super Admin users
        static::addGlobalScope('masjid', function (Builder $builder) {
            $user = auth()->user();
            
            if ($user && !$user->isSuperAdmin()) {
                $builder->where('masjid_id', $user->masjid_id);
            }
        });
    }

    /**
     * Remove masjid scope
     */
    public function scopeWithoutMasjidScope($query)
    {
        return $query->withoutGlobalScope('masjid');
    }
}
