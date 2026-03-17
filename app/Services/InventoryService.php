<?php

namespace App\Services;

use App\Models\TempahanFasiliti;
use App\Models\TempahanFasilitiItem;
use App\Models\PergerakanAset;
use App\Models\SenariFasiliti;
use App\Models\SenariAset;
use App\Models\TransaksiKewangan;
use App\Models\KategoriKewangan;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class InventoryService
{
    /**
     * Calculate available quantity for a fasiliti within date range
     * 
     * @param int $fasilitiId
     * @param string $tarikhMula
     * @param string $tarikhTamat
     * @param int|null $excludeTempahanId
     * @return int
     */
    public function getAvailableQuantity($fasilitiId, $tarikhMula, $tarikhTamat, $excludeTempahanId = null): int
    {
        $fasiliti = SenariFasiliti::find($fasilitiId);
        
        if (!$fasiliti) {
            return 0;
        }

        return $fasiliti->checkAvailability($tarikhMula, $tarikhTamat, $excludeTempahanId);
    }

    /**
     * Create pergerakan aset records when tempahan is approved
     * 
     * @param TempahanFasiliti $tempahan
     * @return Collection
     */
    public function createPergerakanFromTempahan(TempahanFasiliti $tempahan): Collection
    {
        $pergerakanRecords = collect();
        $userId = Auth::id();

        DB::transaction(function () use ($tempahan, &$pergerakanRecords, $userId) {
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

                $pergerakan = PergerakanAset::create([
                    'masjid_id' => $tempahan->masjid_id,
                    'no_pergerakan' => PergerakanAset::generateNoPergerakan($tempahan->masjid_id),
                    'senarai_aset_id' => $fasiliti->senarai_aset_id,
                    'tempahan_fasiliti_id' => $tempahan->id,
                    'tempahan_fasiliti_item_id' => $item->id,
                    'kuantiti' => $item->quantity,
                    'tarikh_pergerakan' => $tempahan->tarikh_mula,
                    'jenis_pergerakan' => $jenisPergerakan,
                    'lokasi_asal' => $aset->lokasi_semasa ?? 'Masjid',
                    // Copy lokasi destinasi from tempahan
                    'is_lokasi_luaran' => $tempahan->is_lokasi_luaran,
                    'lokasi_destinasi' => $tempahan->lokasi_destinasi,
                    'nama_tempat_luaran' => $tempahan->nama_tempat_luaran,
                    'alamat_luaran_1' => $tempahan->alamat_luaran_1,
                    'alamat_luaran_2' => $tempahan->alamat_luaran_2,
                    'poskod_luaran' => $tempahan->poskod_luaran,
                    'bandar_luaran' => $tempahan->bandar_luaran,
                    'negeri_luaran' => $tempahan->negeri_luaran,
                    // Peminjam info from penyewa
                    'nama_peminjam' => $tempahan->nama_penyewa,
                    'no_ic_peminjam' => $tempahan->no_ic_penyewa,
                    'no_telefon_peminjam' => $tempahan->no_telefon_penyewa,
                    'organisasi_peminjam' => $tempahan->organisasi_penyewa,
                    'tarikh_jangka_pulangan' => $tempahan->tarikh_tamat,
                    'status_pulangan' => 'Belum Pulang',
                    'kondisi_sebelum' => $aset->kondisi_aset ?? 'Baik',
                    'sebab_pergerakan' => $tempahan->tujuan_tempahan,
                    'catatan' => "Dicipta automatik dari Tempahan: {$tempahan->no_tempahan}",
                    'created_by' => $userId,
                    'updated_by' => $userId,
                ]);

                $pergerakanRecords->push($pergerakan);
            }
        });

        return $pergerakanRecords;
    }


    /**
     * Process return for a single pergerakan aset
     * 
     * @param int $pergerakanId
     * @param string $kondisiSelepas
     * @param string|null $catatan
     * @return bool
     */
    public function processReturn($pergerakanId, $kondisiSelepas, $catatan = null): bool
    {
        $pergerakan = PergerakanAset::find($pergerakanId);
        
        if (!$pergerakan) {
            return false;
        }

        DB::transaction(function () use ($pergerakan, $kondisiSelepas, $catatan) {
            $pergerakan->update([
                'status_pulangan' => 'Sudah Pulang',
                'tarikh_sebenar_pulangan' => now(),
                'kondisi_selepas' => $kondisiSelepas,
                'catatan' => $catatan ? $pergerakan->catatan . "\nPulangan: " . $catatan : $pergerakan->catatan,
                'updated_by' => Auth::id(),
            ]);

            // Update aset kondisi if changed
            if ($pergerakan->senariAset && $kondisiSelepas !== $pergerakan->kondisi_sebelum) {
                $pergerakan->senariAset->update([
                    'kondisi_aset' => $kondisiSelepas,
                    'updated_by' => Auth::id(),
                ]);
            }

            // If this pergerakan is from tempahan, check if all items returned
            if ($pergerakan->tempahan_fasiliti_id) {
                $this->updateTempahanStatusPemulangan($pergerakan->tempahanFasiliti);
            }
        });

        return true;
    }

    /**
     * Process return for all items in a tempahan
     * 
     * @param TempahanFasiliti $tempahan
     * @param string $kondisiSelepas
     * @param string|null $catatan
     * @return bool
     */
    public function processTempahanReturn(TempahanFasiliti $tempahan, $kondisiSelepas, $catatan = null): bool
    {
        DB::transaction(function () use ($tempahan, $kondisiSelepas, $catatan) {
            // Update all pergerakan records for this tempahan
            foreach ($tempahan->pergerakanAset as $pergerakan) {
                if ($pergerakan->status_pulangan !== 'Sudah Pulang') {
                    $pergerakan->update([
                        'status_pulangan' => 'Sudah Pulang',
                        'tarikh_sebenar_pulangan' => now(),
                        'kondisi_selepas' => $kondisiSelepas,
                        'catatan' => $catatan ? $pergerakan->catatan . "\nPulangan: " . $catatan : $pergerakan->catatan,
                        'updated_by' => Auth::id(),
                    ]);

                    // Update aset kondisi if changed
                    if ($pergerakan->senariAset && $kondisiSelepas !== $pergerakan->kondisi_sebelum) {
                        $pergerakan->senariAset->update([
                            'kondisi_aset' => $kondisiSelepas,
                            'updated_by' => Auth::id(),
                        ]);
                    }
                }
            }

            // Update tempahan status
            $tempahan->update([
                'status_pemulangan' => 'Sudah Pulang',
                'tarikh_sebenar_pulangan' => now(),
                'status_tempahan' => 'Selesai',
                'updated_by' => Auth::id(),
            ]);
        });

        return true;
    }

    /**
     * Update tempahan status_pemulangan based on pergerakan status
     * 
     * @param TempahanFasiliti $tempahan
     */
    protected function updateTempahanStatusPemulangan(TempahanFasiliti $tempahan): void
    {
        $pergerakanList = $tempahan->pergerakanAset;
        
        if ($pergerakanList->isEmpty()) {
            return;
        }

        $totalItems = $pergerakanList->count();
        $returnedItems = $pergerakanList->where('status_pulangan', 'Sudah Pulang')->count();

        if ($returnedItems === 0) {
            $status = 'Belum Pulang';
        } elseif ($returnedItems === $totalItems) {
            $status = 'Sudah Pulang';
            // Also update tempahan to Selesai
            $tempahan->update([
                'status_tempahan' => 'Selesai',
                'tarikh_sebenar_pulangan' => now(),
            ]);
        } else {
            $status = 'Sebahagian';
        }

        $tempahan->update([
            'status_pemulangan' => $status,
            'updated_by' => Auth::id(),
        ]);
    }

    /**
     * Auto-detect and mark late returns
     * 
     * @return int Number of records marked as late
     */
    public function markLateReturns(): int
    {
        $count = 0;

        DB::transaction(function () use (&$count) {
            // Mark pergerakan aset as late
            $latePergerakan = PergerakanAset::where('status_pulangan', 'Belum Pulang')
                ->whereNotNull('tarikh_jangka_pulangan')
                ->where('tarikh_jangka_pulangan', '<', now())
                ->get();

            foreach ($latePergerakan as $pergerakan) {
                $pergerakan->update(['status_pulangan' => 'Lewat']);
                $count++;
            }

            // Mark tempahan as late
            TempahanFasiliti::where('status_tempahan', 'Lulus')
                ->where('status_pemulangan', 'Belum Pulang')
                ->where('tarikh_tamat', '<', now())
                ->update(['status_pemulangan' => 'Lewat']);
        });

        return $count;
    }

    /**
     * Process partial return for a pergerakan aset
     * 
     * @param PergerakanAset $pergerakan
     * @param int $kuantitiPulang
     * @param string $kondisiSelepas
     * @param string|null $catatan
     * @param bool $selesaikan - If true, close the record and calculate hilang
     * @return array
     */
    public function processPartialReturn(
        PergerakanAset $pergerakan, 
        int $kuantitiPulang, 
        string $kondisiSelepas, 
        ?string $catatan = null, 
        bool $selesaikan = false
    ): array {
        $result = [
            'success' => false,
            'message' => '',
            'transaksi_id' => null,
        ];

        // Validate kuantiti
        $bakiBelumPulang = $pergerakan->kuantiti - $pergerakan->kuantiti_dipulangkan;
        if ($kuantitiPulang > $bakiBelumPulang) {
            $result['message'] = "Kuantiti pulang ({$kuantitiPulang}) melebihi baki belum pulang ({$bakiBelumPulang})";
            return $result;
        }

        DB::transaction(function () use ($pergerakan, $kuantitiPulang, $kondisiSelepas, $catatan, $selesaikan, &$result) {
            $newTotalDipulangkan = $pergerakan->kuantiti_dipulangkan + $kuantitiPulang;
            $baki = $pergerakan->kuantiti - $newTotalDipulangkan;

            $updateData = [
                'kuantiti_dipulangkan' => $newTotalDipulangkan,
                'kondisi_selepas' => $kondisiSelepas,
                'updated_by' => Auth::id(),
            ];

            if ($catatan) {
                $updateData['catatan'] = $pergerakan->catatan 
                    ? $pergerakan->catatan . "\n[" . now()->format('d/m/Y H:i') . "] Pulangan: " . $catatan 
                    : "[" . now()->format('d/m/Y H:i') . "] Pulangan: " . $catatan;
            }

            // Determine status
            if ($selesaikan) {
                // User wants to close this record
                $updateData['kuantiti_hilang'] = $baki;
                $updateData['status_pulangan'] = $baki > 0 ? 'Hilang' : 'Sudah Pulang';
                $updateData['tarikh_sebenar_pulangan'] = now();
                $updateData['tarikh_selesai_pulangan'] = now();
                $updateData['diselesaikan_oleh'] = Auth::id();

                // Create ganti rugi transaction if ada hilang
                if ($baki > 0) {
                    $transaksi = $this->createGantiRugiTransaction($pergerakan, $baki, 'hilang');
                    if ($transaksi) {
                        $updateData['transaksi_kewangan_id'] = $transaksi->id;
                        $updateData['nilai_ganti_rugi'] = $transaksi->jumlah;
                        $result['transaksi_id'] = $transaksi->id;
                    }
                }
            } else {
                // Partial return, keep open
                if ($newTotalDipulangkan >= $pergerakan->kuantiti) {
                    $updateData['status_pulangan'] = 'Sudah Pulang';
                    $updateData['tarikh_sebenar_pulangan'] = now();
                } else {
                    $updateData['status_pulangan'] = 'Sebahagian';
                }
            }

            $pergerakan->update($updateData);

            // Sync with tempahan_fasiliti_items
            if ($pergerakan->tempahanFasilitiItem) {
                $pergerakan->tempahanFasilitiItem->update([
                    'kuantiti_dipulangkan' => $newTotalDipulangkan,
                    'kuantiti_hilang' => $updateData['kuantiti_hilang'] ?? 0,
                    'status_pulangan' => $updateData['status_pulangan'],
                ]);
            }

            // Update parent tempahan status
            if ($pergerakan->tempahanFasiliti) {
                $this->updateTempahanStatusPemulangan($pergerakan->tempahanFasiliti);
            }

            // Update aset kondisi if changed
            if ($pergerakan->senariAset && $kondisiSelepas !== $pergerakan->kondisi_sebelum) {
                $pergerakan->senariAset->update([
                    'kondisi_aset' => $kondisiSelepas,
                    'updated_by' => Auth::id(),
                ]);
            }

            $result['success'] = true;
            $result['message'] = 'Pulangan berjaya direkodkan';
        });

        return $result;
    }

    /**
     * Create ganti rugi transaction for lost/damaged assets
     * 
     * @param PergerakanAset $pergerakan
     * @param int $kuantiti
     * @param string $jenis - 'hilang' or 'rosak'
     * @return TransaksiKewangan|null
     */
    protected function createGantiRugiTransaction(PergerakanAset $pergerakan, int $kuantiti, string $jenis = 'hilang'): ?TransaksiKewangan
    {
        $aset = $pergerakan->senariAset;
        if (!$aset) {
            return null;
        }

        // Calculate nilai ganti rugi
        $hargaPerUnit = $aset->harga_perolehan ?? 0;
        $nilaiGantiRugi = $hargaPerUnit * $kuantiti;

        if ($nilaiGantiRugi <= 0) {
            return null;
        }

        // Find kategori
        $kodKategori = $jenis === 'rosak' ? 'KL-ASET-ROSAK' : 'KL-ASET-HILANG';
        $kategori = KategoriKewangan::where('masjid_id', $pergerakan->masjid_id)
            ->where('kod_kategori', $kodKategori)
            ->first();

        if (!$kategori) {
            // Create kategori if not exists
            $kategori = KategoriKewangan::create([
                'masjid_id' => $pergerakan->masjid_id,
                'jenis_kategori' => 'kategori_pendapatan',
                'nama_kategori' => $jenis === 'rosak' ? 'Ganti Rugi Aset Rosak' : 'Ganti Rugi Aset Hilang',
                'kod_kategori' => $kodKategori,
                'status' => 'Aktif',
            ]);
        }

        // Generate no transaksi
        $noTransaksi = $this->generateNoTransaksi($pergerakan->masjid_id);

        $keterangan = sprintf(
            "Ganti rugi %d unit %s (%s) - No. Pergerakan: %s",
            $kuantiti,
            $aset->nama_aset,
            $jenis === 'rosak' ? 'rosak' : 'hilang',
            $pergerakan->no_pergerakan
        );

        if ($pergerakan->nama_peminjam) {
            $keterangan .= " - Peminjam: {$pergerakan->nama_peminjam}";
        }

        $transaksi = TransaksiKewangan::create([
            'masjid_id' => $pergerakan->masjid_id,
            'no_transaksi' => $noTransaksi,
            'tarikh_transaksi' => now(),
            'jenis_transaksi' => 'Pendapatan',
            'kategori_kewangan_id' => $kategori->id,
            'jumlah' => $nilaiGantiRugi,
            'keterangan' => $keterangan,
            'status' => 'Belum Bayar',
            'created_by' => Auth::id(),
        ]);

        return $transaksi;
    }

    /**
     * Generate no transaksi for ganti rugi
     * Uses the same format as TransaksiKewangan model
     */
    protected function generateNoTransaksi(int $masjidId): string
    {
        return TransaksiKewangan::generateNoTransaksi($masjidId);
    }

    /**
     * Process bulk return for all items in a tempahan (full return)
     * 
     * @param TempahanFasiliti $tempahan
     * @param string $kondisiSelepas
     * @param string|null $catatan
     * @return array
     */
    public function processBulkReturn(TempahanFasiliti $tempahan, string $kondisiSelepas, ?string $catatan = null): array
    {
        $result = [
            'success' => false,
            'message' => '',
            'returned_count' => 0,
        ];

        DB::transaction(function () use ($tempahan, $kondisiSelepas, $catatan, &$result) {
            $returnedCount = 0;

            // Update all pergerakan records for this tempahan
            foreach ($tempahan->pergerakanAset as $pergerakan) {
                if (in_array($pergerakan->status_pulangan, ['Belum Pulang', 'Sebahagian', 'Lewat'])) {
                    $pergerakan->update([
                        'kuantiti_dipulangkan' => $pergerakan->kuantiti,
                        'kuantiti_hilang' => 0,
                        'status_pulangan' => 'Sudah Pulang',
                        'tarikh_sebenar_pulangan' => now(),
                        'tarikh_selesai_pulangan' => now(),
                        'kondisi_selepas' => $kondisiSelepas,
                        'catatan' => $catatan 
                            ? ($pergerakan->catatan ? $pergerakan->catatan . "\n" : '') . "[" . now()->format('d/m/Y H:i') . "] Pulangan Bulk: " . $catatan 
                            : $pergerakan->catatan,
                        'diselesaikan_oleh' => Auth::id(),
                        'updated_by' => Auth::id(),
                    ]);

                    // Update aset kondisi if changed
                    if ($pergerakan->senariAset && $kondisiSelepas !== $pergerakan->kondisi_sebelum) {
                        $pergerakan->senariAset->update([
                            'kondisi_aset' => $kondisiSelepas,
                            'updated_by' => Auth::id(),
                        ]);
                    }

                    $returnedCount++;
                }
            }

            // Update all items
            foreach ($tempahan->activeItems as $item) {
                $item->update([
                    'kuantiti_dipulangkan' => $item->quantity,
                    'kuantiti_hilang' => 0,
                    'status_pulangan' => 'Sudah Pulang',
                ]);
            }

            // Update tempahan
            $tempahan->update([
                'status_pemulangan' => 'Sudah Pulang',
                'tarikh_sebenar_pulangan' => now(),
                'status_tempahan' => 'Selesai',
                'updated_by' => Auth::id(),
            ]);

            $result['success'] = true;
            $result['message'] = "Berjaya merekod pulangan untuk {$returnedCount} pergerakan aset";
            $result['returned_count'] = $returnedCount;
        });

        return $result;
    }

    /**
     * Get return statistics for a pergerakan
     */
    public function getReturnStats(PergerakanAset $pergerakan): array
    {
        return [
            'kuantiti_asal' => $pergerakan->kuantiti,
            'kuantiti_dipulangkan' => $pergerakan->kuantiti_dipulangkan,
            'baki_belum_pulang' => $pergerakan->kuantiti - $pergerakan->kuantiti_dipulangkan,
            'kuantiti_hilang' => $pergerakan->kuantiti_hilang,
            'status_pulangan' => $pergerakan->status_pulangan,
            'is_selesai' => in_array($pergerakan->status_pulangan, ['Sudah Pulang', 'Hilang']),
        ];
    }
}
