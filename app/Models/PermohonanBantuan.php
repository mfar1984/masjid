<?php

namespace App\Models;

use App\Traits\HasMasjidScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PermohonanBantuan extends Model
{
    use HasFactory, SoftDeletes, HasMasjidScope;

    protected $table = 'permohonan_bantuan';

    protected $fillable = [
        'masjid_id',
        'no_permohonan',
        'penerima_bantuan_id',
        'program_kebajikan_id',
        'tarikh_permohonan',
        'jenis_bantuan',
        'jumlah_dipohon',
        'tujuan_permohonan',
        'keutamaan',
        'surat_permohonan',
        'surat_hospital',
        'sijil_kematian',
        'resit_perbelanjaan',
        'gambar_bukti_1',
        'gambar_bukti_2',
        'gambar_bukti_3',
        'dokumen_sokongan_lain',
        'tarikh_lawatan',
        'masa_lawatan',
        'pegawai_lawatan',
        'laporan_lawatan',
        'gambar_lawatan_1',
        'gambar_lawatan_2',
        'gambar_lawatan_3',
        'skor_kelayakan',
        'status_permohonan',
        'tarikh_keputusan',
        'jumlah_diluluskan',
        'catatan_keputusan',
        'sebab_tolak',
        'disemak_oleh',
        'tarikh_disemak',
        'catatan_semakan',
        'diluluskan_oleh',
        'tarikh_diluluskan',
        'catatan_kelulusan',
        'ditolak_oleh',
        'tarikh_ditolak',
        'dibatalkan_oleh',
        'tarikh_dibatalkan',
        'sebab_batal',
        'catatan',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'tarikh_permohonan' => 'date',
        'tarikh_lawatan' => 'date',
        'tarikh_keputusan' => 'date',
        'tarikh_disemak' => 'datetime',
        'tarikh_diluluskan' => 'datetime',
        'tarikh_ditolak' => 'datetime',
        'tarikh_dibatalkan' => 'datetime',
        'jumlah_dipohon' => 'decimal:2',
        'jumlah_diluluskan' => 'decimal:2',
        'surat_permohonan' => 'array',
        'surat_hospital' => 'array',
        'resit_perbelanjaan' => 'array',
        'dokumen_sokongan_lain' => 'array',
    ];

    // Relationships
    public function masjid()
    {
        return $this->belongsTo(Masjid::class);
    }

    public function penerimaBantuan()
    {
        return $this->belongsTo(PenerimaBantuan::class);
    }

    public function programKebajikan()
    {
        return $this->belongsTo(ProgramKebajikan::class);
    }

    public function pembayaranBantuan()
    {
        return $this->hasOne(PembayaranBantuan::class);
    }

    public function penyemak()
    {
        return $this->belongsTo(User::class, 'disemak_oleh');
    }

    public function pelulus()
    {
        return $this->belongsTo(User::class, 'diluluskan_oleh');
    }

    public function penolak()
    {
        return $this->belongsTo(User::class, 'ditolak_oleh');
    }

    public function pembatal()
    {
        return $this->belongsTo(User::class, 'dibatalkan_oleh');
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

    // Auto-generate no_permohonan
    public static function generateNoPermohonan($masjidId)
    {
        $year = date('Y');
        $prefix = 'PB-' . $year . '-';

        $lastPermohonan = self::where('masjid_id', $masjidId)
            ->where('no_permohonan', 'like', $prefix . '%')
            ->orderBy('no_permohonan', 'desc')
            ->first();

        $nextNumber = $lastPermohonan ? intval(substr($lastPermohonan->no_permohonan, -4)) + 1 : 1;

        return $prefix . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }

    // Status badge color helper
    public function getStatusColorAttribute()
    {
        return match ($this->status_permohonan) {
            'Baharu' => 'blue',
            'Dalam Semakan' => 'yellow',
            'Lawatan Rumah' => 'purple',
            'Lulus' => 'green',
            'Ditolak' => 'red',
            'Dibatalkan' => 'gray',
            default => 'gray',
        };
    }

    // Keutamaan badge color helper
    public function getKeutamaanColorAttribute()
    {
        return match ($this->keutamaan) {
            'Kecemasan' => 'red',
            'Tinggi' => 'orange',
            'Sederhana' => 'yellow',
            'Biasa' => 'blue',
            default => 'gray',
        };
    }
}
