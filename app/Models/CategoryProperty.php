<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CategoryProperty extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'category_properties';

    protected $fillable = [
        'nama_kategori',
        'deskripsi',
        'status_category',
        'deleted_by',
    ];

    public function properti()
    {
        return $this->hasMany(Property::class, 'kategori_id');
    }
}