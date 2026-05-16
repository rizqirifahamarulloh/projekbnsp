@extends('admin.layouts.app')

@section('title', 'Daftar Pesanan')
@section('page-title', 'Daftar Pesanan')
@section('breadcrumb')
    <li class="breadcrumb-item active">Pesanan</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Daftar Pesanan</h3>
        <div class="card-tools">
            <form action="" method="GET" class="form-inline">
                <select name="status" class="form-control form-control-sm mr-2" onchange="this.form.submit()">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Paid</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </form>
        </div>
    </div>
    <div class="card-body table-responsive p-0">
        <table class="table table-hover text-nowrap">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Kode Pesanan</th>
                    <th>Pelanggan</th>
                    <th>Total</th>
                    <th>Metode</th>
                    <th>Status</th>
                    <th>Tanggal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                <tr>
                    <td>{{ $loop->iteration + ($orders->currentPage() - 1) * $orders->perPage() }}</td>
                    <td><a href="{{ route('admin.orders.show', $order->id) }}"><strong>{{ $order->order_code }}</strong></a></td>
                    <td>{{ $order->user->name ?? '-' }}</td>
                    <td>Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                    <td>{{ ucfirst($order->payment_method ?? '-') }}</td>
                    <td>
                        @if($order->status === 'paid')
                            <span class="badge badge-success">Paid</span>
                        @elseif($order->status === 'pending')
                            <span class="badge badge-warning">Pending</span>
                        @else
                            <span class="badge badge-danger">Cancelled</span>
                        @endif
                    </td>
                    <td>{{ $order->created_at->format('d M Y, H:i') }}</td>
                    <td>
                        <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-info btn-xs">
                            <i class="fas fa-eye"></i>
                        </a>
                        <div class="btn-group">
                            <button type="button" class="btn btn-sm btn-secondary dropdown-toggle" data-toggle="dropdown">
                                Ubah Status
                            </button>
                            <div class="dropdown-menu">
                                @foreach(['pending', 'paid', 'cancelled'] as $status)
                                <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST" class="d-inline">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="status" value="{{ $status }}">
                                    <button type="submit" class="dropdown-item {{ $order->status === $status ? 'active' : '' }}">
                                        {{ ucfirst($status) }}
                                    </button>
                                </form>
                                @endforeach
                            </div>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center text-muted py-3">Belum ada pesanan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($orders->hasPages())
    <div class="card-footer clearfix">
        {{ $orders->links('pagination::bootstrap-4') }}
    </div>
    @endif
</div>
@endsection
