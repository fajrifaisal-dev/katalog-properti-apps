<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CategoryProperty;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CategoryPropertyController extends Controller
{
    public function index()
    {
        $kategori = CategoryProperty::withCount('properti')
            ->latest()
            ->get();

        return Inertia::render('Admin/Category/Index', [
            'kategori' => $kategori,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:150|unique:category_properties,nama_kategori',
            'deskripsi' => 'nullable|string',
            'status_category' => 'nullable|in:aktif,tidak aktif',
        ], [
            'nama_kategori.required' => 'Nama kategori wajib diisi.',
            'nama_kategori.unique' => 'Nama kategori sudah digunakan.',
            'nama_kategori.max' => 'Nama kategori maksimal 150 karakter.',
        ]);

        CategoryProperty::create([
            'nama_kategori' => $request->nama_kategori,
            'deskripsi' => $request->deskripsi,
            'status_category' => $request->status_category ?? 'aktif',
        ]);

        return back()->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function update(Request $request, CategoryProperty $category)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:150|unique:category_properties,nama_kategori,' . $category->id,
            'deskripsi' => 'nullable|string',
        ], [
            'nama_kategori.required' => 'Nama kategori wajib diisi.',
            'nama_kategori.unique' => 'Nama kategori sudah digunakan.',
            'nama_kategori.max' => 'Nama kategori maksimal 150 karakter.',
        ]);

        $category->update($request->only('nama_kategori', 'deskripsi'));

        return back()->with('success', 'Kategori berhasil diperbarui.');
    }

    /**
     * Toggle status aktif <-> tidak aktif
     */
    public function toggleStatus(CategoryProperty $category)
    {
        $newStatus = ($category->status_category ?? 'aktif') === 'aktif'
            ? 'tidak aktif'
            : 'aktif';

        $category->update(['status_category' => $newStatus]);

        return back()->with('success', "Status kategori diubah menjadi \"{$newStatus}\".");
    }

    public function destroy(CategoryProperty $category)
    {
        if ($category->properti()->count() > 0) {
            return back()->with('error', 'Kategori tidak dapat dihapus karena masih memiliki properti terkait.');
        }

        if (($category->status_category ?? 'aktif') !== 'tidak aktif') {
            return back()->with('error', 'Nonaktifkan kategori terlebih dahulu sebelum menghapus.');
        }

        $category->deleted_by = auth()->user()->name ?? auth()->id();
        $category->save();
        $category->delete(); // soft delete (isi deleted_at)

        return back()->with('success', 'Kategori berhasil dihapus.');
    }
}