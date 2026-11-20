<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Aspirasi;
use App\Models\Kategori;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $perKategori = Kategori::withCount('aspirasis')->orderByDesc('aspirasis_count')->get();

        $bulan = collect(range(5, 0))->map(function (int $mundur) {
            $tanggal = Carbon::now()->startOfMonth()->subMonths($mundur);

            return [
                'label' => $tanggal->translatedFormat('M Y'),
                'jumlah' => Aspirasi::whereBetween('created_at', [
                    $tanggal->copy()->startOfMonth(),
                    $tanggal->copy()->endOfMonth(),
                ])->count(),
            ];
        });

        return view('admin.dashboard', [
            'total' => Aspirasi::count(),
            'menunggu' => Aspirasi::where('status', Aspirasi::STATUS_MENUNGGU)->count(),
            'diproses' => Aspirasi::where('status', Aspirasi::STATUS_DIPROSES)->count(),
            'selesai' => Aspirasi::where('status', Aspirasi::STATUS_SELESAI)->count(),
            'perKategori' => $perKategori,
            'bulanan' => $bulan,
            'terbaru' => Aspirasi::with(['kategori', 'user'])->latest()->take(5)->get(),
        ]);
    }
}
