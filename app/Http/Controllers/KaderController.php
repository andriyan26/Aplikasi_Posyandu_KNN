<?php

namespace App\Http\Controllers;

use App\Models\Kader;
use Illuminate\Http\Request;

class KaderController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        
        $query = Kader::latest();
        
        if ($search) {
            $query->where('nama', 'LIKE', "%{$search}%")
                  ->orWhere('id_kader', 'LIKE', "%{$search}%");
        }
        
        $kaders = $query->paginate(10)->withQueryString();
        
        return view('kader.index', compact('kaders', 'search'));
    }

    public function create()
    {
        return view('kader.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'nullable|string',
            'status_aktif' => 'required|in:Aktif,Tidak Aktif',
        ]);

        $kader = Kader::create([
            'nama' => $request->nama,
            'alamat' => $request->alamat,
            'status_aktif' => $request->status_aktif,
            'barcode_ttd' => 'KDR-' . time() . rand(10, 99) // Generate unique text for barcode
        ]);

        return redirect()->route('kader.index')->with('success', 'Data kader berhasil ditambahkan.');
    }

    public function edit(Kader $kader)
    {
        return view('kader.edit', compact('kader'));
    }

    public function update(Request $request, Kader $kader)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'nullable|string',
            'status_aktif' => 'required|in:Aktif,Tidak Aktif',
        ]);

        $kader->update([
            'nama' => $request->nama,
            'alamat' => $request->alamat,
            'status_aktif' => $request->status_aktif
        ]);

        return redirect()->route('kader.index')->with('success', 'Data kader berhasil diperbarui.');
    }

    public function destroy(Kader $kader)
    {
        $kader->delete();
        return redirect()->route('kader.index')->with('success', 'Data kader berhasil dihapus.');
    }
}
