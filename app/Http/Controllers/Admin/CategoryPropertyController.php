<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CategoryProperty;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CategoryPropertyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kategori = CategoryProperty::withCount('properti')
            ->latest()
            ->get();

        return Inertia::render('Admin/Category/Index', [
            'kategori' => $kategori,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:150|unique:category_properties,nama_kategori',
            'deskripsi' => 'nullable|string',
        ], [
            'nama_kategori.required' => 'Nama kategori wajib diisi.',
            'nama_kategori.unique' => 'Nama kategori sudah digunakan.',
            'nama_kategori.max' => 'Nama kategori maksimal 150 karakter.',
        ]);

        CategoryProperty::create($request->only('nama_kategori', 'deskripsi'));

        return back()->with('success', 'Kategori berhasil ditambahkan.');
    }

    /**
     * Update the specified resource in storage.
     */
    // update()
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

    // destroy()
    public function destroy(CategoryProperty $category)
    {
        if ($category->properti()->count() > 0) {
            return back()->with('error', 'Kategori tidak dapat dihapus karena masih memiliki properti terkait.');
        }

        $category->delete();

        return back()->with('success', 'Kategori berhasil dihapus.');
    }
}