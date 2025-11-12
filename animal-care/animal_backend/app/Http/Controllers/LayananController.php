<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Layanan;
use Illuminate\Support\Facades\Storage;

class LayananController extends Controller
{
    /**
     * Menampilkan semua layanan (Admin & User)
     */
    public function index()
    {
        $layanan = Layanan::all();
        return response()->json($layanan);
    }

    /**
     * Menambahkan layanan baru (Admin)
     */
    public function store(Request $request)
    {
        $request->validate([
            'logo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'nama_layanan' => 'required|string|max:100',
            'deskripsi' => 'nullable|string',
        ]);

        // Upload logo ke folder storage/app/public/layanan
        $path = $request->file('logo')->store('layanan', 'public');

        $layanan = Layanan::create([
            'logo' => $path,
            'nama_layanan' => $request->nama_layanan,
            'deskripsi' => $request->deskripsi,
        ]);

        return response()->json([
            'message' => 'Layanan berhasil ditambahkan!',
            'data' => $layanan,
        ]);
    }

    /**
     * Menampilkan detail layanan berdasarkan ID
     */
    public function show($id)
    {
        $layanan = Layanan::findOrFail($id);
        return response()->json($layanan);
    }

    /**
     * Mengupdate data layanan
     */
  public function update(Request $request, $id)
{
    $layanan = Layanan::find($id);

    if (!$layanan) {
        return response()->json(['message' => 'Data layanan tidak ditemukan!'], 404);
    }

    $request->validate([
        'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        'nama_layanan' => 'nullable|string|max:100',
        'deskripsi' => 'nullable|string',
    ]);

    // Jika upload logo baru
    if ($request->hasFile('logo')) {
        if ($layanan->logo && Storage::disk('public')->exists($layanan->logo)) {
            Storage::disk('public')->delete($layanan->logo);
        }
        $path = $request->file('logo')->store('layanan', 'public');
        $layanan->logo = $path;
    }

    // Update field lain (langsung assign)
    if ($request->has('nama_layanan')) {
        $layanan->nama_layanan = $request->nama_layanan;
    }
    if ($request->has('deskripsi')) {
        $layanan->deskripsi = $request->deskripsi;
    }

    // Simpan ke database
    $layanan->save();

    // Refresh agar data terbaru ditampilkan di response
    $layanan->refresh();

    return response()->json([
        'message' => 'Layanan berhasil diperbarui!',
        'data' => $layanan,
    ]);
}


    /**
     * Menghapus layanan
     */
    public function destroy($id)
    {
        $layanan = Layanan::findOrFail($id);

        if ($layanan->logo) {
            Storage::disk('public')->delete($layanan->logo);
        }

        $layanan->delete();

        return response()->json(['message' => 'Layanan berhasil dihapus!']);
    }
}
