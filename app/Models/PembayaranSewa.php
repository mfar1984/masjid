<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasMasjidScope;

class PembayaranSewa extends Model
{
    use SoftDeletes, HasMasjidScope;

    protected $table = 'pembayaran_sewa';

    protected $fillable = [
        'masjid_id',
        'no_pembayaran',
        'tempahan_fasiliti_id',
        'senarai_fasiliti_id',
        'tarikh_pembayaran',
        'jumlah_sewa',
        'jumlah_deposit',
        'jumlah_bayaran',
        'kaedah_bayaran',
        'nama_bank',
        'no_akaun',
        'no_rujukan',
        'no_cek',
        'tarikh_cek',
        'resit_pembayaran_path',
        'bukti_transfer_path',
        'salinan_cek_path',
        'deposit_dikembalikan',
        'tarikh_kembalikan_deposit',
        'sebab_potongan_deposit',
        'status_pembayaran',
        'catatan',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'tarikh_pembayaran' => 'date',
        'tarikh_cek' => 'date',
        'tarikh_kembalikan_deposit' => 'date',
        'jumlah_sewa' => 'decimal:2',
        'jumlah_deposit' => 'decimal:2',
        'jumlah_bayaran' => 'decimal:2',
        'deposit_dikembalikan' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function masjid()
    {
        return $this->belongsTo(Masjid::class);
    }

    public function tempahanFasiliti()
    {
        return $this->belongsTo(TempahanFasiliti::class);
    }

    public function senariFasiliti()
    {
        return $this->belongsTo(SenariFasiliti::class, 'senarai_fasiliti_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function deletedBy()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }

    // Scopes
    public function scopeSudahBayar($query)
    {
        return $query->where('status_pembayaran', 'Sudah Bayar');
    }

    public function scopeBelumBayar($query)
    {
        return $query->where('status_pembayaran', 'Belum Bayar');
    }

    // Methods
    public static function generateNoPembayaran($masjidId)
    {
        $year = date('Y');
        $lastPembayaran = self::where('masjid_id', $masjidId)
            ->where('no_pembayaran', 'like', "PS-{$year}-%")
            ->orderBy('no_pembayaran', 'desc')
            ->first();

        if ($lastPembayaran) {
            $lastNumber = (int) substr($lastPembayaran->no_pembayaran, -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return "PS-{$year}-" . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }
}
