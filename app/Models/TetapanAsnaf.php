<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class TetapanAsnaf extends Model
{
    protected $table = 'tetapan_asnaf';

    protected $fillable = [
        'masjid_id',
        'setting_key',
        'setting_value',
        'setting_type',
        'category',
        'description',
        'is_active',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relationships
    public function masjid()
    {
        return $this->belongsTo(Masjid::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // Helper method to get setting value
    public static function get($key, $default = null, $masjidId = null)
    {
        $masjidId = $masjidId ?? auth()->user()?->masjid_id;

        $setting = self::where('setting_key', $key)
            ->where('masjid_id', $masjidId)
            ->where('is_active', true)
            ->first();

        if (!$setting) {
            return $default;
        }

        // Decrypt if encrypted
        if ($setting->setting_type === 'encrypted') {
            try {
                return Crypt::decryptString($setting->setting_value);
            } catch (\Exception $e) {
                return $default;
            }
        }

        // Parse JSON if json type
        if ($setting->setting_type === 'json') {
            return json_decode($setting->setting_value, true);
        }

        // Convert boolean
        if ($setting->setting_type === 'boolean') {
            return filter_var($setting->setting_value, FILTER_VALIDATE_BOOLEAN);
        }

        // Convert number
        if ($setting->setting_type === 'number') {
            return is_numeric($setting->setting_value) ? (float) $setting->setting_value : $default;
        }

        return $setting->setting_value;
    }

    // Helper method to set setting value
    public static function set($key, $value, $masjidId = null, $type = 'string', $category = null)
    {
        $masjidId = $masjidId ?? auth()->user()?->masjid_id;
        $userId = auth()->id();

        // Encrypt if type is encrypted
        if ($type === 'encrypted') {
            $value = Crypt::encryptString($value);
        }

        // Encode if type is json
        if ($type === 'json' && is_array($value)) {
            $value = json_encode($value);
        }

        // Convert boolean to string
        if ($type === 'boolean') {
            $value = $value ? '1' : '0';
        }

        return self::updateOrCreate(
            [
                'masjid_id' => $masjidId,
                'setting_key' => $key,
            ],
            [
                'setting_value' => $value,
                'setting_type' => $type,
                'category' => $category,
                'updated_by' => $userId,
                'created_by' => $userId,
            ]
        );
    }

    // Get all settings by category
    public static function getByCategory($category, $masjidId = null)
    {
        $masjidId = $masjidId ?? auth()->user()?->masjid_id;

        return self::where('category', $category)
            ->where('masjid_id', $masjidId)
            ->where('is_active', true)
            ->get()
            ->mapWithKeys(function ($setting) {
                $value = $setting->setting_value;

                // Decrypt if encrypted
                if ($setting->setting_type === 'encrypted') {
                    try {
                        $value = Crypt::decryptString($value);
                    } catch (\Exception $e) {
                        $value = null;
                    }
                }

                // Parse JSON
                if ($setting->setting_type === 'json') {
                    $value = json_decode($value, true);
                }

                // Convert boolean
                if ($setting->setting_type === 'boolean') {
                    $value = filter_var($value, FILTER_VALIDATE_BOOLEAN);
                }

                // Convert number
                if ($setting->setting_type === 'number') {
                    $value = is_numeric($value) ? (float) $value : null;
                }

                return [$setting->setting_key => $value];
            });
    }
}
