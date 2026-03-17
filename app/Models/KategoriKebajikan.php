<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KategoriKebajikan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'kategori_kebajikan';

    protected $fillable = [
        'masjid_id',
        'jenis_kategori',
        'nama_kategori',
        'kod_kategori',
        'keterangan',
        'urutan',
        'status',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    // Relationships
    public function masjid()
    {
        return $this->belongsTo(Masjid::class);
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

    // Scopes
    public function scopeJenisBantuan($query)
    {
        return $query->where('jenis_kategori', 'jenis_bantuan');
    }

    public function scopeKeutamaan($query)
    {
        return $query->where('jenis_kategori', 'keutamaan');
    }

    public function scopeJenisProgram($query)
    {
        return $query->where('jenis_kategori', 'jenis_program');
    }

    public function scopeTempohBantuan($query)
    {
        return $query->where('jenis_kategori', 'tempoh_bantuan');
    }

    public function scopeBangsa($query)
    {
        return $query->where('jenis_kategori', 'bangsa');
    }

    public function scopeAgama($query)
    {
        return $query->where('jenis_kategori', 'agama');
    }

    public function scopeJenisKediaman($query)
    {
        return $query->where('jenis_kategori', 'jenis_kediaman');
    }

    public function scopeAktif($query)
    {
        return $query->where('status', 'Aktif');
    }
}
