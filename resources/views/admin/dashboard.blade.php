@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
    <div class="ka-page-head">
        <div>
            <h1>Dashboard</h1>
            <p>Ringkasan aspirasi siswa per hari ini.</p>
        </div>
        <a href="{{ route('admin.aspirasi.index') }}" class="btn btn-ka">
            <x-icon name="list-ul" />
            Kelola aspirasi
        </a>
    </div>

    <div class="ka-stat-grid">
        <div class="ka-stat ka-stat--dark">
            <div class="ka-stat-head">
                <span class="ka-stat-label">Total aspirasi</span>
                <span class="ka-stat-icon"><x-icon name="inbox" /></span>
            </div>
            <div class="ka-stat-value ka-num">{{ $total }}</div>
            <div class="ka-stat-note">Seluruh laporan yang tercatat</div>
        </div>

        <div class="ka-stat">
            <div class="ka-stat-head">
                <span class="ka-stat-label">Menunggu</span>
                <span class="ka-stat-icon" style="background:var(--ka-warning-bg);color:var(--ka-warning);">
                    <x-icon name="hourglass-split" />
                </span>
            </div>
            <div class="ka-stat-value ka-num">{{ $menunggu }}</div>
            <div class="ka-stat-note">Belum ditinjau admin</div>
        </div>

        <div class="ka-stat">
            <div class="ka-stat-head">
                <span class="ka-stat-label">Diproses</span>
                <span class="ka-stat-icon" style="background:var(--ka-info-bg);color:var(--ka-info);">
                    <x-icon name="clock-history" />
                </span>
            </div>
            <div class="ka-stat-value ka-num">{{ $diproses }}</div>
            <div class="ka-stat-note">Sedang ditindaklanjuti</div>
        </div>

        <div class="ka-stat">
            <div class="ka-stat-head">
                <span class="ka-stat-label">Selesai</span>
                <span class="ka-stat-icon" style="background:var(--ka-success-bg);color:var(--ka-success);">
                    <x-icon name="check-circle" />
                </span>
            </div>
            <div class="ka-stat-value ka-num">{{ $selesai }}</div>
            <div class="ka-stat-note">Sudah ditangani</div>
        </div>
    </div>

    <div class="ka-chart-grid">
        <div class="ka-chart-card">
            <div class="ka-chart-head">
                <h2>Laporan per kategori</h2>
                <span>Total {{ $total }}</span>
            </div>
            <div class="ka-chart-box">
                <canvas id="chart-kategori" aria-label="Grafik jumlah laporan per kategori" role="img"></canvas>
            </div>
        </div>

        <div class="ka-chart-card">
            <div class="ka-chart-head">
                <h2>Laporan enam bulan terakhir</h2>
                <span>Berdasarkan tanggal kirim</span>
            </div>
            <div class="ka-chart-box">
                <canvas id="chart-bulanan" aria-label="Grafik jumlah laporan per bulan" role="img"></canvas>
            </div>
        </div>
    </div>

    <div class="ka-card" style="overflow:hidden;">
        <div style="display:flex;align-items:center;justify-content:space-between;gap:16px;padding:20px 22px;border-bottom:1px solid var(--ka-line);">
            <div>
                <div class="ka-panel-title">Laporan terbaru</div>
                <div class="ka-small ka-muted">Lima aspirasi yang paling baru masuk.</div>
            </div>
            <a href="{{ route('admin.aspirasi.index') }}" class="btn btn-ka-quiet btn-sm">
                Lihat semua
                <x-icon name="arrow-right" />
            </a>
        </div>

        @if ($terbaru->isEmpty())
            <div style="padding:24px;">
                <x-empty-state icon="inbox" title="Belum ada aspirasi" description="Laporan siswa akan muncul di sini." />
            </div>
        @else
            <div class="table-responsive d-none d-lg-block">
                <table class="ka-table">
                    <thead>
                        <tr>
                            <th>Laporan</th>
                            <th>Pelapor</th>
                            <th>Kategori</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($terbaru as $item)
                            <tr>
                                <td>
                                    <a href="{{ route('admin.aspirasi.show', $item) }}" class="ka-table-title">{{ $item->judul }}</a>
                                    <span class="ka-table-sub"><x-icon name="geo-alt" />{{ $item->lokasi }}</span>
                                </td>
                                <td>
                                    <span style="font-weight:600;">{{ $item->user->name }}</span>
                                    <span class="ka-table-sub">{{ $item->user->kelas }}</span>
                                </td>
                                <td>{{ $item->kategori->nama }}</td>
                                <td><x-status-badge :status="$item->status" /></td>
                                <td class="ka-small ka-muted">{{ $item->created_at->translatedFormat('d M Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="ka-mobile-list d-lg-none" style="padding:16px;">
                @foreach ($terbaru as $item)
                    <div class="ka-mobile-card">
                        <div class="ka-mobile-card-top">
                            <a href="{{ route('admin.aspirasi.show', $item) }}" class="ka-table-title">{{ $item->judul }}</a>
                            <x-status-badge :status="$item->status" />
                        </div>
                        <div class="ka-meta" style="margin:8px 0 0;">
                            <span>{{ $item->user->name }} &middot; {{ $item->user->kelas }}</span>
                            <span>{{ $item->created_at->translatedFormat('d M Y') }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('vendor/chartjs/chart.umd.js') }}"></script>
    <script>
        (function () {
            if (typeof Chart === "undefined") return;

            Chart.defaults.font.family = "'Plus Jakarta Sans', system-ui, sans-serif";
            Chart.defaults.color = "#64746c";
            Chart.defaults.font.size = 12;

            var kategori = @json($perKategori);
            var bulanan = @json($bulanan);

            new Chart(document.getElementById("chart-kategori"), {
                type: "doughnut",
                data: {
                    labels: kategori.map(function (item) { return item.nama; }),
                    datasets: [{
                        data: kategori.map(function (item) { return item.aspirasis_count; }),
                        backgroundColor: ["#14513f", "#4c7f68", "#9cb6a5", "#d5e1d7"],
                        borderWidth: 0,
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: "66%",
                    plugins: {
                        legend: {
                            position: "bottom",
                            labels: { boxWidth: 9, boxHeight: 9, usePointStyle: true, pointStyle: "circle", padding: 14 }
                        }
                    }
                }
            });

            new Chart(document.getElementById("chart-bulanan"), {
                type: "bar",
                data: {
                    labels: bulanan.map(function (item) { return item.label; }),
                    datasets: [{
                        label: "Laporan",
                        data: bulanan.map(function (item) { return item.jumlah; }),
                        backgroundColor: "#14513f",
                        borderRadius: 5,
                        maxBarThickness: 42
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        x: { grid: { display: false }, border: { color: "#dbe2da" } },
                        y: {
                            beginAtZero: true,
                            ticks: { precision: 0 },
                            grid: { color: "rgba(219, 226, 218, 0.8)" },
                            border: { display: false }
                        }
                    }
                }
            });
        })();
    </script>
@endpush
