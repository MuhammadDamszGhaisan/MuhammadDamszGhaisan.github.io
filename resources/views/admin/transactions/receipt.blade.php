<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Struk {{ $order->invoice_number }}</title>
    {{-- CSS struk (dipisah ke file) : public/css/receipt.css --}}
    <link rel="stylesheet" href="{{ asset('css/receipt.css') }}">
</head>
<body>
    <div class="center">
        <h4 style="margin:0;">MAGSTORE</h4>
        <small>Smart Shopping, Easy Living.<br>Jl. Contoh No. 123, Indonesia<br>Telp: 0812-3456-7890</small>
    </div>
    <div class="line"></div>
    <table>
        <tr><td>No. Invoice</td><td class="right">{{ $order->invoice_number }}</td></tr>
        <tr><td>Tanggal</td><td class="right">{{ $order->created_at->format('d/m/Y H:i') }}</td></tr>
        <tr><td>Kasir</td><td class="right">Admin</td></tr>
        <tr><td>Pelanggan</td><td class="right">{{ $order->customer_name }}</td></tr>
    </table>
    <div class="line"></div>
    <table>
        @foreach($order->items as $item)
            <tr><td colspan="2">{{ $item->product_name }}</td></tr>
            <tr>
                <td>{{ $item->qty }} x {{ number_format($item->price, 0, ',', '.') }}</td>
                <td class="right">{{ number_format($item->subtotal, 0, ',', '.') }}</td>
            </tr>
        @endforeach
    </table>
    <div class="line"></div>
    <table>
        <tr class="total"><td>TOTAL</td><td class="right">Rp {{ number_format($order->total, 0, ',', '.') }}</td></tr>
        <tr><td>Metode</td><td class="right">{{ strtoupper($order->payment_method) }}</td></tr>
        <tr><td>Status Bayar</td><td class="right">{{ $order->payment_label }}</td></tr>
    </table>
    <div class="line"></div>
    <div class="center">
        <small>Terima kasih telah berbelanja<br>Barang yang sudah dibeli tidak dapat ditukar</small>
    </div>
    <button class="btn-print" data-print>Cetak</button>
    {{-- JS struk (dipisah ke file) : public/js/receipt.js --}}
    <script src="{{ asset('js/receipt.js') }}"></script>
</body>
</html>
