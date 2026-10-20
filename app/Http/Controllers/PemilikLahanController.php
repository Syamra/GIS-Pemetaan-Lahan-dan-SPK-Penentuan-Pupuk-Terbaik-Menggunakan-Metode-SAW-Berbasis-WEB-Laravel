<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PemilikLahan;

class PemilikLahanController extends Controller
{
    public function index()
    {
        $pemiliks = PemilikLahan::orderBy('nama_pemilik', 'asc')->get();
        return view('pemilik.index', compact('pemiliks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_pemilik' => 'required|string|max:50',
            'alamat' => 'required|string|max:200',
            'no_hp' => 'required|string|max:15',
        ]);

        PemilikLahan::create($request->all());

        return redirect()->route('pemilik.index')->with('success', 'Data pemilik lahan berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_pemilik' => 'required|string|max:50',
            'alamat' => 'required|string|max:200',
            'no_hp' => 'required|string|max:15',
        ]);

        $pemilik = PemilikLahan::findOrFail($id);
        $pemilik->update($request->all());

        return redirect()->route('pemilik.index')->with('success', 'Data pemilik lahan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $pemilik = PemilikLahan::findOrFail($id);
        $pemilik->delete();

        return redirect()->route('pemilik.index')->with('success', 'Data pemilik lahan berhasil dihapus.');
    }
}