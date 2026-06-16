@extends('layout.main')

@section('title', 'Dashboard - ' . Auth::user()->name)
@section('namepage', 'Dashboard Overview')
@section('namemenu', 'Overview')
@section('route', route('dashboard'))

@section('content')

<div class="row">
    <div class="col-xl-6 mb-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 fw-bold text-dark"><i class="fas fa-hourglass-start me-2 text-warning"></i>Queue</h6>
                <a href="{{ route('realtime.queueFull') }}" target="_blank" class="btn btn-sm btn-primary fw-bold">
                    <i class="fas fa-external-link-alt me-1"></i> Open Fullscreen
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="px-3" style="width: 50px;">No</th>
                                <th>Customer</th>
                                <th>Items</th>
                                <th style="width: 80px;">Time</th>
                            </tr>
                        </thead>
                        <tbody id="realtimeQueueTbody">
                            @forelse($queueOrders as $index => $order)
                            <tr>
                                <td class="px-3 fw-bold text-muted">{{ $index + 1 }}</td>
                                <td><span class="fw-bold text-dark">{{ $order->customer_name }}</span></td>
                                <td>
                                    <span class="small text-muted">
                                        {{ $order->orderDetails->map(fn($d) => ($d->product->product_name ?? 'Product Deleted') . " (x{$d->qty})")->join(', ') }}
                                    </span>
                                </td>
                                <td><span class="badge bg-light text-dark">{{ $order->created_at->format('H:i') }}</span></td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="text-center py-4 text-muted small">Not found any order.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-6 mb-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 fw-bold text-dark"><i class="fas fa-box me-2 text-success"></i>Catalog</h6>
                <a href="{{ route('realtime.productFull') }}" target="_blank" class="btn btn-sm btn-primary fw-bold">
                    <i class="fas fa-external-link-alt me-1"></i> Open Fullscreen
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="px-3">Category</th>
                                <th>Product Name</th>
                                <th>Price</th>
                                <th class="text-center" style="width: 80px;">Stock</th>
                            </tr>
                        </thead>
                        <tbody id="realtimeProductTbody">
                            @forelse($readyProducts as $product)
                            <tr>
                                <td class="px-3"><span class="badge bg-soft-secondary text-secondary">{{ $product->category->category_name ?? 'Uncategorized' }}</span></td>
                                <td><span class="fw-semibold text-dark">{{ $product->product_name }}</span></td>
                                <td class="fw-bold text-primary">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                                <td class="text-center fw-bold text-success">{{ $product->stock }} pcs</td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="text-center py-4 text-muted small">Not found any product.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@vite(['resources/js/app.js'])
<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', () => {
        fetchInitialData();

        window.Echo.channel('poke-stream-channel')
            .listen('.stream.updated', (e) => {
                updateQueueTable(e.queue);
                updateProductTable(e.products);
            });
    });

    function fetchInitialData() {
        axios.get('/api/live-stream-data-snapshot').then(res => {
            updateQueueTable(res.data.queue);
            updateProductTable(res.data.products);
        }).catch(err => console.log("Gagal memuat snapshot awal"));
    }

    function updateQueueTable(queue) {
        const tbody = document.getElementById('realtimeQueueTbody');
        if(queue.length === 0) {
            tbody.innerHTML = `<tr><td colspan="4" class="text-center py-4 text-muted small">Antrean kosong.</td></tr>`;
            return;
        }
        tbody.innerHTML = queue.map(q => `
            <tr>
                <td class="px-3 fw-bold text-muted">${q.no}</td>
                <td><span class="fw-bold text-dark">${q.customer_name}</span></td>
                <td><span class="small text-muted">${q.items}</span></td>
                <td><span class="badge bg-light text-dark">${q.time}</span></td>
            </tr>
        `).join('');
    }

    function updateProductTable(products) {
        const tbody = document.getElementById('realtimeProductTbody');
        if(products.length === 0) {
            tbody.innerHTML = `<tr><td colspan="4" class="text-center py-4 text-muted small">Katalog produk kosong/habis.</td></tr>`;
            return;
        }
        tbody.innerHTML = products.map(p => `
            <tr>
                <td class="px-3"><span class="badge bg-soft-secondary text-secondary">${p.category}</span></td>
                <td><span class="fw-semibold text-dark">${p.name}</span></td>
                <td class="fw-bold text-primary">${p.price}</td>
                <td class="text-center fw-bold text-success">${p.stock} pcs</td>
            </tr>
        `).join('');
    }
</script>
@endsection