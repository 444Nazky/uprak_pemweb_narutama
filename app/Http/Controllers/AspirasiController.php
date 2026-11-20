<?php

namespace App\Http\Controllers;

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
        $aspirasis = Aspirasi::with('kategori')
            ->milik($request->user()->id)
            ->latest()
            ->paginate(6);

        return view('aspirasi.index', [
            'aspirasis' => $aspirasis,
            'ringkasan' => [
                'total' => Aspirasi::milik($request->user()->id)->count(),
                'menunggu' => Aspirasi::milik($request->user()->id)->where('status', Aspirasi::STATUS_MENUNGGU)->count(),
                'selesai' => Aspirasi::milik($request->user()->id)->where('status', Aspirasi::STATUS_SELESAI)->count(),
            ],
        ]);
    }

    public function create(): View
    {
        return view('aspirasi.create', [
            'kategoris' => Kategori::orderBy('nama')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);

        $data['user_id'] = $request->user()->id;
        $data['status'] = Aspirasi::STATUS_MENUNGGU;

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('bukti', 'public');
        }

        $aspirasi = Aspirasi::create($data);

        return redirect()
            ->route('aspirasi.show', $aspirasi)
            ->with('status', 'Aspirasi berhasil dikirim. Pantau statusnya di halaman ini.');
    }

    public function show(Request $request, Aspirasi $aspirasi): View
    {
        $this->authorizeOwner($request, $aspirasi);
        $aspirasi->load(['kategori', 'user']);

        return view('aspirasi.show', [
            'aspirasi' => $aspirasi,
        ]);
    }

    public function edit(Request $request, Aspirasi $aspirasi): View|RedirectResponse
    {
        $this->authorizeOwner($request, $aspirasi);

        if ($aspirasi->status !== Aspirasi::STATUS_MENUNGGU) {
            return redirect()
                ->route('aspirasi.show', $aspirasi)
                ->with('error', 'Aspirasi yang sudah diproses tidak dapat diubah lagi.');
        }

        return view('aspirasi.edit', [
            'aspirasi' => $aspirasi,
            'kategoris' => Kategori::orderBy('nama')->get(),
        ]);
    }

    public function update(Request $request, Aspirasi $aspirasi): RedirectResponse
    {
        $this->authorizeOwner($request, $aspirasi);

        if ($aspirasi->status !== Aspirasi::STATUS_MENUNGGU) {
            return redirect()
                ->route('aspirasi.show', $aspirasi)
                ->with('error', 'Aspirasi yang sudah diproses tidak dapat diubah lagi.');
        }

        $data = $this->validated($request);

        if ($request->hasFile('foto')) {
            $this->deleteFoto($aspirasi);
            $data['foto'] = $request->file('foto')->store('bukti', 'public');
        }

        $aspirasi->update($data);

        return redirect()
            ->route('aspirasi.show', $aspirasi)
            ->with('status', 'Perubahan aspirasi berhasil disimpan.');
    }

    public function destroy(Request $request, Aspirasi $aspirasi): RedirectResponse
    {
        $this->authorizeOwner($request, $aspirasi);

        if ($aspirasi->status !== Aspirasi::STATUS_MENUNGGU) {
            return redirect()
                ->route('aspirasi.show', $aspirasi)
                ->with('error', 'Aspirasi yang sudah diproses tidak dapat dihapus.');
        }

        $this->deleteFoto($aspirasi);
        $aspirasi->delete();

        return redirect()
            ->route('aspirasi.index')
            ->with('status', 'Aspirasi berhasil dihapus.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'judul' => ['required', 'string', 'min:5', 'max:150'],
            'kategori_id' => ['required', 'integer', 'exists:kategoris,id'],
            'lokasi' => ['required', 'string', 'min:3', 'max:150'],
            'pesan' => ['required', 'string', 'min:20', 'max:2000'],
            'prioritas' => ['required', 'in:rendah,sedang,tinggi'],
            'foto' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ], [
            'judul.required' => 'Judul aspirasi wajib diisi.',
            'judul.min' => 'Judul minimal 5 karakter.',
            'kategori_id.required' => 'Pilih kategori aspirasi.',
            'kategori_id.exists' => 'Kategori yang dipilih tidak tersedia.',
            'lokasi.required' => 'Lokasi wajib diisi.',
            'pesan.required' => 'Isi aspirasi wajib diisi.',
            'pesan.min' => 'Tuliskan isi aspirasi minimal 20 karakter agar mudah dipahami.',
            'prioritas.required' => 'Pilih tingkat prioritas.',
            'foto.image' => 'Berkas harus berupa gambar.',
            'foto.mimes' => 'Foto harus berformat JPG atau PNG.',
            'foto.max' => 'Ukuran foto maksimal 2 MB.',
        ]);
    }

    private function authorizeOwner(Request $request, Aspirasi $aspirasi): void
    {
        abort_unless(
            $aspirasi->user_id === $request->user()->id,
            403,
            'Anda tidak berhak membuka aspirasi milik siswa lain.',
        );
    }

    private function deleteFoto(Aspirasi $aspirasi): void
    {
        if ($aspirasi->foto) {
            Storage::disk('public')->delete($aspirasi->foto);
        }
    }
}
