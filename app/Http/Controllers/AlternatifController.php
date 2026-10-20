<?php
namespace App\Http\Controllers;

use App\Models\Alternatif;
use Illuminate\Http\Request;

class AlternatifController extends Controller
{
    public function index() {
        $alternatifs = Alternatif::all();
        return view('alternatif.index', compact('alternatifs'));
    }

    public function store(Request $request) {
        $request->validate([
            'kode' => 'required',
            'nama_pupuk' => 'required',
            'harga' => 'required|numeric'
        ]);
        Alternatif::create($request->all());
        return back()->with('success', 'Alternatif pupuk berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $alternatif = Alternatif::findOrFail($id);
        $alternatif->update($request->all());

        return back()->with('success', 'Data pupuk berhasil diperbarui!');
    }

    public function destroy($id) {
        Alternatif::findOrFail($id)->delete();
        return back()->with('success', 'Alternatif pupuk berhasil dihapus!');
    }
}