<?php

namespace App\Models;

use App\Traits\HasMasjidScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PembayaranBantuan extends Model
{
    use HasFactory, SoftDeletes, HasMasjidScope;

    protected $table = 'pembayaran_bantuan';

    protected $fillable = [
        'masjid_id',
        'no_pembayaran',
        'permohonan_bantuan_id',
        'penerima_bantuan_id',
        'program_kebajikan_id',
        'tarikh_pembayaran',
        'jumlah_bayaran',
        'kaedah_bayaran',
        'nama_bank',
        'no_akaun',
        'no_rujukan',
        'no_cek',
        'tarikh_cek',
        'senarai_barangan',
        'nilai_barangan',
        'resit_pembayaran',
        'salinan_cek',
        'bukti_transfer',
        'gambar_penyerahan_1',
        'gambar_penyerahan_2',
        'gambar_penyerahan_3',
        'tarikh_diterima',
        'diterima_oleh',
        'surat_akuan',
        'tandatangan_digital',
        'status_pembayaran',
        'catatan',
        'dibayar_oleh',
        'tarikh_dibayar',
        'disahkan_oleh',
        'tarikh_disahkan',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'tarikh_pembayaran' => 'date',
        'tarikh_cek' => 'date',
        'tarikh_diterima' => 'date',
        'tarikh_dibayar' => 'datetime',
        'tarikh_disahkan' => 'datetime',
        'jumlah_bayaran' => 'decimal:2',
        'nilai_barangan' => 'decimal:2',
        'resit_pembayaran' => 'array',
    ];

    // Relationships
    public function masjid()
    {
        return $this->belongsTo(Masjid::class);
    }

    public function permohonanBantuan()
    {
        return $this->belongsTo(PermohonanBantuan::class);
    }

    public function penerimaBantuan()
    {
        return $this->belongsTo(PenerimaBantuan::class);
    }

    public function programKebajikan()
    {
        return $this->belongsTo(ProgramKebajikan::class);
    }

    public function pembayar()
    {
        return $this->belongsTo(User::class, 'dibayar_oleh');
    }

    public function pengesah()
    {
        return $this->belongsTo(User::class, 'disahkan_oleh');
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

    // Auto-generate no_pembayaran
    public static function generateNoPembayaran($masjidId)
    {
        $year = date('Y');
        $prefix = 'PBY-' . $year . '-';

        $lastPembayaran = self::where('masjid_id', $masjidId)
            ->where('no_pembayaran', 'like', $prefix . '%')
            ->orderBy('no_pembayaran', 'desc')
            ->first();

        $nextNumber = $lastPembayaran ? intval(substr($lastPembayaran->no_pembayaran, -4)) + 1 : 1;

        return $prefix . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }

    // Status badge color helper
    public function getStatusColorAttribute()
    {
        return match ($this->status_pembayaran) {
            'Sudah Bayar' => 'green',
            'Belum Bayar' => 'orange',
            'Dibatalkan' => 'red',
            default => 'gray',
        };
    }
}
