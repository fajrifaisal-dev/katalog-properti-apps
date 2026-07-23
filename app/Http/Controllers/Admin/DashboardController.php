<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Client;
use App\Models\CategoryProperty;
use App\Models\Property;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $status   = $request->input('status');      // 'Menunggu' | 'Disetujui' | 'Ditolak' | null
        $dateFrom = $request->input('date_from');    // 'Y-m-d' | null
        $dateTo   = $request->input('date_to');      // 'Y-m-d' | null

        // Base query booking yang sudah kena filter, dipakai ulang di beberapa tempat
        $bookingQuery = Booking::query()
            ->when($status, fn ($q) => $q->where('status', $status))
            ->when($dateFrom, fn ($q) => $q->whereDate('tanggal_konsultasi', '>=', $dateFrom))
            ->when($dateTo, fn ($q) => $q->whereDate('tanggal_konsultasi', '<=', $dateTo));

        // Ringkasan angka utama (properti/kategori/client tidak difilter, booking ikut filter)
        $totalProperties = Property::count();
        $totalCategories = CategoryProperty::count();
        $totalClients = Client::count();
        $totalBookings = (clone $bookingQuery)->count();

        // Breakdown booking per status (ikut filter tanggal, tapi status filter di-skip
        // biar tetap kelihatan distribusi semua status meski lagi filter salah satu)
        $bookingsByStatusRaw = Booking::query()
            ->when($dateFrom, fn ($q) => $q->whereDate('tanggal_konsultasi', '>=', $dateFrom))
            ->when($dateTo, fn ($q) => $q->whereDate('tanggal_konsultasi', '<=', $dateTo))
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $bookingsByStatus = collect(['Menunggu', 'Disetujui', 'Ditolak'])
            ->mapWithKeys(fn ($s) => [$s => $bookingsByStatusRaw->get($s, 0)]);

        // Booking terbaru (ikut semua filter)
        $recentBookings = (clone $bookingQuery)
            ->with(['client:id,nama,email,no_hp', 'property:id,nama_properti,lokasi'])
            ->latest('created_at')
            ->take(10)
            ->get()
            ->map(fn ($booking) => [
                'id' => $booking->id,
                'nomor_booking' => $booking->nomor_booking ?? '-',
                'client_nama' => $booking->client->nama ?? '-',
                'properti_nama' => $booking->property->nama_properti ?? '-',
                'tanggal_konsultasi' => $booking->tanggal_konsultasi?->format('Y-m-d'),
                'jam_konsultasi' => $booking->jam_konsultasi,
                'status' => $booking->status ?? 'Menunggu',
            ]);

        // Properti terbaru (tidak ikut filter booking)
        $recentProperties = Property::with('kategori:id,nama_kategori')
            ->latest('created_at')
            ->take(5)
            ->get()
            ->map(fn ($property) => [
                'id' => $property->id,
                'nama_properti' => $property->nama_properti,
                'kategori' => $property->kategori->nama_kategori ?? '-',
                'harga' => $property->harga,
                'lokasi' => $property->lokasi,
            ]);

        // Tren booking — ikut filter status, tapi range tanggal defaultnya 7 hari terakhir
        // kalau user tidak set date_from/date_to
        $trendQuery = Booking::query()
            ->when($status, fn ($q) => $q->where('status', $status));

        if ($dateFrom || $dateTo) {
            $trendQuery
                ->when($dateFrom, fn ($q) => $q->whereDate('tanggal_konsultasi', '>=', $dateFrom))
                ->when($dateTo, fn ($q) => $q->whereDate('tanggal_konsultasi', '<=', $dateTo));
        } else {
            $trendQuery->where('created_at', '>=', now()->subDays(6)->startOfDay());
        }

        $bookingTrend = $trendQuery
            ->selectRaw('DATE(created_at) as tanggal, COUNT(*) as total')
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->get();

        return Inertia::render('Dashboard', [
            'stats' => [
                'totalProperties' => $totalProperties,
                'totalCategories' => $totalCategories,
                'totalClients' => $totalClients,
                'totalBookings' => $totalBookings,
            ],
            'bookingsByStatus' => $bookingsByStatus,
            'recentBookings' => $recentBookings,
            'recentProperties' => $recentProperties,
            'bookingTrend' => $bookingTrend,
            'filters' => [
                'status' => $status,
                'date_from' => $dateFrom,
                'date_to' => $dateTo,
            ],
        ]);
    }
}