<x-mail::message>
# Booking Konsultasi Berhasil 🎉

Halo **{{ $booking->client->nama }}**,

Terima kasih telah melakukan booking konsultasi di **{{ config('app.name') }}**.

Booking Anda telah berhasil dibuat dengan detail berikut.

<x-mail::table>
| Detail | Informasi |
| :------ | :--------- |
| Nomor Booking | **{{ $booking->nomor_booking }}** |
| Properti | {{ $booking->property->nama_properti }} |
| Tanggal Konsultasi | {{ $booking->tanggal_konsultasi->format('d M Y') }} |
| Jam Konsultasi | {{ $booking->jam_konsultasi }} |
| Status | {{ $booking->status }} |
</x-mail::table>

Silakan simpan nomor booking tersebut sebagai bukti reservasi Anda.

<x-mail::button :url="config('app.url')">
Kunjungi Website
</x-mail::button>

Terima kasih,<br>
{{ config('app.name') }}
</x-mail::message>