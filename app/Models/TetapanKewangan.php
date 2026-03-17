<?php

namespace App\Models;

use App\Traits\HasMasjidScope;
use Illuminate\Database\Eloquent\Model;

class TetapanKewangan extends Model
{
    use HasMasjidScope;

    protected $table = 'tetapan_kewangan';

    protected $fillable = [
        'masjid_id',
        'setting_key',
        'setting_value',
        'setting_type',
    ];

    public $timestamps = true;

    // Relationships
    public function masjid()
    {
        return $this->belongsTo(Masjid::class);
    }

    // Helper Methods
    public static function get($key, $default = null, $masjidId = null)
    {
        if (!$masjidId) {
            $masjidId = auth()->user()->masjid_id ?? null;
        }

        if (!$masjidId) {
            return $default;
        }

        $setting = self::where('masjid_id', $masjidId)
            ->where('setting_key', $key)
            ->first();

        if (!$setting) {
            return $default;
        }

        return self::castValue($setting->setting_value, $setting->setting_type);
    }

    public static function set($key, $value, $type = 'text', $masjidId = null)
    {
        if (!$masjidId) {
            $masjidId = auth()->user()->masjid_id ?? null;
        }

        if (!$masjidId) {
            return false;
        }

        return self::updateOrCreate(
            [
                'masjid_id' => $masjidId,
                'setting_key' => $key,
            ],
            [
                'setting_value' => is_array($value) ? json_encode($value) : $value,
                'setting_type' => $type,
            ]
        );
    }

    private static function castValue($value, $type)
    {
        switch ($type) {
            case 'boolean':
                return filter_var($value, FILTER_VALIDATE_BOOLEAN);
            case 'number':
                return is_numeric($value) ? (float) $value : 0;
            case 'json':
                return json_decode($value, true);
            default:
                return $value;
        }
    }
}
