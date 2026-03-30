<?php

namespace App\Http\Controllers;

use App\Models\Balita;
use Illuminate\Http\Request;

class BalitaController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $per_page = $request->get('per_page', 5); // Default 5

        $query = Balita::query()->latest();

        if ($search) {
            $query->where('nama', 'LIKE', "%{$search}%")
                  ->orWhere('nama_orang_tua', 'LIKE', "%{$search}%");
        }

        /** @var \Illuminate\Pagination\LengthAwarePaginator $balitas */
        if ($per_page === 'all') {
            $balitas = $query->paginate($query->count());
        } else {
            $balitas = $query->paginate($per_page);
        }
        $balitas->withQueryString();

        return view('balita.index', compact('balitas'));
    }

    public function create()
    {
        return view('balita.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:L,P',
            'nama_orang_tua' => 'required|string|max:255',
        ]);

        Balita::create($request->all());

        return redirect()->route('balita.index')->with('success', 'Data Balita berhasil ditambahkan');
    }

    public function edit(Balita $balita)
    {
        return view('balita.edit', compact('balita'));
    }

    public function update(Request $request, Balita $balita)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:L,P',
            'nama_orang_tua' => 'required|string|max:255',
        ]);

        $balita->update($request->all());

        return redirect()->route('balita.index')->with('success', 'Data Balita berhasil diupdate');
    }

    public function destroy(Balita $balita)
    {
        $balita->delete();
        return redirect()->route('balita.index')->with('success', 'Data Balita berhasil dihapus');
    }
}
