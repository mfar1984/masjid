<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use App\Models\TempahanFasiliti;
use App\Models\PergerakanAset;
use App\Models\SenariAset;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Sync existing approved tempahan to create pergerakan aset records
     */
    public function up(): void
    {
        // Get all tempahan that are Lulus or Selesai but have no pergerakan
        $tempahanList = TempahanFasiliti::with('activeItems.senariFasiliti')
            ->whereIn('status_tempahan', ['Lulus', 'Selesai'])
            ->whereDoesntHave('pergerakanAset')
            ->get();

        foreach ($tempahanList as $tempahan) {
            foreach ($tempahan->activeItems as $item) {
                $fasiliti = $item->senariFasiliti;
                
                // Skip if fasiliti has no linked aset
                if (!$fasiliti || !$fasiliti->senarai_aset_id) {
                    continue;
                }

                $aset = SenariAset::find($fasiliti->senarai_aset_id);
                if (!$aset) {
                    continue;
                }

                // Determine jenis_pergerakan based on tempahan type
                $jenisPergerakan = 'Pinjaman';
                if ($tempahan->harga_sewa > 0) {
                    $jenisPergerakan = 'Sewa';
                }

                // Determine status based on tempahan status
                $statusPulangan = 'Belum Pulang';
                $tarikhSebenarPulangan = null;
                $kuantitiDipulangkan = 0;
                
                if ($tempahan->status_tempahan === 'Selesai' || $tempahan->status_pemulangan === 'Sudah Pulang') {
                    $statusPulangan = 'Sudah Pulang';
                    $tarikhSebenarPulangan = $tempahan->tarikh_sebenar_pulangan ?? now();
                    $kuantitiDipulangkan = $item->quantity;
                } elseif ($tempahan->tarikh_tamat && $tempahan->tarikh_tamat->isPast()) {
                    $statusPulangan = 'Lewat';
                }

                PergerakanAset::create([
                    'masjid_id' => $tempahan->masjid_id,
                    'no_pergerakan' => PergerakanAset::generateNoPergerakan($tempahan->masjid_id),
                    'senarai_aset_id' => $fasiliti->senarai_aset_id,
                    'tempahan_fasiliti_id' => $tempahan->id,
                    'tempahan_fasiliti_item_id' => $item->id,
                    'kuantiti' => $item->quantity,
                    'kuantiti_dipulangkan' => $kuantitiDipulangkan,
                    'tarikh_pergerakan' => $tempahan->tarikh_mula,
                    'jenis_pergerakan' => $jenisPergerakan,
                    'lokasi_asal' => $aset->lokasi_semasa ?? 'Masjid',
                    'is_lokasi_luaran' => $tempahan->is_lokasi_luaran ?? false,
                    'lokasi_destinasi' => $tempahan->lokasi_destinasi,
                    'nama_tempat_luaran' => $tempahan->nama_tempat_luaran,
                    'alamat_luaran_1' => $tempahan->alamat_luaran_1,
                    'alamat_luaran_2' => $tempahan->alamat_luaran_2,
                    'poskod_luaran' => $tempahan->poskod_luaran,
                    'bandar_luaran' => $tempahan->bandar_luaran,
                    'negeri_luaran' => $tempahan->negeri_luaran,
                    'nama_peminjam' => $tempahan->nama_penyewa,
                    'no_ic_peminjam' => $tempahan->no_ic_penyewa,
                    'no_telefon_peminjam' => $tempahan->no_telefon_penyewa,
                    'organisasi_peminjam' => $tempahan->organisasi_penyewa,
                    'tarikh_jangka_pulangan' => $tempahan->tarikh_tamat,
                    'tarikh_sebenar_pulangan' => $tarikhSebenarPulangan,
                    'status_pulangan' => $statusPulangan,
                    'kondisi_sebelum' => $aset->kondisi_aset ?? 'Baik',
                    'kondisi_selepas' => $statusPulangan === 'Sudah Pulang' ? 'Baik' : null,
                    'sebab_pergerakan' => $tempahan->tujuan_tempahan,
                    'catatan' => "Dicipta automatik dari Tempahan: {$tempahan->no_tempahan} (sync existing data)",
                    'created_by' => $tempahan->created_by,
                    'updated_by' => $tempahan->updated_by,
                ]);

                // Update item status_pulangan
                $item->update([
                    'kuantiti_dipulangkan' => $kuantitiDipulangkan,
                    'status_pulangan' => $statusPulangan,
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Delete pergerakan that were created from tempahan sync
        PergerakanAset::whereNotNull('tempahan_fasiliti_id')
            ->where('catatan', 'like', '%sync existing data%')
            ->delete();
    }
};
