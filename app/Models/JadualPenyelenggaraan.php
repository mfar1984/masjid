<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasMasjidScope;

class JadualPenyelenggaraan extends Model
{
    use SoftDeletes, HasMasjidScope;

    protected $table = 'jadual_penyelenggaraan';

    protected $fillable = [
        'masjid_id',
        'no_jadual',
        'nama_jadual',
        'senarai_aset_id',
        'senarai_fasiliti_id',
        'jenis_item',
        'jenis_penyelenggaraan',
        'kekerapan',
        'tarikh_mula',
        'tarikh_akhir',
        'tarikh_penyelenggaraan_seterusnya',
        'skop_kerja',
        'vendor_nama',
        'vendor_telefon',
        'anggaran_kos',
        'status',
        'catatan',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'tarikh_mula' => 'date',
        'tarikh_akhir' => 'date',
        'tarikh_penyelenggaraan_seterusnya' => 'date',
        'anggaran_kos' => 'decimal:2',
    ];

    // Relationships
    public function masjid()
    {
        return $this->belongsTo(Masjid::class);
    }

    public function senariAset()
    {
        return $this->belongsTo(SenariAset::class, 'senarai_aset_id');
    }

    public function senariFasiliti()
    {
        return $this->belongsTo(SenariFasiliti::class, 'senarai_fasiliti_id');
    }

    public function kerjaPenyelenggaraan()
    {
        return $this->hasMany(KerjaPenyelenggaraan::class, 'jadual_penyelenggaraan_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // Scopes
    public function scopeAktif($query)
    {
        return $query->where('status', 'Aktif');
    }

    public function scopeByJenisItem($query, $jenis)
    {
        return $query->where('jenis_item', $jenis);
    }

    // Accessors
    public function getNamaAttribute()
    {
        return $this->nama_jadual ?? 'Jadual #' . $this->id;
    }

    public function getItemNamaAttribute()
    {
        if ($this->jenis_item === 'Aset') {
            return $this->senariAset->nama_aset ?? '-';
        }
        return $this->senariFasiliti->nama_fasiliti ?? '-';
    }

    // Auto-generate no_jadual
    public static function generateNoJadual($masjidId)
    {
        $year = date('Y');
        $prefix = 'JP-' . $year . '-';

        $last = self::where('masjid_id', $masjidId)
            ->where('no_jadual', 'like', $prefix . '%')
            ->orderBy('no_jadual', 'desc')
            ->first();

        $nextNumber = $last ? intval(substr($last->no_jadual, -4)) + 1 : 1;

        return $prefix . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }
}
