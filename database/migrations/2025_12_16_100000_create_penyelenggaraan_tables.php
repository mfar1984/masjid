<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Table 1: Jadual Penyelenggaraan (Maintenance Schedule)
        Schema::create('jadual_penyelenggaraan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('masjid_id')->constrained('masjids')->onDelete('cascade');
            $table->string('no_jadual', 50)->unique();
            $table->string('nama_jadual');
            $table->foreignId('senarai_aset_id')->nullable()->constrained('senarai_aset')->onDelete('set null');
            $table->foreignId('senarai_fasiliti_id')->nullable()->constrained('senarai_fasiliti')->onDelete('set null');
            $table->enum('jenis_item', ['Aset', 'Fasiliti'])->default('Aset');
            $table->enum('jenis_penyelenggaraan', ['Berkala', 'Pembaikan', 'Pemeriksaan', 'Servis'])->default('Berkala');
            $table->enum('kekerapan', ['Harian', 'Mingguan', 'Bulanan', 'Suku Tahunan', 'Tahunan'])->default('Bulanan');
            $table->date('tarikh_mula');
            $table->date('tarikh_akhir')->nullable();
            $table->date('tarikh_penyelenggaraan_seterusnya')->nullable();
            $table->text('skop_kerja')->nullable();
            $table->string('vendor_nama')->nullable();
            $table->string('vendor_telefon', 20)->nullable();
            $table->decimal('anggaran_kos', 12, 2)->nullable();
            $table->enum('status', ['Aktif', 'Tidak Aktif', 'Selesai'])->default('Aktif');
            $table->text('catatan')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('deleted_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['masjid_id', 'status']);
            $table->index(['masjid_id', 'jenis_item']);
        });

        // Table 2: Kerja Penyelenggaraan (Maintenance Work Records)
        Schema::create('kerja_penyelenggaraan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('masjid_id')->constrained('masjids')->onDelete('cascade');
            $table->string('no_kerja', 50)->unique();
            $table->foreignId('jadual_penyelenggaraan_id')->nullable()->constrained('jadual_penyelenggaraan')->onDelete('set null');
            $table->foreignId('senarai_aset_id')->nullable()->constrained('senarai_aset')->onDelete('set null');
            $table->foreignId('senarai_fasiliti_id')->nullable()->constrained('senarai_fasiliti')->onDelete('set null');
            $table->enum('jenis_item', ['Aset', 'Fasiliti'])->default('Aset');
            $table->date('tarikh_kerja');
            $table->time('masa_mula')->nullable();
            $table->time('masa_tamat')->nullable();
            $table->enum('jenis_kerja', ['Penyelenggaraan Berkala', 'Pembaikan', 'Pemeriksaan', 'Servis', 'Kecemasan'])->default('Penyelenggaraan Berkala');
            $table->text('penerangan_kerja');
            $table->string('vendor_nama')->nullable();
            $table->string('vendor_telefon', 20)->nullable();
            $table->string('vendor_alamat')->nullable();
            $table->decimal('kos', 12, 2)->default(0);
            $table->foreignId('transaksi_kewangan_id')->nullable()->constrained('transaksi_kewangan')->onDelete('set null');
            $table->enum('kondisi_sebelum', ['Baik', 'Sederhana', 'Teruk', 'Rosak'])->nullable();
            $table->enum('kondisi_selepas', ['Baik', 'Sederhana', 'Teruk', 'Rosak'])->nullable();
            $table->enum('status', ['Dirancang', 'Sedang Berjalan', 'Selesai', 'Dibatalkan', 'Tertangguh'])->default('Dirancang');
            $table->string('gambar_sebelum')->nullable();
            $table->string('gambar_selepas')->nullable();
            $table->string('dokumen_path')->nullable();
            $table->text('catatan')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('deleted_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['masjid_id', 'status']);
            $table->index(['masjid_id', 'tarikh_kerja']);
            $table->index(['masjid_id', 'jenis_item']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kerja_penyelenggaraan');
        Schema::dropIfExists('jadual_penyelenggaraan');
    }
};
