<!DOCTYPE html>
<html>
<head>
    <title>{{ $title }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 30px; }
        .header h1 { margin: 0; font-size: 24px; }
        .header p { margin: 5px 0; color: #666; }
        
        .section-title { font-size: 18px; font-weight: bold; margin-bottom: 10px; border-bottom: 2px solid #000; padding-bottom: 5px; margin-top: 30px; }
        
        .summary-box { background: #f9f9f9; padding: 15px; border: 1px solid #ddd; border-radius: 8px; margin-bottom: 20px; }
        .summary-box table { border: none; margin-bottom: 0; }
        .summary-box td { border: none; padding: 5px 0; }
        .summary-box .label { font-weight: bold; width: 150px; }
        .summary-box .value { font-size: 16px; font-weight: bold; }

        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; font-weight: bold; }
        
        .footer { margin-top: 50px; text-align: right; }
        .footer p { margin: 5px 0; }
        
        .status { text-transform: capitalize; }
    </style>
</head>
<body>
    <div class="header">
        <h1>HYPERRACK</h1>
        <p>Laporan Bulanan: {{ $bulanText }} {{ $tahun }}</p>
        <p>Tanggal Cetak: {{ $date }}</p>
    </div>

    <div class="section-title">I. Ringkasan Keuangan</div>
    <div class="summary-box">
        <table>
            <tr>
                <td class="label">Total Pendapatan (Selesai):</td>
                <td class="value">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td class="label">Total Transaksi:</td>
                <td>{{ $penjualan->count() }} Trx</td>
            </tr>
            <tr>
                <td class="label">Bulan Laporan:</td>
                <td>{{ $bulanText }} {{ $tahun }}</td>
            </tr>
        </table>
    </div>

    <div class="section-title">II. Laporan Penjualan</div>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>ID Pesanan</th>
                <th>Pelanggan</th>
                <th>Kategori</th>
                <th>Jumlah</th>
                <th>Total</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($penjualan as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ date('d/m/Y', strtotime($item->created_at)) }}</td>
                <td>{{ $item->id_pesanan }}</td>
                <td>{{ $item->pelanggan }}</td>
                <td>{{ $item->kategori }}</td>
                <td>{{ $item->jumlah }}</td>
                <td>Rp {{ number_format($item->total_harga, 0, ',', '.') }}</td>
                <td class="status">{{ $item->status }}</td>
            </tr>
            @endforeach
            @if($penjualan->isEmpty())
            <tr>
                <td colspan="8" style="text-align: center;">Tidak ada data penjualan.</td>
            </tr>
            @endif
        </tbody>
    </table>

    <div class="section-title">III. Pemasukan Barang (Stok Produk)</div>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Nama Produk</th>
                <th>Kategori</th>
                <th>Stok</th>
                <th>Harga</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pemasukan as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ date('d/m/Y', strtotime($item->created_at)) }}</td>
                <td>{{ $item->nama_produk }}</td>
                <td>{{ $item->kategori }}</td>
                <td>{{ $item->stok }}</td>
                <td>Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
            </tr>
            @endforeach
            @if($pemasukan->isEmpty())
            <tr>
                <td colspan="6" style="text-align: center;">Tidak ada data pemasukan barang.</td>
            </tr>
            @endif
        </tbody>
    </table>

    <div class="footer">
        <p>Dicetak pada: {{ date('d F Y H:i') }}</p>
        <br><br><br>
        <p>( ____________________ )</p>
        <p>Admin/Petugas HyperRack</p>
    </div>
</body>
</html>
