<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TempahanFasilitiItem extends Model
{
    protected $table = 'tempahan_fasiliti_items';

    protected $fillable = [
        'tempahan_fasiliti_id',
        'senarai_fasiliti_id',
        'quantity',
        'harga_per_unit',
        'subtotal',
        // Pulangan tracking fields
        'kuantiti_dipulangkan',
        'kuantiti_hilang',
        'status_pulangan',
        'status_item',
        'dibatalkan_oleh',
        'tarikh_dibatalkan',
        'sebab_batal_item',
    ];

    protected $casts = [
        'harga_per_unit' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'tarikh_dibatalkan' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function tempahanFasiliti()
    {
        return $this->belongsTo(TempahanFasiliti::class, 'tempahan_fasiliti_id');
    }

    public function senariFasiliti()
    {
        return $this->belongsTo(SenariFasiliti::class, 'senarai_fasiliti_id');
    }

    public function dibatalkanOleh()
    {
        return $this->belongsTo(User::class, 'dibatalkan_oleh');
    }

    // Scopes
    public function scopeAktif($query)
    {
        return $query->where('status_item', 'Aktif');
    }

    public function scopeDibatalkan($query)
    {
        return $query->where('status_item', 'Dibatalkan');
    }

    // Methods
    public function cancelItem($userId, $reason)
    {
        $this->update([
            'status_item' => 'Dibatalkan',
            'dibatalkan_oleh' => $userId,
            'tarikh_dibatalkan' => now(),
            'sebab_batal_item' => $reason,
        ]);

        // Recalculate tempahan total
        $this->tempahanFasiliti->recalculateTotal();
    }

    /**
     * Get pergerakan aset for this item
     */
    public function pergerakanAset()
    {
        return $this->hasOne(PergerakanAset::class, 'tempahan_fasiliti_item_id');
    }

    /**
     * Get baki belum pulang
     */
    public function getBakiBelumPulangAttribute()
    {
        return $this->quantity - $this->kuantiti_dipulangkan;
    }

    /**
     * Check if fully returned
     */
    public function getIsFullyReturnedAttribute()
    {
        return $this->kuantiti_dipulangkan >= $this->quantity;
    }
}
