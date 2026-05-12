<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Booking extends Model
{
    use HasFactory;

    protected $table = 'bookings';

    protected $fillable = [
        'properti_id',
        'client_id',
        'user_id',
        'tanggal_konsultasi',
        'jam_konsultasi',
        'status',
        'nomor_booking',
        'catatan',
    ];

    protected $casts = [
        'tanggal_konsultasi' => 'date',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATION
    |--------------------------------------------------------------------------
    */

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function property()
    {
        return $this->belongsTo(Property::class, 'properti_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}