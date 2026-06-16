<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Report Order - {{ Auth::user()->name }}</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #333;
            font-size: 11px;
            line-height: 1.4;
            margin: 0;
            padding: 0;
        }
        .header {
            text-align: center;
            margin-bottom: 25px;
            border-bottom: 2px solid #001f3f;
            padding-bottom: 10px;
        }
        .header h2 {
            margin: 0 0 5px 0;
            color: #001f3f;
            font-size: 20px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .header p {
            margin: 0 0 5px 0;
            color: #666;
            font-size: 12px;
        }
        .header .period-badge {
            display: inline-block;
            background-color: #f1f5f9;
            color: #0f172a;
            padding: 4px 10px;
            font-weight: bold;
            border-radius: 4px;
            font-size: 11px;
            margin-top: 5px;
            border: 1px solid #cbd5e1;
        }
        .meta-info {
            width: 100%;
            margin-bottom: 15px;
            font-size: 10px;
            color: #555;
        }
        .table-report {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .table-report th {
            background-color: #001f3f;
            color: #ffffff;
            text-align: left;
            padding: 8px 6px;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 10px;
            border: 1px solid #001f3f;
        }
        .table-report td {
            padding: 8px 6px;
            border-bottom: 1px solid #e2e8f0;
            border-left: 1px solid #e2e8f0;
            border-right: 1px solid #e2e8f0;
            vertical-align: top;
        }
        .table-report tr:nth-child(even) td {
            background-color: #f8fafc;
        }
        .badge {
            display: inline-block;
            padding: 2px 6px;
            font-weight: bold;
            border-radius: 3px;
            font-size: 9px;
            text-align: center;
        }
        .badge-queue { background-color: #fef3c7; color: #d97706; }
        .badge-done { background-color: #dcfce7; color: #15803d; }
        .badge-cancel { background-color: #fee2e2; color: #b91c1c; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .fw-bold { font-weight: bold; }
        .summary-box {
            float: right;
            width: 250px;
            margin-top: 10px;
            border: 1px solid #cbd5e1;
            background-color: #f8fafc;
            border-radius: 4px;
        }
        .summary-row {
            padding: 6px 10px;
            border-bottom: 1px solid #cbd5e1;
        }
        .summary-row:last-child {
            border-bottom: none;
            background-color: #001f3f;
            color: #fff;
        }
        .summary-row span {
            float: right;
        }
    </style>
</head>
<body>

    <div class="header">
        <h2>{{ Auth::user()->name }}</h2>
        <p>Report Order</p>
        <div class="period-badge">{{ $dateInfo }}</div>
    </div>

    <table class="meta-info">
        <tr>
            <td><strong>Printed Date:</strong> {{ $printedAt }}</td>
            <td style="text-align: right;"><strong>Total Orders Found:</strong> {{ $orders->count() }} Record(s)</td>
        </tr>
    </table>

    <table class="table-report">
        <thead>
            <tr>
                <th style="width: 30px;" class="text-center">No</th>
                <th style="width: 100px;">Date & Time</th>
                <th style="width: 120px;">Customer Name</th>
                <th>Purchased Items Detail</th>
                <th style="width: 75px;" class="text-center">Status</th>
                <th style="width: 90px;" class="text-right">Total Bill</th>
            </tr>
        </thead>
        <tbody>
            @php $grandTotalRevenue = 0; @endphp
            @forelse($orders as $index => $order)
                @if($order->status === 'done')
                    @php $grandTotalRevenue += $order->total; @endphp
                @endif
            <tr>
                <td class="text-center fw-bold" style="color: #666;">{{ $index + 1 }}</td>
                <td>{{ $order->created_at->format('d M Y, H:i') }} WIB</td>
                <td class="fw-bold" style="color: #0f172a;">{{ $order->customer_name }}</td>
                <td>
                    @foreach($order->orderDetails as $detail)
                        • {{ $detail->product->product_name ?? 'Product Deleted' }} 
                        <span style="color: #666;">(x{{ $detail->qty }})</span><br>
                    @endforeach
                </td>
                <td>
                    @if($order->status === 'in_queue')
                        IN QUEUE
                    @elseif($order->status === 'done')
                        DONE
                    @else
                        CANCELED
                    @endif
                </td>
                <td class="fw-bold" style="color: #001f3f;">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center" style="padding: 30px 0; color: #999;">No transaction data match with current filters.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="summary-box">
        <div class="summary-row">
            In Queue Orders: <span>{{ $orders->where('status', 'in_queue')->count() }}</span>
        </div>
        <div class="summary-row">
            Canceled Orders: <span>{{ $orders->where('status', 'canceled')->count() }}</span>
        </div>
        <div class="summary-row">
            Successful Orders: <span>{{ $orders->where('status', 'done')->count() }}</span>
        </div>
        <div class="summary-row fw-bold">
            Total Revenue: <span>Rp {{ number_format($grandTotalRevenue, 0, ',', '.') }}</span>
        </div>
    </div>

</body>
</html>