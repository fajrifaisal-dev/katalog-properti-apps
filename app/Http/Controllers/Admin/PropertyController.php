<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\CategoryProperty;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;

class PropertyController extends Controller
{
    public function index()
    {
        $properties = Property::with('kategori')
            ->latest()
            ->paginate(10);

        $kategori = CategoryProperty::orderBy('nama_kategori')->get();

        return Inertia::render('Admin/Property/Index', [
            'properties' => $properties,
            'kategori' => $kategori,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'kategori_id' => 'required|exists:category_properties,id',
            'nama_properti' => 'required|string|max:200',
            'lokasi' => 'required|string|max:200',
            'harga' => 'required|numeric|min:0',
            'deskripsi' => 'nullable|string',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'kategori_id.required' => 'Kategori wajib dipilih.',
            'kategori_id.exists' => 'Kategori tidak valid.',
            'nama_properti.required' => 'Nama properti wajib diisi.',
            'lokasi.required' => 'Lokasi wajib diisi.',
            'harga.required' => 'Harga wajib diisi.',
            'harga.numeric' => 'Harga harus berupa angka.',
            'gambar.image' => 'File harus berupa gambar.',
            'gambar.max' => 'Ukuran gambar maksimal 2MB.',
        ]);

        $data = $request->only('kategori_id', 'nama_properti', 'lokasi', 'harga', 'deskripsi');

        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('properties', 'public');
        }

        Property::create($data);

        return back()->with('success', 'Properti berhasil ditambahkan.');
    }

    public function update(Request $request, Property $property)
    {
        $request->validate([
            'kategori_id' => 'required|exists:category_properties,id',
            'nama_properti' => 'required|string|max:200',
            'lokasi' => 'required|string|max:200',
            'harga' => 'required|numeric|min:0',
            'deskripsi' => 'nullable|string',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'kategori_id.required' => 'Kategori wajib dipilih.',
            'kategori_id.exists' => 'Kategori tidak valid.',
            'nama_properti.required' => 'Nama properti wajib diisi.',
            'lokasi.required' => 'Lokasi wajib diisi.',
            'harga.required' => 'Harga wajib diisi.',
            'harga.numeric' => 'Harga harus berupa angka.',
            'gambar.image' => 'File harus berupa gambar.',
            'gambar.max' => 'Ukuran gambar maksimal 2MB.',
        ]);

        $data = $request->only('kategori_id', 'nama_properti', 'lokasi', 'harga', 'deskripsi');

        if ($request->hasFile('gambar')) {
            // hapus gambar lama
            if ($property->gambar) {
                Storage::disk('public')->delete($property->gambar);
            }
            $data['gambar'] = $request->file('gambar')->store('properties', 'public');
        }

        $property->update($data);

        return back()->with('success', 'Properti berhasil diperbarui.');
    }

    public function destroy(Property $property)
    {
        // Guard: masih ada booking terkait
        if ($property->bookings()->count() > 0) {
            return back()->with('error', 'Properti tidak dapat dihapus karena masih memiliki data booking terkait.');
        }

        try {
            $property->deleted_by = auth()->user()->name ?? auth()->id();
            $property->save();
            $property->delete(); // soft delete — file gambar TETAP di storage

            return back()->with('success', 'Properti berhasil dihapus.');
        } catch (\Illuminate\Database\QueryException $e) {
            return back()->with('error', 'Properti tidak dapat dihapus karena masih digunakan oleh data lain.');
        }
    }
}
