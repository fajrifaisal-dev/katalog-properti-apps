<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\CategoryProperty;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PropertyCatalogController extends Controller
{
    public function index(Request $request)
    {
        $query = Property::with('kategori');

        if ($request->filled('kategori_id')) {
            $query->where('kategori_id', (int) $request->kategori_id);
        }

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->whereRaw('LOWER(nama_properti) LIKE ?', ['%' . strtolower($search) . '%'])
                    ->orWhereRaw('LOWER(lokasi) LIKE ?', ['%' . strtolower($search) . '%']);
            });
        }

        if ($request->filled('harga_min')) {
            $query->where('harga', '>=', (float) $request->harga_min);
        }

        if ($request->filled('harga_max')) {
            $query->where('harga', '<=', (float) $request->harga_max);
        }

        $properties = $query->orderBy('nama_properti')
            ->paginate(9)
            ->withQueryString();

        return Inertia::render('Public/Katalog', [
            'properties' => $properties,
            'kategori' => CategoryProperty::orderBy('nama_kategori')->get(),
            'filters' => $request->only(['kategori_id', 'search', 'harga_min', 'harga_max']),
        ]);
    }
    
    public function show(Property $property)
    {
        $property->load('kategori');

        $related = Property::with('kategori')
            ->where('kategori_id', $property->kategori_id)
            ->where('id', '!=', $property->id)
            ->limit(3)
            ->get();

        return Inertia::render('Public/PropertyDetail', [
            'property' => $property,
            'related' => $related,
        ]);
    }
}