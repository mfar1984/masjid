<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ApiConfiguration;

class ApiConfigurationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ApiConfiguration::updateOrCreate(
            ['id' => 1],
            [
                'base_url' => url('/'),
                'version' => 'v1',
                'auth_type' => 'Bearer Token (Laravel Sanctum)',
                'rate_limit' => 0, // Unlimited
                'timeout' => 30,
                'max_retries' => 3,
                'ssl_verification' => 'enabled',
                'logging_level' => 'Info',
                'token_default_expiry' => '6h',
                'allowed_origins' => 'https://www.e-masjid.my, https://e-masjid.com.my',
                'default_abilities' => json_encode([
                    'read:overview',
                    'read:integrations',
                    'read:system_health'
                ]),
                'token_name' => 'e_masjid_api',
            ]
        );
    }
}
