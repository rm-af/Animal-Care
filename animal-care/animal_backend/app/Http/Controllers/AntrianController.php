<?php

namespace App\Http\Controllers;

use App\Models\Antrian;
use Illuminate\Http\Request;

class AntrianController extends Controller
{
    // 1. ADMIN: Lihat semua antrian aktif
    public function index()
    {
        $data = Antrian::with(['user', 'hewan', 'layanan'])
            ->whereIn('status', ['menunggu', 'diproses', 'selesai'])
            ->orderBy('antrianId', 'asc')
            ->get();

        return response()->json($data);
    }

    // 2. USER: Lihat antrian miliknya
    public function userAntrian($userId)
    {
        $data = Antrian::where('userId', $userId)
            ->orderBy('antrianId', 'desc')
            ->get();

        return response()->json($data);
    }

    // 3. USER: Tambah antrian
    public function store(Request $request)
    {
        $validated = $request->validate([
            'userId' => 'required|integer',
            'hewanId' => 'required|integer',
            'layananId' => 'required|integer',
            'nama_hewan' => 'required|string|max:100',
            'keluhan' => 'required|string',
            'no_hp' => 'required|string|max:15',
        ]);

        $validated['status'] = 'menunggu';

        $antrian = Antrian::create($validated);


        $antrian->load(['user', 'hewan', 'layanan']);

        return response()->json([
            'message' => 'Antrian berhasil dibuat',
            'data' => $antrian
        ], 201);
    }

    //  4. USER: Edit antrian (jika status masih menunggu)
    public function update(Request $request, $id)
    {
        $antrian = Antrian::find($id);
        if (!$antrian) {
            return response()->json(['message' => 'Antrian tidak ditemukan'], 404);
        }

        if ($antrian->status !== 'menunggu') {
            return response()->json(['message' => 'Tidak bisa mengedit, antrian sudah diproses!'], 403);
        }

        // Validasi semua field yang boleh diubah
        $validated = $request->validate([
            'nama_hewan' => 'nullable|string|max:100',
            'hewanId' => 'nullable|integer|exists:hewan,hewanId',
            'keluhan' => 'nullable|string',
            'layananId' => 'nullable|integer|exists:layanan,layananId',
            'no_hp' => 'nullable|string|max:15',
        ]);

        // Update semua data termasuk hewan dan layanan
        $antrian->update($validated);

        // (opsional) reload relasi biar data baru ikut terkirim balik
        $antrian->load(['hewan', 'layanan']);

        return response()->json([
            'message' => 'Antrian berhasil diperbarui',
            'data' => $antrian
        ]);
    }


    // 🔹 5. USER: Hapus antrian (jika status masih menunggu)
    public function destroy($id)
    {
        $antrian = Antrian::find($id);

        if (!$antrian) {
            return response()->json(['message' => 'Antrian tidak ditemukan'], 404);
        }

        if ($antrian->status !== 'menunggu') {
            return response()->json(['message' => 'Tidak bisa menghapus, antrian sudah diproses!'], 403);
        }

        $antrian->delete();

        return response()->json(['message' => 'Antrian berhasil dihapus']);
    }

    // 6. ADMIN: Klik "Tangani" → ubah status ke diproses
    public function tangani($id)
    {
        $antrian = Antrian::find($id);
        if (!$antrian) {
            return response()->json(['message' => 'Antrian tidak ditemukan'], 404);
        }

        $antrian->status = 'diproses';
        $antrian->save();

        return response()->json(['message' => 'Antrian sedang ditangani']);
    }

    // 7. ADMIN: Klik "Selesai" → ubah status ke selesai
    public function selesai($id)
    {
        $antrian = Antrian::find($id);
        if (!$antrian) {
            return response()->json(['message' => 'Antrian tidak ditemukan'], 404);
        }

        $antrian->status = 'selesai';
        $antrian->save();

        return response()->json(['message' => 'Antrian telah selesai ditangani']);
    }

    // 8. MENU DASHBOARD: 3 card + history
    public function dashboard()
    {
        $totalPasien = Antrian::count();
        $belumDitangani = Antrian::where('status', 'menunggu')->count();
        $sedangDitangani = Antrian::where('status', 'diproses')->count();


        $history = Antrian::where('status', 'selesai')
            ->orderBy('antrianId', 'desc')
            ->get();

        return response()->json([
            'card' => [
                'total_pasien' => $totalPasien,
                'belum_ditangani' => $belumDitangani,
                'sedang_ditangani' => $sedangDitangani,
            ],
            'history' => $history
        ]);
    }

    // 🔹 Hapus antrian history (ADMIN)
    public function destroyHistory($id)
    {
        $antrian = Antrian::find($id);
        if (!$antrian) {
            return response()->json(['message' => 'Antrian tidak ditemukan'], 404);
        }

        if ($antrian->status !== 'selesai') {
            return response()->json(['message' => 'Hanya antrian selesai yang bisa dihapus'], 403);
        }

        $antrian->delete();
        return response()->json(['message' => 'Antrian history berhasil dihapus']);
    }

    public function show($id)
    {
        $antrian = Antrian::with(['hewan','layanan','user'])->find($id);

        if (!$antrian) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        return response()->json(['data' => $antrian]);
    }


    // 🔹 9. ADMIN: Hapus antrian secara penuh (termasuk yang selesai)
    public function destroyAdmin($id)
    {
        $antrian = Antrian::find($id);

        if (!$antrian) {
            return response()->json(['message' => 'Antrian tidak ditemukan'], 404);
        }

        
        $antrian->delete();

        return response()->json(['message' => 'Antrian berhasil dihapus oleh admin']);
    }


}
