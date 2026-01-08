<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bantuan;

class BantuanController extends Controller
{
    public function index()
    {
        $data = Bantuan::orderBy('bidang')->orderBy('nama_bantuan')->get();
        return view('bantuan.index', compact('data'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_bantuan' => 'required|string|max:255|unique:bantuans,nama_bantuan',
            'bidang' => 'required|string|max:255',
            'satuan' => 'required|string|max:50',
        ]);

        Bantuan::create($validated);

        return redirect()->route('bantuan.index')
                         ->with('success', 'Jenis bantuan berhasil ditambahkan!');
    }

    public function edit(Bantuan $bantuan)
    {
        return view('bantuan.edit', compact('bantuan'));
    }

    public function update(Request $request, Bantuan $bantuan)
    {
        $validated = $request->validate([
            'nama_bantuan' => 'required|string|max:255|unique:bantuans,nama_bantuan,' . $bantuan->id,
            'bidang' => 'required|string|max:255',
            'satuan' => 'required|string|max:50',
        ]);

        $bantuan->update($validated);

        return redirect()->route('bantuan.index')
                         ->with('success', 'Jenis bantuan berhasil diperbarui!');
    }

    public function destroy(Bantuan $bantuan)
    {
        // Cek apakah bantuan sedang digunakan
        $digunakan = \App\Models\Beneficiary::where('jenis_bantuan', $bantuan->nama_bantuan)->exists();
        
        if ($digunakan) {
            return redirect()->route('bantuan.index')
                             ->with('error', 'Jenis bantuan tidak dapat dihapus karena sedang digunakan!');
        }

        $bantuan->delete();

        return redirect()->route('bantuan.index')
                         ->with('success', 'Jenis bantuan berhasil dihapus!');
    }
}