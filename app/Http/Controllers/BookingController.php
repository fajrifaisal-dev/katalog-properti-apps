<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Services\BookingServices;
use App\Models\Booking;

class BookingController extends Controller
{
    protected $bookingService;

    public function __construct(BookingServices $bookingService)
    {
        $this->bookingService = $bookingService;
    }



    /*
    |--------------------------------------------------------------------------
    | FORM BOOKING (PAGE)
    |--------------------------------------------------------------------------
    */
    public function create()
    {
        return Inertia::render('Public/Booking', [
            'jamList' => [
                '09:00',
                '10:00',
                '11:00',
                '13:00',
                '14:00',
                '15:00'
            ]
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | STORE BOOKING
    |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:150',
            'email' => 'nullable|email|max:150',
            'no_hp' => 'required|string|max:20',
            'properti_id' => 'required|exists:properties,id',
            'tanggal_konsultasi' => 'required|date',
            'jam_konsultasi' => 'required',
            'catatan' => 'nullable|string',
        ]);

        $exists = Booking::where('tanggal_konsultasi', $request->tanggal_konsultasi)
            ->where('jam_konsultasi', $request->jam_konsultasi)
            ->exists();

        if ($exists) {
            return back()->withErrors([
                'jam_konsultasi' => 'Slot jam ini sudah dibooking'
            ]);
        }

        $booking = $this->bookingService->createBooking($request->all());

        return back()->with([
            'success' => 'Booking berhasil dibuat!',
            'booking_code' => $booking->nomor_booking
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | CEK BOOKING (PUBLIC)
    |--------------------------------------------------------------------------
    */
    public function cek(Request $request)
    {
        $request->validate([
            'nomor_booking' => 'required|string'
        ]);

        $nomor = trim($request->nomor_booking);

        $booking = Booking::with(['client', 'property.kategori'])
            ->whereRaw('LOWER(nomor_booking) = ?', [strtolower($nomor)])
            ->first();

        return Inertia::render('Public/CekBooking', [
            'found' => (bool) $booking,
            'booking' => $booking,
            'message' => $booking
                ? 'Booking ditemukan'
                : 'Nomor booking tidak ditemukan'
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | GET SLOTS (PUBLIC API)
    |--------------------------------------------------------------------------
    */
    public function getSlots(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date'
        ]);

        $booked = Booking::where('tanggal_konsultasi', $request->tanggal)
            ->pluck('jam_konsultasi');

        return response()->json([
            'booked' => $booked
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | ADMIN: INDEX BOOKING
    |--------------------------------------------------------------------------
    */
    public function index(Request $request)
    {
        $query = Booking::with(['client', 'property'])
            ->latest();

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by tanggal
        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal_konsultasi', $request->tanggal);
        }

        // Search by nomor booking atau nama client
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nomor_booking', 'like', "%{$search}%")
                    ->orWhereHas('client', function ($q2) use ($search) {
                        $q2->where('nama', 'like', "%{$search}%");
                    });
            });
        }

        $bookings = $query->paginate(10)->withQueryString();

        return Inertia::render('Admin/Booking/Index', [
            'bookings' => $bookings,
            'filters' => $request->only(['status', 'tanggal', 'search']),
            'statusList' => ['Menunggu', 'Disetujui', 'Ditolak'],
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | ADMIN: SHOW BOOKING
    |--------------------------------------------------------------------------
    */
    public function show(Booking $booking)
    {
        $booking->load(['client', 'property.kategori', 'user']);

        return Inertia::render('Admin/Booking/Show', [
            'booking' => $booking,
            'statusList' => ['Menunggu', 'Disetujui', 'Ditolak'],
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | ADMIN: UPDATE STATUS BOOKING
    |--------------------------------------------------------------------------
    */
    public function updateStatus(Request $request, Booking $booking)
    {
        $request->validate([
            'status' => 'required|in:Menunggu,Disetujui,Ditolak',
        ]);

        $booking->update(['status' => $request->status]);

        return back()->with('success', 'Status booking berhasil diperbarui.');
    }

    /*
    |--------------------------------------------------------------------------
    | ADMIN: DELETE BOOKING
    |--------------------------------------------------------------------------
    */
    public function destroy(Booking $booking)
    {
        $booking->delete();

        return redirect()->route('admin.bookings.index')
            ->with('success', 'Booking berhasil dihapus.');
    }
}