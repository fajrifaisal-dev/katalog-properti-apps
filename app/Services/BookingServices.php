<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Client;
use Illuminate\Support\Facades\DB;

class BookingServices
{
    public function createBooking(array $data)
    {
        return DB::transaction(function () use ($data) {

            /*
            |--------------------------------------------------------------------------
            | 1. FIND / CREATE CLIENT
            |--------------------------------------------------------------------------
            */
            $client = Client::firstOrCreate(
                ['no_hp' => $data['no_hp']],
                [
                    'nama' => $data['nama'],
                    'email' => $data['email'] ?? null,
                ]
            );

            /*
            |--------------------------------------------------------------------------
            | 2. GENERATE NOMOR BOOKING
            |--------------------------------------------------------------------------
            */
            $kode = $this->generateKodeBooking();

            /*
            |--------------------------------------------------------------------------
            | 3. CREATE BOOKING
            |--------------------------------------------------------------------------*/
            $booking = Booking::create([
                'client_id' => $client->id,
                'properti_id' => $data['properti_id'],
                'tanggal_konsultasi' => $data['tanggal_konsultasi'],
                'jam_konsultasi' => $data['jam_konsultasi'],
                'status' => 'Menunggu',
                'nomor_booking' => $kode,
                'catatan' => $data['catatan'] ?? null,
            ]);

            return $booking;
        });
    }

    /*
    |--------------------------------------------------------------------------
    | GENERATE KODE BOOKING
    |--------------------------------------------------------------------------
    */
    private function generateKodeBooking()
    {
        do {
            $kode = 'IKK-' . date('Ymd') . '-' . rand(1000, 9999);
        } while (Booking::where('nomor_booking', $kode)->exists());

        return $kode;
    }
}