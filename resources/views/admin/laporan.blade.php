<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HyperRack - Laporan {{ session('user_role') == 'petugas' ? 'Petugas' : 'Admin' }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        body { background-color: #ffffff; display: flex; min-height: 100vh; color: #000; overflow-x: hidden; }

        /* Sidebar */
        .sidebar { width: 260px; border-right: 1px solid #e0e0e0; padding: 40px 0; display: flex; flex-direction: column; position: fixed; height: 100vh; background: #fff; z-index: 1000; transition: transform 0.3s ease; }
        .sidebar-brand { text-align: center; margin-bottom: 10px; }
        .sidebar-brand h1 { font-size: 32px; font-weight: 800; }
        .sidebar-brand p { font-size: 18px; color: #666; margin-bottom: 30px; }

        .nav-menu { flex-grow: 1; }
        .nav-item { display: block; padding: 12px 30px; color: #000; text-decoration: none; font-size: 14px; font-weight: 500; transition: 0.3s; }
        .nav-item.active { background-color: #eeeeee; font-weight: 700; }
        .nav-item:hover { background-color: #f5f5f5; }

        .logout-section { padding: 0 30px; }
        .btn-logout { background: #000; color: #fff; border: none; padding: 10px 25px; border-radius: 8px; font-weight: 600; cursor: pointer; width: 100%; transition: 0.3s; }
        .btn-logout:hover { background: #333; }

        /* Main Content */
        .main-content { margin-left: 260px; flex: 1; width: calc(100% - 260px); transition: margin-left 0.3s ease; }
        .top-bar { padding: 20px 40px; border-bottom: 1px solid #e0e0e0; display: flex; justify-content: space-between; align-items: center; background: #fff; position: sticky; top: 0; z-index: 100; }
        .top-bar h2 { font-size: 20px; font-weight: 700; }

        .content-body { padding: 40px; }
        .section-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; flex-wrap: wrap; gap: 20px; }
        .welcome-text { font-size: 24px; font-weight: 700; }

        .btn-download { background: #000; color: #fff; border: none; padding: 10px 20px; border-radius: 8px; font-size: 14px; font-weight: 600; cursor: pointer; text-decoration: none; transition: 0.3s; display: inline-flex; align-items: center; gap: 8px; }
        .btn-download:hover { background: #333; transform: translateY(-2px); }

        /* Filter */
        .filter-section { background: #f9f9f9; padding: 20px; border-radius: 12px; margin-bottom: 30px; display: flex; gap: 15px; align-items: flex-end; border: 1px solid #eee; }
        .filter-group { display: flex; flex-direction: column; gap: 5px; }
        .filter-group label { font-size: 12px; font-weight: 600; color: #666; }
        .filter-group select { padding: 8px 15px; border-radius: 6px; border: 1px solid #ddd; font-size: 14px; outline: none; }
        .btn-filter { background: #000; color: #fff; border: none; padding: 9px 20px; border-radius: 6px; font-weight: 600; cursor: pointer; }

        /* Stats Card */
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px; }
        .stat-card { background: #fff; border: 1px solid #000; border-radius: 12px; padding: 20px; }
        .stat-card h4 { font-size: 12px; color: #666; margin-bottom: 10px; text-transform: uppercase; letter-spacing: 1px; }
        .stat-card p { font-size: 20px; font-weight: 800; }

        /* Tabs */
        .tabs { display: flex; gap: 20px; margin-bottom: 30px; border-bottom: 1px solid #e0e0e0; }
        .tab { padding: 10px 0; font-size: 16px; font-weight: 600; color: #999; cursor: pointer; position: relative; }
        .tab.active { color: #000; }
        .tab.active::after { content: ''; position: absolute; bottom: -1px; left: 0; width: 100%; height: 2px; background: #000; }

        /* Table */
        .report-section { display: none; }
        .report-section.active { display: block; }
        .table-responsive { background: #fff; overflow-x: auto; width: 100%; border: 1px solid #e0e0e0; border-radius: 12px; }
        table { width: 100%; border-collapse: collapse; font-size: 12px; min-width: 800px; }
        th { background: #f9f9f9; padding: 15px; text-align: left; font-weight: 600; border-bottom: 1px solid #e0e0e0; }
        td { padding: 15px; border-bottom: 1px solid #eeeeee; }

        .status { padding: 4px 12px; border-radius: 4px; color: #fff; font-size: 10px; font-weight: 700; display: inline-block; text-transform: capitalize; }
        .status.selesai { background-color: #4CAF50; }
        .status.diantar { background-color: #17a2b8; }
        .status.dikemas { background-color: #dc3545; }

        .menu-toggle { display: none; font-size: 24px; cursor: pointer; margin-right: 15px; }
        .sidebar-overlay { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 900; }

        @media (max-width: 1024px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.active { transform: translateX(0); }
            .main-content { margin-left: 0; width: 100%; }
            .menu-toggle { display: block; }
            .top-bar { padding: 20px; }
            .content-body { padding: 30px 20px; }
            .sidebar-overlay.active { display: block; }
            .filter-section { flex-direction: column; align-items: stretch; }
        }
    </style>
</head>
<body>

    <div class="sidebar-overlay" id="overlay" onclick="toggleSidebar()"></div>

    <aside class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <h1>HyperRack</h1>
            <p>{{ session('user_role') == 'petugas' ? 'Petugas' : 'Admin' }}</p>
        </div>
        <div class="nav-menu">
            <a href="{{ route('admin.dashboard') }}" class="nav-item">Dashboard</a>
            <a href="{{ route('admin.produk') }}" class="nav-item">Kelola Produk</a>
            <a href="{{ route('admin.kelolaorder') }}" class="nav-item">Kelola Order</a>
            <a href="{{ route('admin.riwayat') }}" class="nav-item">Riwayat Pesanan</a>
            @if(session('user_role') == 'admin')
            <a href="{{ route('admin.petugas') }}" class="nav-item">Kelola Petugas</a>
            <a href="{{ route('admin.pengguna') }}" class="nav-item">Kelola Pengguna</a>
            @endif
            <a href="{{ route('admin.laporan') }}" class="nav-item active">Laporan</a>
        </div>
        
        <div class="logout-section">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn-logout">Logout</button>
            </form>
        </div>
    </aside>

    <main class="main-content">
        <header class="top-bar">
            <div style="display: flex; align-items: center;">
                <div class="menu-toggle" onclick="toggleSidebar()">
                    <i class="fa fa-bars"></i>
                </div>
                <h2>Laporan Bulanan</h2>
            </div>
            <div class="user-icon">
                <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                    <circle cx="12" cy="7" r="4"></circle>
                </svg>
            </div>
        </header>

        <div class="content-body">
            <div class="section-header">
                <h1 class="welcome-text">Ringkasan Laporan</h1>
                <a href="{{ route('admin.laporan.download', ['bulan' => $bulan, 'tahun' => $tahun]) }}" class="btn-download">
                    <i class="fas fa-file-pdf"></i> Download PDF
                </a>
            </div>

            <form action="{{ route('admin.laporan') }}" method="GET" class="filter-section">
                <div class="filter-group">
                    <label>Pilih Bulan</label>
                    <select name="bulan">
                        @for($m=1; $m<=12; $m++)
                            <option value="{{ sprintf('%02d', $m) }}" {{ $bulan == sprintf('%02d', $m) ? 'selected' : '' }}>
                                {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                            </option>
                        @endfor
                    </select>
                </div>
                <div class="filter-group">
                    <label>Pilih Tahun</label>
                    <select name="tahun">
                        @for($y=date('Y'); $y>=2024; $y--)
                            <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                </div>
                <button type="submit" class="btn-filter">Tampilkan</button>
            </form>

            <div class="stats-grid">
                <div class="stat-card">
                    <h4>Total Pendapatan (Selesai)</h4>
                    <p>Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</p>
                </div>
                <div class="stat-card">
                    <h4>Total Pesanan</h4>
                    <p>{{ $totalPesanan }} Trx</p>
                </div>
                <div class="stat-card">
                    <h4>Barang Masuk</h4>
                    <p>{{ $pemasukan->count() }} Jenis</p>
                </div>
            </div>

            <div class="tabs">
                <div class="tab active" onclick="switchTab('penjualan')">Laporan Penjualan</div>
                <div class="tab" onclick="switchTab('pemasukan')">Pemasukan Barang</div>
            </div>

            <div id="penjualan" class="report-section active">
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>ID Pesanan</th>
                                <th>Pelanggan</th>
                                <th>Kategori</th>
                                <th>Jumlah</th>
                                <th>Total Harga</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($penjualan as $item)
                            <tr>
                                <td>{{ date('d M Y', strtotime($item->created_at)) }}</td>
                                <td>{{ $item->id_pesanan }}</td>
                                <td>{{ $item->pelanggan }}</td>
                                <td>{{ $item->kategori }}</td>
                                <td>{{ $item->jumlah }}</td>
                                <td>Rp {{ number_format($item->total_harga, 0, ',', '.') }}</td>
                                <td>
                                    <span class="status {{ $item->status }}">{{ $item->status }}</span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" style="text-align: center; padding: 30px;">Belum ada data penjualan di bulan ini.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div id="pemasukan" class="report-section">
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Nama Produk</th>
                                <th>Kategori</th>
                                <th>Stok Tersedia</th>
                                <th>Harga</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pemasukan as $item)
                            <tr>
                                <td>{{ date('d M Y', strtotime($item->created_at)) }}</td>
                                <td>{{ $item->nama_produk }}</td>
                                <td>{{ $item->kategori }}</td>
                                <td>{{ $item->stok }}</td>
                                <td>Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" style="text-align: center; padding: 30px;">Belum ada data pemasukan di bulan ini.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('active');
            document.getElementById('overlay').classList.toggle('active');
        }

        function switchTab(tabId) {
            document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
            document.querySelectorAll('.report-section').forEach(s => s.classList.remove('active'));
            
            event.target.classList.add('active');
            document.getElementById(tabId).classList.add('active');
        }
    </script>

</body>
</html>
