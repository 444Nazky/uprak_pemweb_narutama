<?php

namespace App\Http\Controllers;

use App\Models\Aspirasi;
use App\Models\Kategori;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(): View
    {
        $total = Aspirasi::count();
        $selesai = Aspirasi::where('status', Aspirasi::STATUS_SELESAI)->count();

        return view('home', [
            'total' => $total,
            'menunggu' => Aspirasi::where('status', Aspirasi::STATUS_MENUNGGU)->count(),
            'diproses' => Aspirasi::where('status', Aspirasi::STATUS_DIPROSES)->count(),
            'selesai' => $selesai,
            'persenSelesai' => $total > 0 ? (int) round($selesai / $total * 100) : 0,
            'kategoris' => Kategori::withCount('aspirasis')->orderBy('id')->get(),
        ]);
    }
}
