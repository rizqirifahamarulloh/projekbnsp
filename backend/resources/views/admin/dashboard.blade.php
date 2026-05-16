{{-- resources/views/admin/dashboard.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')

{{-- ═══ ALERT: STOK MENIPIS ═══ --}}
@if($stokMenipis > 0)
<div class="alert alert-warning alert-dismissible fade show" role="alert">
    <i class="fas fa-exclamation-triangle mr-2"></i>
    <strong>Perhatian!</strong> Ada <strong>{{ $stokMenipis }}</strong> buku dengan stok menipis (≤ 5 unit).
    <a href="{{ route('admin.books.index') }}" class="alert-link ml-1">Lihat daftar buku →</a>
    <button type="button" class="close" data-dismiss="alert">&times;</button>
</div>
@endif

{{-- ═══ INDIKATOR WAKTU REFRESH ═══ --}}
<div class="text-right mb-3">
    <small class="text-muted">
        <i class="fas fa-sync-alt mr-1"></i> Terakhir diperbarui: {{ $waktuRefresh }}
    </small>
</div>

{{-- ══════════════════════════════════════════════════════════════════
     BAGIAN A — SUMMARY CARDS (6 Kartu, Grid 3 Kolom Desktop)
     ══════════════════════════════════════════════════════════════════ --}}
<div class="row">
    {{-- Kartu 1: Total Buku --}}
    <div class="col-lg-4 col-md-6 col-12">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ number_format($totalBuku, 0, ',', '.') }}</h3>
                <p>Total Buku</p>
            </div>
            <div class="icon"><i class="fas fa-book"></i></div>
            <a href="{{ route('admin.books.index') }}" class="small-box-footer">
                Kelola Buku <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    {{-- Kartu 2: Total Pengguna --}}
    <div class="col-lg-4 col-md-6 col-12">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ number_format($totalPengguna, 0, ',', '.') }}</h3>
                <p>Total Pengguna</p>
            </div>
            <div class="icon"><i class="fas fa-users"></i></div>
            <a href="{{ route('admin.users.index') }}" class="small-box-footer">
                Lihat Pengguna <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    {{-- Kartu 3: Total Pesanan --}}
    <div class="col-lg-4 col-md-6 col-12">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ number_format($totalPesanan, 0, ',', '.') }}</h3>
                <p>Total Pesanan</p>
            </div>
            <div class="icon"><i class="fas fa-shopping-cart"></i></div>
            <a href="{{ route('admin.orders.index') }}" class="small-box-footer">
                Lihat Pesanan <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    {{-- Kartu 4: Pendapatan Bulan Ini --}}
    <div class="col-lg-4 col-md-6 col-12">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3 style="font-size: 1.6rem;">Rp {{ number_format($pendapatanBulanIni, 0, ',', '.') }}</h3>
                <p>Pendapatan Bulan Ini</p>
            </div>
            <div class="icon"><i class="fas fa-wallet"></i></div>
            <a href="{{ route('admin.orders.index') }}?status=paid" class="small-box-footer">
                Pesanan Lunas <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    {{-- Kartu 5: Stok Menipis --}}
    <div class="col-lg-4 col-md-6 col-12">
        <div class="small-box bg-secondary">
            <div class="inner">
                <h3>{{ number_format($stokMenipis, 0, ',', '.') }}</h3>
                <p>Buku Stok Menipis</p>
            </div>
            <div class="icon"><i class="fas fa-box-open"></i></div>
            <a href="{{ route('admin.books.index') }}" class="small-box-footer">
                Kelola Stok <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    {{-- Kartu 6: Pesan Belum Dibaca --}}
    <div class="col-lg-4 col-md-6 col-12">
        <div class="small-box bg-primary">
            <div class="inner">
                <h3>{{ number_format($pesanBelumDibaca, 0, ',', '.') }}</h3>
                <p>Pesan Belum Dibaca</p>
            </div>
            <div class="icon"><i class="fas fa-envelope-open-text"></i></div>
            <a href="{{ route('admin.contacts.index') }}" class="small-box-footer">
                Buka Kotak Masuk <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════════════
     BAGIAN B — GRAFIK (Line Chart + Doughnut Chart)
     ══════════════════════════════════════════════════════════════════ --}}
<div class="row">
    {{-- Line Chart: Pesanan 7 Hari Terakhir --}}
    <div class="col-lg-8 col-12">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-chart-line mr-2"></i> Pesanan 7 Hari Terakhir</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div style="height: 280px;">
                    <canvas id="chartPesananMingguan"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- Doughnut Chart: Pesanan per Status --}}
    <div class="col-lg-4 col-12">
        <div class="card card-outline card-success">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-chart-pie mr-2"></i> Pesanan per Status</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div style="height: 280px;">
                    <canvas id="chartStatusPesanan"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════════════
     BAGIAN C — BAR CHART: Pendapatan per Kategori (lebar penuh)
     ══════════════════════════════════════════════════════════════════ --}}
<div class="row">
    <div class="col-12">
        <div class="card card-outline card-info">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-chart-bar mr-2"></i> Pendapatan per Kategori</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div style="height: 280px;">
                    <canvas id="chartPendapatanKategori"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════════════
     BAGIAN D — TABEL PESANAN TERBARU
     ══════════════════════════════════════════════════════════════════ --}}
<div class="row">
    <div class="col-12">
        <div class="card card-outline card-warning">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-clock mr-2"></i> 8 Pesanan Terbaru</h3>
                <div class="card-tools">
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-primary">
                        Lihat Semua <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover table-striped text-nowrap">
                    <thead class="thead-light">
                        <tr>
                            <th>No</th>
                            <th>Kode Pesanan</th>
                            <th>Nama Pembeli</th>
                            <th>Total</th>
                            <th>Metode</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pesananTerbaru as $order)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td><code>{{ $order->order_code }}</code></td>
                            <td>{{ $order->user->name ?? '-' }}</td>
                            <td class="font-weight-bold">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                            <td>{{ ucfirst($order->payment_method ?? '-') }}</td>
                            <td>
                                @switch($order->status)
                                    @case('paid')
                                        <span class="badge badge-success"><i class="fas fa-check mr-1"></i>Paid</span>
                                        @break
                                    @case('pending')
                                        <span class="badge badge-warning"><i class="fas fa-clock mr-1"></i>Pending</span>
                                        @break
                                    @case('cancelled')
                                        <span class="badge badge-danger"><i class="fas fa-times mr-1"></i>Cancelled</span>
                                        @break
                                @endswitch
                            </td>
                            <td>{{ $order->created_at->translatedFormat('d M Y, H:i') }}</td>
                            <td>
                                <a href="{{ route('admin.orders.show', $order->id) }}"
                                   class="btn btn-info btn-xs" title="Lihat Detail">
                                    <i class="fas fa-eye"></i> Lihat
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                                Belum ada pesanan.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════════════
     BAGIAN E — 2 TABEL KECIL BERDAMPINGAN
     ══════════════════════════════════════════════════════════════════ --}}
<div class="row">
    {{-- Tabel Kiri: Buku Stok Menipis --}}
    <div class="col-lg-6 col-12">
        <div class="card card-outline card-danger">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-exclamation-circle mr-2"></i> Buku Stok Menipis</h3>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">
                    <thead class="thead-light">
                        <tr>
                            <th width="60">Cover</th>
                            <th>Judul</th>
                            <th>Kategori</th>
                            <th>Stok</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bukuStokMenipis as $buku)
                        <tr class="{{ $buku->stock <= 3 ? 'table-danger' : ($buku->stock <= 10 ? 'table-warning' : '') }}">
                            <td>
                                @if($buku->cover_image)
                                    <img src="{{ asset('storage/' . $buku->cover_image) }}" alt="cover"
                                         style="width:40px; height:55px; object-fit:cover; border-radius:4px;">
                                @else
                                    <div class="bg-secondary d-flex align-items-center justify-content-center"
                                         style="width:40px; height:55px; border-radius:4px;">
                                        <i class="fas fa-image text-white" style="font-size:0.7rem;"></i>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <strong>{{ Str::limit($buku->title, 25) }}</strong>
                                <br><small class="text-muted">{{ $buku->author }}</small>
                            </td>
                            <td><span class="badge badge-info">{{ $buku->category->name ?? '-' }}</span></td>
                            <td>
                                @if($buku->stock <= 3)
                                    <span class="badge badge-danger font-weight-bold" style="font-size:0.9rem;">{{ $buku->stock }}</span>
                                @elseif($buku->stock <= 10)
                                    <span class="badge badge-warning font-weight-bold" style="font-size:0.9rem;">{{ $buku->stock }}</span>
                                @else
                                    <span class="badge badge-success">{{ $buku->stock }}</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-3">
                                <i class="fas fa-check-circle text-success mr-1"></i> Semua buku stoknya aman.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Tabel Kanan: Pesan Masuk Terbaru --}}
    <div class="col-lg-6 col-12">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-envelope mr-2"></i> Pesan Masuk Terbaru</h3>
                <div class="card-tools">
                    <a href="{{ route('admin.contacts.index') }}" class="btn btn-sm btn-outline-primary">
                        Semua Pesan <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">
                    <thead class="thead-light">
                        <tr>
                            <th>Nama</th>
                            <th>Subjek</th>
                            <th>Waktu</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pesanTerbaru as $pesan)
                        <tr class="{{ !$pesan->is_read ? 'font-weight-bold' : '' }}">
                            <td>
                                {{ $pesan->name }}
                                <br><small class="text-muted">{{ $pesan->email }}</small>
                            </td>
                            <td>{{ Str::limit($pesan->subject, 25) }}</td>
                            <td>{{ $pesan->created_at->diffForHumans() }}</td>
                            <td>
                                @if(!$pesan->is_read)
                                    <span class="badge badge-danger"><i class="fas fa-circle mr-1" style="font-size:0.5rem;"></i>Baru</span>
                                @else
                                    <span class="badge badge-secondary"><i class="fas fa-check mr-1"></i>Dibaca</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-3">
                                <i class="fas fa-inbox mr-1"></i> Belum ada pesan masuk.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection

{{-- ══════════════════════════════════════════════════════════════════
     BAGIAN JAVASCRIPT — Chart.js Initialization
     ══════════════════════════════════════════════════════════════════ --}}
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {

    // Data dari Controller (dikirim via Blade JSON directive)
    const dataPesananMingguan     = @json($grafikPesananMingguan);
    const dataPendapatanKategori  = @json($grafikPendapatanKategori);
    const dataStatusPesanan       = @json($grafikStatusPesanan);

    // Helper format Rupiah
    const formatRupiah = (angka) => {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency', currency: 'IDR', minimumFractionDigits: 0
        }).format(angka);
    };

    // ═══════════════════════════════════════════
    // CHART 1: Line Chart — Pesanan 7 Hari Terakhir
    // ═══════════════════════════════════════════
    new Chart(document.getElementById('chartPesananMingguan'), {
        type: 'line',
        data: {
            labels: dataPesananMingguan.map(d => d.label),
            datasets: [{
                label: 'Jumlah Pesanan',
                data: dataPesananMingguan.map(d => d.jumlah),
                borderColor: '#007bff',
                backgroundColor: 'rgba(0, 123, 255, 0.1)',
                borderWidth: 2.5,
                tension: 0.4,
                fill: true,
                pointBackgroundColor: '#007bff',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 5,
                pointHoverRadius: 7,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: (ctx) => `${ctx.parsed.y} pesanan`
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1, precision: 0 },
                    grid: { color: 'rgba(0,0,0,0.05)' }
                },
                x: {
                    grid: { display: false }
                }
            }
        }
    });

    // ═══════════════════════════════════════════
    // CHART 2: Doughnut — Pesanan per Status
    // ═══════════════════════════════════════════
    new Chart(document.getElementById('chartStatusPesanan'), {
        type: 'doughnut',
        data: {
            labels: ['Pending', 'Paid', 'Cancelled'],
            datasets: [{
                data: [
                    dataStatusPesanan.pending ?? 0,
                    dataStatusPesanan.paid ?? 0,
                    dataStatusPesanan.cancelled ?? 0,
                ],
                backgroundColor: ['#ffc107', '#28a745', '#dc3545'],
                borderWidth: 2,
                borderColor: '#fff',
                hoverOffset: 8,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { padding: 20, usePointStyle: true, pointStyle: 'circle' }
                },
                tooltip: {
                    callbacks: {
                        label: (ctx) => `${ctx.label}: ${ctx.parsed} pesanan`
                    }
                }
            },
            cutout: '55%',
        }
    });

    // ═══════════════════════════════════════════
    // CHART 3: Bar — Pendapatan per Kategori
    // ═══════════════════════════════════════════
    const warnaBar = ['#007bff', '#28a745', '#17a2b8', '#ffc107', '#dc3545', '#6f42c1', '#e83e8c', '#fd7e14'];

    new Chart(document.getElementById('chartPendapatanKategori'), {
        type: 'bar',
        data: {
            labels: dataPendapatanKategori.map(d => d.kategori),
            datasets: [{
                label: 'Pendapatan',
                data: dataPendapatanKategori.map(d => d.total_pendapatan),
                backgroundColor: dataPendapatanKategori.map((_, i) => warnaBar[i % warnaBar.length]),
                borderRadius: 6,
                borderSkipped: false,
                maxBarThickness: 60,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: (ctx) => `Pendapatan: ${formatRupiah(ctx.parsed.y)}`
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: (val) => formatRupiah(val),
                    },
                    grid: { color: 'rgba(0,0,0,0.05)' }
                },
                x: {
                    grid: { display: false }
                }
            }
        }
    });

});
</script>
@endpush
