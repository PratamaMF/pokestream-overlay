@extends('layout.main')

@section('title', 'Order History & Reports - '  . Auth::user()->name)
@section('namepage', 'Transaction History')
@section('route', route('orders.history'))
@section('namemenu', 'View Reports')

@section('content')
<div class="row mb-4">
    <div class="col-xl-4 col-md-6 mb-3">
        <div class="card border-0 shadow-sm bg-primary text-white h-100">
            <div class="card-body">
                <div class="small text-white-50">Total Stream Revenue</div>
                <div class="h3 fw-bold mb-0">Rp {{ number_format($orders->where('status', 'done')->sum('total'), 0, ',', '.') }}</div>
                <div class="small mt-2">
                    <i class="fas fa-calendar-alt me-1"></i> Total Earnings from Done Status
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-md-6 mb-3">
        <div class="card border-0 shadow-sm bg-white h-100">
            <div class="card-body border-start border-4 border-success">
                <div class="small text-muted">Total Live Orders Queue</div>
                <div class="h3 fw-bold mb-0 text-dark">{{ $orders->where('status', 'in_queue')->count() }}</div>
                <div class="small text-success mt-2">Active Queued Orders</div>
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-md-6 mb-3">
        <div class="card border-0 shadow-sm bg-white h-100">
            <div class="card-body border-start border-4 border-warning">
                <div class="small text-muted">Queue Summary</div>
                <div class="small mt-1 text-dark">
                    In Queue: <strong class="text-warning">{{ $orders->where('status', 'in_queue')->count() }}</strong> order(s)<br>
                    Done Transaction: <strong class="text-success">{{ $orders->where('status', 'done')->count() }}</strong> order(s)<br>
                    Canceled: <strong class="text-danger">{{ $orders->where('status', 'canceled')->count() }}</strong> order(s)
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-white py-3">
        <h6 class="m-0 fw-bold text-primary"><i class="fas fa-filter me-2"></i>Date Range Filters</h6>
    </div>
    <div class="card-body">
        <form action="{{ route('orders.history') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label small fw-bold">Start Date</label>
                <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}" />
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-bold">End Date</label>
                <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}" />
            </div>
            <div class="{{ request()->filled('start_date') || request()->filled('end_date') ? 'col-md-6' : 'col-md-6' }}">
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search me-2"></i>Filter
                    </button>
                    
                    @if(request()->filled('start_date') || request()->filled('end_date'))
                        <a href="{{ route('orders.exportPdf', request()->all()) }}" target="_blank" class="btn btn-success w-100">
                            <i class="fas fa-file-pdf me-2"></i>Export Filtered PDF
                        </a>
                    @endif

                    <a href="{{ route('orders.history') }}" class="btn btn-soft-secondary w-100">
                        <i class="fas fa-undo me-2"></i>Reset
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 fw-bold text-dark"><i class="fas fa-list me-2 text-primary"></i>Details Queue Log</h6>
        
        <a href="{{ route('orders.exportPdf') }}" target="_blank" class="btn btn-soft-success btn-sm px-3 fw-bold">
            <i class="fas fa-globe me-1"></i> Export All Time PDF
        </a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table id="datatablesSimple" class="table table-hover align-middle mb-0 text-nowrap">
                <thead class="table-light">
                    <tr>
                        <th class="px-4" style="width: 80px;">No</th>
                        <th class="px-4">Date & Time</th>
                        <th>Customer</th>
                        <th>Purchased Items</th>
                        <th class="text-center">Queue Status</th>
                        <th class="text-end">Total Amount</th>
                        <th class="text-center" style="width: 150px;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $index => $order)
                    <tr>
                        <td class="px-4"><span class="fw-medium text-dark d-block">{{ $index + 1 }}</span></td>
                        <td class="px-4">
                            <span class="fw-medium text-dark d-block">{{ $order->created_at->format('d M Y, H:i') }} WIB</span>
                            <small class="text-muted text-uppercase" style="font-size: 9px;">{{ $order->created_at->diffForHumans() }}</small>
                        </td>
                        <td><span class="fw-bold text-dark">{{ $order->customer_name }}</span></td>
                        <td>
                            <span class="text-muted small text-wrap d-block" style="max-width: 300px;">
                                @foreach($order->orderDetails as $detail)
                                    • {{ $detail->product->product_name ?? 'Product Deleted' }} <strong class="text-dark">x{{ $detail->qty }}</strong><br>
                                @endforeach
                            </span>
                        </td>
                        <td class="text-center">
                            @if($order->status === 'in_queue')
                                <span class="badge bg-warning text-dark px-3 py-1.5 fw-bold"><i class="fas fa-hourglass-start me-1"></i>IN QUEUE</span>
                            @elseif($order->status === 'done')
                                <span class="badge bg-success px-3 py-1.5 fw-bold"><i class="fas fa-check-circle me-1"></i>DONE</span>
                            @else
                                <span class="badge bg-danger px-3 py-1.5 fw-bold"><i class="fas fa-times-circle me-1"></i>CANCELED</span>
                            @endif
                        </td>
                        <td class="fw-bold text-end text-primary">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                        <td class="text-center">
                            <button type="button" class="btn btn-sm btn-soft-warning px-3" data-bs-toggle="modal" data-bs-target="#editStatusModal{{ $order->id }}">
                                <i class="fas fa-edit me-1"></i> Status
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            Not found any order.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@foreach($orders as $order)
<div class="modal fade" id="editStatusModal{{ $order->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow border-top-custom">
            <div class="modal-header bg-light border-0 py-3">
                <h5 class="modal-title fw-bold text-dark"><i class="fas fa-tasks me-2 text-warning"></i>Update Queue Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('orders.updateStatus', $order->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body p-4">
                    <div class="mb-4 bg-light p-3 rounded border">
                        <small class="text-muted d-block text-uppercase fw-bold mb-1" style="font-size: 10px; letter-spacing: 0.5px;">Customer Nickname</small>
                        <span class="fw-bold text-dark h6 mb-0 d-block">{{ $order->customer_name }}</span>
                        <small class="text-muted d-block mt-2">Total Bill: <strong class="text-primary">Rp {{ number_format($order->total, 0, ',', '.') }}</strong></small>
                    </div>

                    <label class="form-label fw-600 d-block mb-3 small text-uppercase text-muted fw-bold">Select Current Status</label>
                    
                    <div class="p-0 border rounded mb-2 status-card-wrapper transition-all @if($order->status === 'in_queue') border-warning bg-soft-warning-active @endif">
                        <label class="d-flex align-items-center p-3 w-100 h-100 mb-0 style-clickable-label" for="radioQueue{{ $order->id }}">
                            <input class="form-check-input ms-0 me-3 mt-0" type="radio" name="status" 
                                   id="radioQueue{{ $order->id }}" value="in_queue" 
                                   {{ $order->status === 'in_queue' ? 'checked' : '' }}
                                   onchange="handleStatusCardChange(this, 'warning')">
                            <span class="text-dark fw-bold text-status-label">
                                <i class="fas fa-hourglass-start me-1 text-warning"></i> In Queue
                            </span>
                        </label>
                    </div>

                    <div class="p-0 border rounded mb-2 status-card-wrapper transition-all @if($order->status === 'done') border-success bg-soft-success-active @endif">
                        <label class="d-flex align-items-center p-3 w-100 h-100 mb-0 style-clickable-label" for="radioDone{{ $order->id }}">
                            <input class="form-check-input ms-0 me-3 mt-0" type="radio" name="status" 
                                   id="radioDone{{ $order->id }}" value="done" 
                                   {{ $order->status === 'done' ? 'checked' : '' }}
                                   onchange="handleStatusCardChange(this, 'success')">
                            <span class="text-dark fw-bold text-status-label">
                                <i class="fas fa-check-circle me-1 text-success"></i> Done
                            </span>
                        </label>
                    </div>

                    <div class="p-0 border rounded mb-0 status-card-wrapper transition-all @if($order->status === 'canceled') border-danger bg-soft-danger-active @endif">
                        <label class="d-flex align-items-center p-3 w-100 h-100 mb-0 style-clickable-label" for="radioCancel{{ $order->id }}">
                            <input class="form-check-input ms-0 me-3 mt-0" type="radio" name="status" 
                                   id="radioCancel{{ $order->id }}" value="canceled" 
                                   {{ $order->status === 'canceled' ? 'checked' : '' }}
                                   onchange="handleStatusCardChange(this, 'danger')">
                            <span class="text-danger fw-bold text-status-label">
                                <i class="fas fa-times-circle me-1"></i> Canceled
                            </span>
                        </label>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0 py-3">
                    <button type="button" class="btn btn-soft-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary btn-sm fw-bold px-4">Update Status</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

<script type="text/javascript">
    function handleStatusCardChange(radioInput, themeColor) {
        const modalBody = radioInput.closest('.modal-body');
        
        modalBody.querySelectorAll('.status-card-wrapper').forEach(wrapper => {
            wrapper.classList.remove(
                'border-warning', 'bg-soft-warning-active', 
                'border-success', 'bg-soft-success-active', 
                'border-danger', 'bg-soft-danger-active'
            );
        });

        const currentWrapper = radioInput.closest('.status-card-wrapper');
        currentWrapper.classList.add(`border-${themeColor}`, `bg-soft-${themeColor}-active`);
    }
</script>

<style>
    .style-clickable-label {
        cursor: pointer;
        user-select: none;
    }
    .status-card-wrapper {
        transition: all 0.15s ease-in-out;
    }
    .status-card-wrapper:hover {
        background-color: #f8f9fa;
        transform: translateY(-1px);
    }
    .bg-soft-warning-active { background-color: #fffbeb !important; }
    .bg-soft-success-active { background-color: #f0fdf4 !important; }
    .bg-soft-danger-active  { background-color: #fef2f2 !important; }
    .transition-all { transition: all 0.2s; }
</style>
@endsection