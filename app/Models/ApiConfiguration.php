<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApiConfiguration extends Model
{
    use HasFactory;

    protected $table = 'api_configurations';

    protected $fillable = [
        'base_url',
        'version',
        'auth_type',
        'access_token',
        'rate_limit',
        'timeout',
        'max_retries',
        'ssl_verification',
        'logging_level',
        'token_default_expiry',
        'allowed_origins',
        'default_abilities',
        'token_name',
    ];

    protected $casts = [
        'default_abilities' => 'array',
    ];

    /**
     * Get formatted rate limit
     */
    public function getFormattedRateLimitAttribute()
    {
        if ($this->rate_limit == 0) {
            return 'Unlimited';
        }
        return $this->rate_limit . ' requests/hour';
    }

    /**
     * Get formatted timeout
     */
    public function getFormattedTimeoutAttribute()
    {
        return $this->timeout . ' saat';
    }

    /**
     * Get formatted token expiry
     */
    public function getFormattedTokenExpiryAttribute()
    {
        $expiry = $this->token_default_expiry;
        
        switch ($expiry) {
            case '15m':
                return '15 minit';
            case '1h':
                return '1 jam';
            case '6h':
                return '6 jam';
            case '24h':
                return '24 jam';
            case '7d':
                return '7 hari';
            case 'never':
                return 'Tiada tamat tempoh';
            default:
                return $expiry ?? '6 jam';
        }
    }

    /**
     * Get available abilities options
     */
    public function getAbilitiesOptionsAttribute()
    {
        return [
            // Dashboard
            'read:overview' => 'read:overview',
            // Pengurusan
            'read:kumpulan' => 'read:kumpulan',
            'write:kumpulan' => 'write:kumpulan',
            'read:ahli' => 'read:ahli',
            'write:ahli' => 'write:ahli',
            'read:aktiviti' => 'read:aktiviti',
            'write:aktiviti' => 'write:aktiviti',
            'read:kewangan' => 'read:kewangan',
            'write:kewangan' => 'write:kewangan',
            // Pentadbiran Sistem
            'read:tetapan' => 'read:tetapan',
            'write:tetapan' => 'write:tetapan',
            'read:integrations' => 'read:integrations',
            'write:integrations' => 'write:integrations',
            'read:integrations_email' => 'read:integrations_email',
            'write:integrations_email' => 'write:integrations_email',
            'read:integrations_weather' => 'read:integrations_weather',
            'write:integrations_weather' => 'write:integrations_weather',
            'read:integrations_api' => 'read:integrations_api',
            'write:integrations_api' => 'write:integrations_api',
            // Pengguna
            'read:users' => 'read:users',
            'write:users' => 'write:users',
            'read:roles' => 'read:roles',
            'write:roles' => 'write:roles',
            'read:permissions' => 'read:permissions',
            'write:permissions' => 'write:permissions',
            'read:audit_logs' => 'read:audit_logs',
            // Profil
            'read:profile' => 'read:profile',
            'write:profile' => 'write:profile',
            // Admin
            'admin:all' => 'admin:all',
        ];
    }
}
