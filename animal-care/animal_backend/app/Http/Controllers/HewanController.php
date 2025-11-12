<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Hewan;
use Illuminate\Support\Facades\Storage;

class HewanController extends Controller
{
    /**
     * Menampilkan semua data hewan (Admin & User)
     */
    public function index()
    {
        $hewan = Hewan::all();
        return response()->json($hewan);
    }

    /**
     * Menambahkan data hewan baru (Admin)
     */
    public function store(Request $request)
    {
        $request->validate([
            'gambar' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'nama_hewan' => 'required|string|max:100',
            'deskripsi' => 'nullable|string',
        ]);

        // Upload gambar ke folder storage/app/public/hewan
        $path = $request->file('gambar')->store('hewan', 'public');

        $hewan = Hewan::create([
            'gambar' => $path,
            'nama_hewan' => $request->nama_hewan,
            'deskripsi' => $request->deskripsi,
        ]);

        return response()->json([
            'message' => 'Hewan berhasil ditambahkan!',
            'data' => $hewan,
        ]);
    }

    /**
     * Menampilkan detail hewan berdasarkan ID
     */
    public function show($id)
    {
        $hewan = Hewan::find($id);

        if (!$hewan) {
            return response()->json(['message' => 'Data hewan tidak ditemukan!'], 404);
        }

        return response()->json($hewan);
    }

    /**
     * Mengupdate data hewan (Admin)
     */
    public function update(Request $request, $id)
    {
        $hewan = Hewan::find($id);

        if (!$hewan) {
            return response()->json(['message' => 'Data hewan tidak ditemukan!'], 404);
        }

        $request->validate([
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'nama_hewan' => 'nullable|string|max:100',
            'deskripsi' => 'nullable|string',
        ]);

        // Jika upload gambar baru
        if ($request->hasFile('gambar')) {
            if ($hewan->gambar && Storage::disk('public')->exists($hewan->gambar)) {
                Storage::disk('public')->delete($hewan->gambar);
            }
            $path = $request->file('gambar')->store('hewan', 'public');
            $hewan->gambar = $path;
        }

        // Update kolom lain
        if ($request->has('nama_hewan')) {
            $hewan->nama_hewan = $request->nama_hewan;
        }
        if ($request->has('deskripsi')) {
            $hewan->deskripsi = $request->deskripsi;
        }

        $hewan->save();
        $hewan->refresh();

        return response()->json([
            'message' => 'Hewan berhasil diperbarui!',
            'data' => $hewan,
        ]);
    }

    /**
     * Menghapus data hewan
     */
    public function destroy($id)
    {
        $hewan = Hewan::find($id);

        if (!$hewan) {
            return response()->json(['message' => 'Data hewan tidak ditemukan!'], 404);
        }

        // Hapus gambar di storage kalau ada
        if ($hewan->gambar && Storage::disk('public')->exists($hewan->gambar)) {
            Storage::disk('public')->delete($hewan->gambar);
        }

        $hewan->delete();

        return response()->json(['message' => 'Hewan berhasil dihapus!']);
    }
}
