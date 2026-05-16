@extends('admin.layouts.app')

@section('title', 'Detail Pesanan')
@section('page-title', 'Detail Pesanan')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.orders.index') }}">Pesanan</a></li>
    <li class="breadcrumb-item active">{{ $order->order_code }}</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Item Pesanan</h3>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Buku</th>
                            <th>Harga Satuan</th>
                            <th>Qty</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->orderItems as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <strong>{{ $item->book->title ?? 'Buku dihapus' }}</strong>
                                <br><small class="text-muted">{{ $item->book->author ?? '' }}</small>
                            </td>
                            <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="font-weight-bold">
                            <td colspan="4" class="text-right">Total:</td>
                            <td>Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Informasi Pesanan</h3>
            </div>
            <div class="card-body">
                <dl>
                    <dt>Kode Pesanan</dt>
                    <dd><code>{{ $order->order_code }}</code></dd>
                    <dt>Pelanggan</dt>
                    <dd>{{ $order->user->name ?? '-' }} ({{ $order->user->email ?? '-' }})</dd>
                    <dt>Status</dt>
                    <dd>
                        @if($order->status === 'paid')
                            <span class="badge badge-success badge-lg">Paid</span>
                        @elseif($order->status === 'pending')
                            <span class="badge badge-warning badge-lg">Pending</span>
                        @else
                            <span class="badge badge-danger badge-lg">Cancelled</span>
                        @endif
                    </dd>
                    <dt>Metode Pembayaran</dt>
                    <dd>{{ ucfirst($order->payment_method ?? '-') }}</dd>
                    <dt>Tanggal Pesan</dt>
                    <dd>{{ $order->created_at->format('d M Y, H:i:s') }}</dd>
                </dl>
            </div>
        </div>
    </div>
</div>
@endsection
