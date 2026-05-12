<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Client extends Model
{
    use HasFactory;

    protected $table = 'clients';

    protected $fillable = [
        'nama',
        'email',
        'no_hp',
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}