<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ClientController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | ADMIN: INDEX CLIENT
    |--------------------------------------------------------------------------
    */
    public function index(Request $request)
    {
        $query = Client::withCount('bookings')->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('no_hp', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $clients = $query->paginate(10)->withQueryString();

        return Inertia::render('Admin/Client/Index', [
            'clients' => $clients,
            'filters' => $request->only(['search']),
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | ADMIN: EDIT CLIENT
    |--------------------------------------------------------------------------
    */
    public function edit(Client $client)
    {
        return Inertia::render('Admin/Client/Edit', [
            'client' => $client,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | ADMIN: UPDATE CLIENT
    |--------------------------------------------------------------------------
    */
    public function update(Request $request, Client $client)
    {
        $request->validate([
            'nama'  => 'required|string|max:150',
            'email' => 'nullable|email|max:150',
            'no_hp' => 'required|string|max:20',
        ]);

        $client->update($request->only(['nama', 'email', 'no_hp']));

        return redirect()->route('admin.clients.index')
            ->with('success', 'Data client berhasil diperbarui.');
    }

    /*
    |--------------------------------------------------------------------------
    | ADMIN: DESTROY CLIENT
    |--------------------------------------------------------------------------
    */
    public function destroy(Client $client)
    {
        $hasActiveBooking = $client->bookings()
            ->whereNotIn('status', ['Ditolak'])
            ->exists();

        if ($hasActiveBooking) {
            return back()->withErrors([
                'delete' => 'Client tidak dapat dihapus karena masih memiliki booking aktif. Tolak semua booking terlebih dahulu.'
            ]);
        }

        $client->delete();

        return redirect()->route('admin.clients.index')
            ->with('success', 'Client berhasil dihapus.');
    }
}