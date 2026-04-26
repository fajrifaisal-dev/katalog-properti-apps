<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Property extends Model
{
    use HasFactory;

    protected $table = 'properties';

    protected $fillable = [
        'kategori_id',
        'nama_properti',
        'lokasi',
        'harga',
        'deskripsi',
        'gambar',
    ];

    protected $casts = [
        'harga' => 'decimal:2',
    ];

    public function kategori()
    {
        return $this->belongsTo(CategoryProperty::class, 'kategori_id');
    }
}
