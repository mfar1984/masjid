<?php

namespace App\Models;

use App\Traits\HasMasjidScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PenerimaBantuan extends Model
{
    use HasFactory, SoftDeletes, HasMasjidScope;

    protected $table = 'penerima_bantuan';

    protected $fillable = [
        'masjid_id',
        'no_pendaftaran',
        'nama_penuh',
        'no_kp',
        'jantina',
        'tarikh_lahir',
        'umur',
        'bangsa',
        'agama',
        'status_perkahwinan',
        'kewarganegaraan',
        'no_telefon',
        'no_telefon_kecemasan',
        'emel',
        'alamat_1',
        'alamat_2',
        'poskod',
        'bandar',
        'negeri',
        'bilangan_tanggungan',
        'bilangan_anak',
        'bilangan_anak_sekolah',
        'nama_pasangan',
        'no_kp_pasangan',
        'pekerjaan_pasangan',
        'pendapatan_pasangan',
        'status_pekerjaan',
        'pekerjaan',
        'majikan',
        'pendapatan_bulanan',
        'pendapatan_lain',
        'jumlah_pendapatan',
        'jenis_kediaman',
        'sewa_bulanan',
        'kategori_penerima',
        'status_oku',
        'jenis_oku',
        'no_kad_oku',
        'status_yatim',
        'status_ibu_tunggal',
        'status_warga_emas',
        'gambar_profil',
        'salinan_ic',
        'salinan_ic_pasangan',
        'sijil_lahir_anak',
        'slip_gaji',
        'penyata_bank',
        'kad_oku',
        'sijil_kematian',
        'surat_sokongan',
        'dokumen_lain',
        'status_penerima',
        'catatan',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'tarikh_lahir' => 'date',
        'pendapatan_pasangan' => 'decimal:2',
        'pendapatan_bulanan' => 'decimal:2',
        'pendapatan_lain' => 'decimal:2',
        'jumlah_pendapatan' => 'decimal:2',
        'sewa_bulanan' => 'decimal:2',
        'salinan_ic' => 'array',
        'salinan_ic_pasangan' => 'array',
        'sijil_lahir_anak' => 'array',
        'slip_gaji' => 'array',
        'penyata_bank' => 'array',
        'kad_oku' => 'array',
        'surat_sokongan' => 'array',
        'dokumen_lain' => 'array',
    ];

    // Relationships
    public function masjid()
    {
        return $this->belongsTo(Masjid::class);
    }

    public function permohonanBantuan()
    {
        return $this->hasMany(PermohonanBantuan::class);
    }

    public function pembayaranBantuan()
    {
        return $this->hasMany(PembayaranBantuan::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function deleter()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }

    // Auto-generate no_pendaftaran
    public static function generateNoPendaftaran($masjidId)
    {
        $year = date('Y');
        $prefix = 'PNB-' . $year . '-';

        $lastPenerima = self::where('masjid_id', $masjidId)
            ->where('no_pendaftaran', 'like', $prefix . '%')
            ->orderBy('no_pendaftaran', 'desc')
            ->first();

        $nextNumber = $lastPenerima ? intval(substr($lastPenerima->no_pendaftaran, -4)) + 1 : 1;

        return $prefix . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }

    // Calculate age from tarikh_lahir
    public function calculateAge()
    {
        if ($this->tarikh_lahir) {
            return $this->tarikh_lahir->age;
        }
        return null;
    }

    // Calculate total income
    public function calculateTotalIncome()
    {
        $total = 0;
        if ($this->pendapatan_bulanan) {
            $total += $this->pendapatan_bulanan;
        }
        if ($this->pendapatan_lain) {
            $total += $this->pendapatan_lain;
        }
        if ($this->pendapatan_pasangan) {
            $total += $this->pendapatan_pasangan;
        }
        return $total;
    }
}
