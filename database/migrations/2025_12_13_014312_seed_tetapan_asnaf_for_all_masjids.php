<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $masjids = DB::table('masjids')->pluck('id');
        
        foreach ($masjids as $masjidId) {
            // Check if already has settings
            $existingCount = DB::table('tetapan_asnaf')
                ->where('masjid_id', $masjidId)
                ->count();
            
            if ($existingCount > 0) {
                continue; // Skip if already has settings
            }
            
            $settings = [
                // Had Kifayah (7 settings)
                ['setting_key' => 'had_kifayah_individu', 'setting_value' => '1200', 'setting_type' => 'number', 'category' => 'had_kifayah'],
                ['setting_key' => 'had_kifayah_pasangan', 'setting_value' => '1800', 'setting_type' => 'number', 'category' => 'had_kifayah'],
                ['setting_key' => 'had_kifayah_anak', 'setting_value' => '400', 'setting_type' => 'number', 'category' => 'had_kifayah'],
                ['setting_key' => 'had_kifayah_tanggungan', 'setting_value' => '300', 'setting_type' => 'number', 'category' => 'had_kifayah'],
                ['setting_key' => 'had_kifayah_max_anak', 'setting_value' => '8', 'setting_type' => 'number', 'category' => 'had_kifayah'],
                ['setting_key' => 'had_kifayah_max_tanggungan', 'setting_value' => '4', 'setting_type' => 'number', 'category' => 'had_kifayah'],
                ['setting_key' => 'had_kifayah_auto_calculate', 'setting_value' => 'true', 'setting_type' => 'boolean', 'category' => 'had_kifayah'],
                
                // Had Bantuan (8 settings)
                ['setting_key' => 'fakir_percentage', 'setting_value' => '25', 'setting_type' => 'number', 'category' => 'had_bantuan'],
                ['setting_key' => 'miskin_percentage', 'setting_value' => '25', 'setting_type' => 'number', 'category' => 'had_bantuan'],
                ['setting_key' => 'amil_percentage', 'setting_value' => '12.5', 'setting_type' => 'number', 'category' => 'had_bantuan'],
                ['setting_key' => 'muallaf_percentage', 'setting_value' => '12.5', 'setting_type' => 'number', 'category' => 'had_bantuan'],
                ['setting_key' => 'riqab_percentage', 'setting_value' => '5', 'setting_type' => 'number', 'category' => 'had_bantuan'],
                ['setting_key' => 'gharimin_percentage', 'setting_value' => '10', 'setting_type' => 'number', 'category' => 'had_bantuan'],
                ['setting_key' => 'fisabilillah_percentage', 'setting_value' => '5', 'setting_type' => 'number', 'category' => 'had_bantuan'],
                ['setting_key' => 'ibnu_sabil_percentage', 'setting_value' => '5', 'setting_type' => 'number', 'category' => 'had_bantuan'],
                
                // Workflow (6 settings)
                ['setting_key' => 'require_mesyuarat_approval', 'setting_value' => 'true', 'setting_type' => 'boolean', 'category' => 'workflow'],
                ['setting_key' => 'require_mesyuarat_attachment', 'setting_value' => 'true', 'setting_type' => 'boolean', 'category' => 'workflow'],
                ['setting_key' => 'auto_approve_enabled', 'setting_value' => 'false', 'setting_type' => 'boolean', 'category' => 'workflow'],
                ['setting_key' => 'auto_approve_amount', 'setting_value' => '0', 'setting_type' => 'number', 'category' => 'workflow'],
                ['setting_key' => 'notification_enabled', 'setting_value' => 'true', 'setting_type' => 'boolean', 'category' => 'workflow'],
                ['setting_key' => 'notification_methods', 'setting_value' => '["email"]', 'setting_type' => 'json', 'category' => 'workflow'],
                
                // Permohonan (7 settings)
                ['setting_key' => 'max_permohonan_per_year', 'setting_value' => '0', 'setting_type' => 'number', 'category' => 'permohonan'],
                ['setting_key' => 'allow_adhoc_agihan', 'setting_value' => 'true', 'setting_type' => 'boolean', 'category' => 'permohonan'],
                ['setting_key' => 'require_supporting_docs', 'setting_value' => 'true', 'setting_type' => 'boolean', 'category' => 'permohonan'],
                ['setting_key' => 'min_days_between_applications', 'setting_value' => '30', 'setting_type' => 'number', 'category' => 'permohonan'],
                ['setting_key' => 'allowed_file_types', 'setting_value' => '["pdf","jpg","jpeg","png"]', 'setting_type' => 'json', 'category' => 'permohonan'],
                ['setting_key' => 'max_file_size_mb', 'setting_value' => '5', 'setting_type' => 'number', 'category' => 'permohonan'],
                ['setting_key' => 'admin_only_create', 'setting_value' => 'true', 'setting_type' => 'boolean', 'category' => 'permohonan'],
                
                // Kategori Asnaf (8 settings)
                ['setting_key' => 'enable_fakir', 'setting_value' => 'true', 'setting_type' => 'boolean', 'category' => 'kategori_asnaf'],
                ['setting_key' => 'enable_miskin', 'setting_value' => 'true', 'setting_type' => 'boolean', 'category' => 'kategori_asnaf'],
                ['setting_key' => 'enable_amil', 'setting_value' => 'true', 'setting_type' => 'boolean', 'category' => 'kategori_asnaf'],
                ['setting_key' => 'enable_muallaf', 'setting_value' => 'true', 'setting_type' => 'boolean', 'category' => 'kategori_asnaf'],
                ['setting_key' => 'enable_riqab', 'setting_value' => 'true', 'setting_type' => 'boolean', 'category' => 'kategori_asnaf'],
                ['setting_key' => 'enable_gharimin', 'setting_value' => 'true', 'setting_type' => 'boolean', 'category' => 'kategori_asnaf'],
                ['setting_key' => 'enable_fisabilillah', 'setting_value' => 'true', 'setting_type' => 'boolean', 'category' => 'kategori_asnaf'],
                ['setting_key' => 'enable_ibnu_sabil', 'setting_value' => 'true', 'setting_type' => 'boolean', 'category' => 'kategori_asnaf'],
                
                // Payment Gateway (6 settings)
                ['setting_key' => 'chipasia_enabled', 'setting_value' => 'false', 'setting_type' => 'boolean', 'category' => 'payment_gateway'],
                ['setting_key' => 'chipasia_brand_id', 'setting_value' => '', 'setting_type' => 'encrypted', 'category' => 'payment_gateway'],
                ['setting_key' => 'chipasia_api_key', 'setting_value' => '', 'setting_type' => 'encrypted', 'category' => 'payment_gateway'],
                ['setting_key' => 'bank_name', 'setting_value' => '', 'setting_type' => 'string', 'category' => 'payment_gateway'],
                ['setting_key' => 'bank_account_number', 'setting_value' => '', 'setting_type' => 'string', 'category' => 'payment_gateway'],
                ['setting_key' => 'bank_account_name', 'setting_value' => '', 'setting_type' => 'string', 'category' => 'payment_gateway'],
                
                // Display Settings (5 settings)
                ['setting_key' => 'show_asnaf_on_website', 'setting_value' => 'false', 'setting_type' => 'boolean', 'category' => 'display_settings'],
                ['setting_key' => 'show_donation_form', 'setting_value' => 'false', 'setting_type' => 'boolean', 'category' => 'display_settings'],
                ['setting_key' => 'show_zakat_calculator', 'setting_value' => 'true', 'setting_type' => 'boolean', 'category' => 'display_settings'],
                ['setting_key' => 'records_per_page', 'setting_value' => '10', 'setting_type' => 'number', 'category' => 'display_settings'],
                ['setting_key' => 'date_format', 'setting_value' => 'd/m/Y', 'setting_type' => 'string', 'category' => 'display_settings'],
            ];
            
            foreach ($settings as $setting) {
                DB::table('tetapan_asnaf')->insert([
                    'masjid_id' => $masjidId,
                    'setting_key' => $setting['setting_key'],
                    'setting_value' => $setting['setting_value'],
                    'setting_type' => $setting['setting_type'],
                    'category' => $setting['category'],
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Delete seeded settings for masjids except masjid_id = 1
        DB::table('tetapan_asnaf')
            ->where('masjid_id', '!=', 1)
            ->delete();
    }
};
