<?php

namespace App\Models;

use App\Models\Booking;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Property extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'properties';

    protected $fillable = [
        'kategori_id',
        'nama_properti',
        'lokasi',
        'harga',
        'deskripsi',
        'gambar',
        'deleted_by',
    ];

    protected $casts = [
        'harga' => 'decimal:2',
    ];

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(CategoryProperty::class, 'kategori_id');
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'properti_id');
    }
}