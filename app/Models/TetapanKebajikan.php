<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TetapanKebajikan extends Model
{
    use HasFactory;

    protected $table = 'tetapan_kebajikan';

    public $timestamps = true;

    protected $fillable = [
        'masjid_id',
        'setting_key',
        'setting_value',
        'setting_type',
    ];

    // Relationships
    public function masjid()
    {
        return $this->belongsTo(Masjid::class);
    }

    // Helper methods
    public static function getSetting($masjidId, $key, $default = null)
    {
        $setting = self::where('masjid_id', $masjidId)
            ->where('setting_key', $key)
            ->first();

        return $setting ? $setting->setting_value : $default;
    }

    public static function setSetting($masjidId, $key, $value, $type = 'text')
    {
        return self::updateOrCreate(
            ['masjid_id' => $masjidId, 'setting_key' => $key],
            ['setting_value' => $value, 'setting_type' => $type]
        );
    }

    public static function getSettings($masjidId, array $keys)
    {
        $settings = self::where('masjid_id', $masjidId)
            ->whereIn('setting_key', $keys)
            ->pluck('setting_value', 'setting_key');

        $result = [];
        foreach ($keys as $key) {
            $result[$key] = $settings->get($key, '');
        }

        return $result;
    }
}
