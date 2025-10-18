{{-- resources/views/admin/payments/receipt.blade.php --}}
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kwitansi Pembayaran #{{ $payment->id }} - Zynera</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f8f9fa;
        }

        .receipt-container {
            max-width: 800px;
            margin: 20px auto;
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .receipt-header {
            background: linear-gradient(135deg, #16a085, #27ae60);
            color: white;
            padding: 30px;
            text-align: center;
        }

        .receipt-header h1 {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .receipt-header p {
            font-size: 16px;
            opacity: 0.9;
        }

        .receipt-body {
            padding: 30px;
        }

        .receipt-info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 30px;
        }

        .info-section h3 {
            color: #16a085;
            font-size: 18px;
            margin-bottom: 15px;
            border-bottom: 2px solid #16a085;
            padding-bottom: 5px;
        }

        .info-item {
            margin-bottom: 8px;
        }

        .info-label {
            font-weight: bold;
            color: #555;
            display: inline-block;
            width: 120px;
        }

        .info-value {
            color: #333;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin: 30px 0;
            background: #f8f9fa;
            border-radius: 8px;
            overflow: hidden;
        }

        .items-table th {
            background: #16a085;
            color: white;
            padding: 15px;
            text-align: left;
            font-weight: bold;
        }

        .items-table td {
            padding: 12px 15px;
            border-bottom: 1px solid #e9ecef;
        }

        .items-table tr:last-child td {
            border-bottom: none;
        }

        .items-table .text-right {
            text-align: right;
        }

        .total-section {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-top: 20px;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        .total-row.final {
            font-weight: bold;
            font-size: 18px;
            color: #16a085;
            border-top: 2px solid #16a085;
            padding-top: 10px;
            margin-top: 10px;
        }

        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .status-paid {
            background: #d4edda;
            color: #155724;
        }

        .method-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .method-cod {
            background: #fff3cd;
            color: #856404;
        }

        .method-transfer {
            background: #cce5ff;
            color: #004085;
        }

        .method-qris {
            background: #e2d5f7;
            color: #6f42c1;
        }

        .receipt-footer {
            background: #f8f9fa;
            padding: 20px 30px;
            border-top: 1px solid #e9ecef;
            text-align: center;
            color: #6c757d;
        }

        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 72px;
            color: rgba(22, 160, 133, 0.1);
            font-weight: bold;
            z-index: 1;
            pointer-events: none;
        }

        .print-btn {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #16a085;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            font-weight: bold;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        .print-btn:hover {
            background: #138d75;
        }

        @media print {
            body {
                background: white;
            }

            .receipt-container {
                box-shadow: none;
                margin: 0;
                max-width: none;
            }

            .print-btn {
                display: none;
            }

            .watermark {
                display: none;
            }
        }

        @media (max-width: 768px) {
            .receipt-info {
                grid-template-columns: 1fr;
                gap: 20px;
            }

            .items-table {
                font-size: 12px;
            }

            .items-table th,
            .items-table td {
                padding: 8px;
            }
        }
    </style>
</head>

<body>
    <button class="print-btn" onclick="window.print()">
        <i class="fas fa-print"></i> Cetak Kwitansi
    </button>

    <div class="receipt-container">
        <div class="watermark">LUNAS</div>

        <div class="receipt-header">
            <h1>KWITANSI PEMBAYARAN</h1>
            <p>Zynera - Platform Produk Lokal</p>
        </div>

        <div class="receipt-body">
            <div class="receipt-info">
                <div class="info-section">
                    <h3>Informasi Pembayaran</h3>
                    <div class="info-item">
                        <span class="info-label">No. Kwitansi:</span>
                        <span class="info-value">#{{ $payment->id }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">No. Order:</span>
                        <span class="info-value">#{{ $payment->order->order_number }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Tanggal:</span>
                        <span
                            class="info-value">{{ $payment->paid_at ? $payment->paid_at->format('d F Y') : $payment->created_at->format('d F Y') }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Waktu:</span>
                        <span
                            class="info-value">{{ $payment->paid_at ? $payment->paid_at->format('H:i:s') : $payment->created_at->format('H:i:s') }}
                            WIB</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Metode:</span>
                        <span class="method-badge method-{{ $payment->method }}">
                            {{ $payment->method_label }}
                        </span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Status:</span>
                        <span class="status-badge status-paid">{{ $payment->status_label }}</span>
                    </div>
                    @if ($payment->reference_number)
                        <div class="info-item">
                            <span class="info-label">Ref. Number:</span>
                            <span class="info-value">{{ $payment->reference_number }}</span>
                        </div>
                    @endif
                </div>

                <div class="info-section">
                    <h3>Informasi Pelanggan</h3>
                    <div class="info-item">
                        <span class="info-label">Nama:</span>
                        <span class="info-value">{{ $payment->order->user->name }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Email:</span>
                        <span class="info-value">{{ $payment->order->user->email }}</span>
                    </div>
                    @if ($payment->order->user->phone)
                        <div class="info-item">
                            <span class="info-label">Telepon:</span>
                            <span class="info-value">{{ $payment->order->user->phone }}</span>
                        </div>
                    @endif
                    @if ($payment->order->delivery_address)
                        <div class="info-item">
                            <span class="info-label">Alamat:</span>
                            <span class="info-value">{{ $payment->order->delivery_address }}</span>
                        </div>
                    @endif
                </div>
            </div>

            <table class="items-table">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Produk</th>
                        <th>Produsen</th>
                        <th class="text-right">Qty</th>
                        <th class="text-right">Harga Satuan</th>
                        <th class="text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($payment->order->orderItems as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <strong>{{ $item->product->name }}</strong>
                                @if ($item->product->description)
                                    <br><small
                                        style="color: #6c757d;">{{ Str::limit($item->product->description, 50) }}</small>
                                @endif
                            </td>
                            <td>{{ $item->product->user->name }}</td>
                            <td class="text-right">{{ $item->quantity }} {{ $item->product->unit }}</td>
                            <td class="text-right">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                            <td class="text-right">Rp {{ number_format($item->quantity * $item->price, 0, ',', '.') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="total-section">
                <div class="total-row">
                    <span>Subtotal:</span>
                    <span>Rp
                        {{ number_format($payment->order->orderItems->sum(function ($item) {return $item->quantity * $item->price;}),0,',','.') }}</span>
                </div>

                @if ($payment->order->delivery_fee > 0)
                    <div class="total-row">
                        <span>Ongkos Kirim:</span>
                        <span>Rp {{ number_format($payment->order->delivery_fee, 0, ',', '.') }}</span>
                    </div>
                @endif

                @if ($payment->order->admin_fee > 0)
                    <div class="total-row">
                        <span>Biaya Admin:</span>
                        <span>Rp {{ number_format($payment->order->admin_fee, 0, ',', '.') }}</span>
                    </div>
                @endif

                <div class="total-row final">
                    <span>TOTAL DIBAYAR:</span>
                    <span>Rp {{ number_format($payment->amount, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <div class="receipt-footer">
            <p><strong>Terima kasih telah berbelanja di Zynera!</strong></p>
            <p style="margin-top: 10px;">
                Kwitansi ini dicetak secara otomatis pada {{ now()->format('d F Y, H:i:s') }} WIB<br>
                Untuk pertanyaan, hubungi customer service kami di support@Zynera.click
            </p>
            <p style="margin-top: 15px; font-size: 12px; color: #adb5bd;">
                © {{ date('Y') }} Zynera. Platform e-commerce produk lokal.<br>
                Payment ID: {{ $payment->id }} |
                @if ($payment->paid_at)
                    Dibayar: {{ $payment->paid_at->format('d/m/Y H:i') }}
                @else
                    Dibuat: {{ $payment->created_at->format('d/m/Y H:i') }}
                @endif
            </p>
        </div>
    </div>

    <script>
        // Auto print when page loads (optional)
        // window.onload = function() { window.print(); }

        // Add some interactive features for web view
        document.addEventListener('DOMContentLoaded', function() {
            // Add print shortcut
            document.addEventListener('keydown', function(e) {
                if (e.ctrlKey && e.key === 'p') {
                    e.preventDefault();
                    window.print();
                }
            });
        });
    </script>
</body>

</html>

