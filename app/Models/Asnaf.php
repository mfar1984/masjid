<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasMasjidScope;

class Asnaf extends Model
{
    use SoftDeletes, HasMasjidScope;

    protected $table = 'asnaf';

    protected $fillable = [
        // Maklumat Peribadi
        'nama',
        'no_ic',
        'jantina',
        'bangsa',
        'agama',
        'status_perkahwinan',
        'telefon',
        'telefon_alternatif',
        'email',

        // Alamat IC
        'alamat_ic',
        'poskod_ic',
        'bandar_ic',
        'negeri_ic',

        // Alamat Surat
        'alamat_surat',
        'poskod_surat',
        'bandar_surat',
        'negeri_surat',

        // Alamat Kediaman
        'alamat_kediaman',
        'poskod_kediaman',
        'bandar_kediaman',
        'negeri_kediaman',
        'status_kediaman',

        // Waris
        'nama_waris',
        'hubungan_waris',
        'no_ic_waris',
        'telefon_waris',
        'alamat_waris',

        // Kategori Asnaf
        'kategori_asnaf',
        'sebab_permohonan',

        // Pekerjaan & Pendapatan
        'status_pekerjaan',
        'nama_majikan',
        'jawatan',
        'pendapatan_bulanan',
        'pendapatan_pasangan',
        'pendapatan_lain',
        'sumber_pendapatan_lain',

        // Tanggungan
        'bilangan_tanggungan',
        'jumlah_perbelanjaan',

        // Hutang
        'ada_hutang',
        'jumlah_hutang',
        'bayaran_hutang_bulanan',
        'sebab_berhutang',

        // Kesihatan
        'status_kesihatan',
        'jenis_penyakit',
        'kos_perubatan_bulanan',

        // Aset
        'pemilikan_rumah',
        'pemilikan_kenderaan',
        'simpanan_bank',

        // Dokumen
        'ic_depan_path',
        'ic_belakang_path',
        'ic_waris_path',
        'slip_gaji_path',
        'penyata_bank_path',
        'bil_utiliti_path',
        'surat_sokongan_path',

        // Workflow
        'status',
        'catatan_kelulusan',
        'diluluskan_oleh',
        'tarikh_diluluskan',
        'jumlah_diluluskan',

        // Multi-tenant
        'masjid_id',

        // Audit
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'pendapatan_bulanan' => 'decimal:2',
        'pendapatan_pasangan' => 'decimal:2',
        'pendapatan_lain' => 'decimal:2',
        'jumlah_perbelanjaan' => 'decimal:2',
        'jumlah_hutang' => 'decimal:2',
        'bayaran_hutang_bulanan' => 'decimal:2',
        'kos_perubatan_bulanan' => 'decimal:2',
        'simpanan_bank' => 'decimal:2',
        'jumlah_diluluskan' => 'decimal:2',
        'ada_hutang' => 'boolean',
        'tarikh_diluluskan' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
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

    public function diluluskanOleh()
    {
        return $this->belongsTo(User::class, 'diluluskan_oleh');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'Diluluskan');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'Menunggu');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'Diluluskan');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'Ditolak');
    }

    public function scopeSuspended($query)
    {
        return $query->where('status', 'Digantung');
    }

    // Accessors
    public function getUmurAttribute()
    {
        if (!$this->no_ic || strlen($this->no_ic) < 6) {
            return null;
        }

        $year = substr($this->no_ic, 0, 2);
        $currentYear = date('y');
        $birthYear = ($year > $currentYear) ? '19' . $year : '20' . $year;

        return date('Y') - $birthYear;
    }

    public function getTotalPendapatanAttribute()
    {
        return $this->pendapatan_bulanan + $this->pendapatan_pasangan + $this->pendapatan_lain;
    }

    public function getPendapatanPerKapitaAttribute()
    {
        $tanggungan = $this->bilangan_tanggungan + 1; // +1 for pemohon
        return $tanggungan > 0 ? $this->total_pendapatan / $tanggungan : $this->total_pendapatan;
    }
}
