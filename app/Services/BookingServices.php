<?php

namespace App\Services;
use App\Models\Booking;
use App\Models\Client;
use App\Mail\BookingConfirmation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class BookingServices
{
    public function createBooking(array $data)
    {
        $booking = DB::transaction(function () use ($data) {

            $client = Client::firstOrCreate(
                ['no_hp' => $data['no_hp']],
                [
                    'nama' => $data['nama'],
                    'email' => $data['email'] ?? null,
                ]
            );

            $kode = $this->generateKodeBooking();

            return Booking::create([
                'client_id' => $client->id,
                'properti_id' => $data['properti_id'],
                'tanggal_konsultasi' => $data['tanggal_konsultasi'],
                'jam_konsultasi' => $data['jam_konsultasi'],
                'status' => 'Menunggu',
                'nomor_booking' => $kode,
                'catatan' => $data['catatan'] ?? null,
            ]);
        });

        // Load relasi yang dipakai di email
        $booking->load(['client', 'property']);

        // Kirim email
        $this->sendBookingEmail($booking);

        return $booking;
    }

    private function sendBookingEmail(Booking $booking): void
    {
        if (empty($booking->client->email)) {
            return;
        }

        try {
            Mail::to($booking->client->email)
                ->send(new BookingConfirmation($booking));
        } catch (\Throwable $e) {
            Log::error(
                "Gagal mengirim email booking {$booking->nomor_booking}: {$e->getMessage()}"
            );
        }
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