<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Aspirasi;
use App\Models\Kategori;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class AspirasiController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
            'status' => ['nullable', 'in:menunggu,diproses,selesai'],
            'kategori' => ['nullable', 'integer', 'exists:kategoris,id'],
            'urut' => ['nullable', 'in:terbaru,terlama,prioritas'],
        ]);

        $aspirasis = Aspirasi::with(['kategori', 'user'])
            ->cari($filters['q'] ?? null)
            ->when($filters['status'] ?? null, fn ($query, $status) => $query->where('status', $status))
            ->when($filters['kategori'] ?? null, fn ($query, $kategori) => $query->where('kategori_id', $kategori));

        match ($filters['urut'] ?? 'terbaru') {
            'terlama' => $aspirasis->oldest(),
            'prioritas' => $aspirasis->orderByRaw("FIELD(prioritas, 'tinggi', 'sedang', 'rendah')")->latest(),
            default => $aspirasis->latest(),
        };

        return view('admin.aspirasi.index', [
            'aspirasis' => $aspirasis->paginate(10)->withQueryString(),
            'kategoris' => Kategori::orderBy('nama')->get(),
            'filters' => $filters,
            'jumlah' => [
                'semua' => Aspirasi::count(),
                'menunggu' => Aspirasi::where('status', Aspirasi::STATUS_MENUNGGU)->count(),
                'diproses' => Aspirasi::where('status', Aspirasi::STATUS_DIPROSES)->count(),
                'selesai' => Aspirasi::where('status', Aspirasi::STATUS_SELESAI)->count(),
            ],
        ]);
    }

    public function show(Aspirasi $aspirasi): View
    {
        $aspirasi->load(['kategori', 'user']);

        return view('admin.aspirasi.show', [
            'aspirasi' => $aspirasi,
        ]);
    }

    public function update(Request $request, Aspirasi $aspirasi): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', 'in:menunggu,diproses,selesai'],
            'prioritas' => ['required', 'in:rendah,sedang,tinggi'],
            'tanggapan' => ['nullable', 'string', 'max:2000'],
        ], [
            'status.required' => 'Pilih status penanganan.',
            'status.in' => 'Status penanganan tidak dikenal.',
            'prioritas.required' => 'Pilih tingkat prioritas.',
            'tanggapan.max' => 'Tanggapan maksimal 2000 karakter.',
        ]);

        $data['ditanggapi_pada'] = filled($data['tanggapan']) ? now() : null;

        $aspirasi->update($data);

        return redirect()
            ->route('admin.aspirasi.show', $aspirasi)
            ->with('status', 'Status dan tanggapan berhasil diperbarui.');
    }

    public function destroy(Aspirasi $aspirasi): RedirectResponse
    {
        if ($aspirasi->foto) {
            Storage::disk('public')->delete($aspirasi->foto);
        }

        $aspirasi->delete();

        return redirect()
            ->route('admin.aspirasi.index')
            ->with('status', 'Aspirasi berhasil dihapus dari sistem.');
    }
}
