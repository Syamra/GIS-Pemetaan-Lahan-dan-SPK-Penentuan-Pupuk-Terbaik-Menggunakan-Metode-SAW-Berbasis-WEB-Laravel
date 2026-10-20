<?php
namespace App\Http\Controllers;

use App\Models\Kriteria;
use Illuminate\Http\Request;

class KriteriaController extends Controller
{
    public function index() {
        $kriterias = Kriteria::all();
        return view('kriteria.index', compact('kriterias'));
    }

    public function store(Request $request) {
        $request->validate([
            'kode' => 'required',
            'nama_kriteria' => 'required',
            'atribut' => 'required|in:benefit,cost',
            'bobot' => 'required|numeric'
        ]);
        Kriteria::create($request->all());
        return back()->with('success', 'Kriteria berhasil ditambahkan!');
    }

    public function destroy($id) {
        Kriteria::findOrFail($id)->delete();
        return back()->with('success', 'Kriteria berhasil dihapus!');
    }

    public function update(Request $request, $id)
    {
        $kriteria = Kriteria::findOrFail($id);
        $kriteria->update($request->all());

        return back()->with('success', 'Data kriteria berhasil diperbarui!');
    }
}