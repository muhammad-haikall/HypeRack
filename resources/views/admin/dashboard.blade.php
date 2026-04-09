<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HyperRack - Dashboard {{ session('user_role') == 'petugas' ? 'Petugas' : 'Admin' }}</title>
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
        .welcome-text { font-size: 24px; font-weight: 700; margin-bottom: 30px; }

        /* Stats Cards */
        .stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 40px; }
        .stat-card { border: 1px solid #000; border-radius: 12px; padding: 20px; text-align: center; }
        .stat-card h4 { font-size: 11px; font-weight: 600; color: #333; margin-bottom: 15px; }
        .stat-card p { font-size: 16px; font-weight: 800; }

        /* Table */
        .table-responsive { background: #fff; overflow-x: auto; width: 100%; }
        .section-title { font-size: 16px; font-weight: 700; margin-bottom: 15px; }
        table { width: 100%; border-collapse: collapse; font-size: 12px; min-width: 800px; }
        th { background: #f2f2f2; padding: 12px; text-align: left; font-weight: 600; }
        td { padding: 12px; border-bottom: 1px solid #eeeeee; }

        .status { padding: 4px 12px; border-radius: 4px; color: #fff; font-size: 10px; font-weight: 700; display: inline-block; text-transform: capitalize; }
        .status.selesai { background-color: #4CAF50; }
        .status.diantar { background-color: #17a2b8; }
        .status.dikemas { background-color: #dc3545; }
        .status.proses { background-color: #00BCD4; }
        .status.dikirim { background-color: #f44336; }

        .menu-toggle { display: none; font-size: 24px; cursor: pointer; margin-right: 15px; }
        .sidebar-overlay { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 900; }

        /* MEDIA QUERIES */
        @media (max-width: 1024px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.active { transform: translateX(0); }
            .main-content { margin-left: 0; width: 100%; }
            .menu-toggle { display: block; }
            .top-bar { padding: 20px; }
            .content-body { padding: 30px 20px; }
            .sidebar-overlay.active { display: block; }
            .stats-grid { grid-template-columns: repeat(2, 1fr); }
        }

        @media (max-width: 600px) {
            .welcome-text { font-size: 20px; }
            .stats-grid { grid-template-columns: 1fr; }
            .top-bar h2 { font-size: 16px; }
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
            <a href="{{ route('admin.dashboard') }}" class="nav-item {{ Request::is('dashboard') ? 'active' : '' }}">Dashboard</a>
            <a href="{{ route('admin.produk') }}" class="nav-item {{ Request::is('dashboard/produk*') ? 'active' : '' }}">Kelola Produk</a>
            <a href="{{ route('admin.kelolaorder') }}" class="nav-item {{ Request::is('dashboard/order*') ? 'active' : '' }}">Kelola Order</a>
            <a href="{{ route('admin.riwayat') }}" class="nav-item {{ Request::is('dashboard/riwayat*') ? 'active' : '' }}">Riwayat Pesanan</a>
            <a href="{{ route('admin.laporan') }}" class="nav-item {{ Request::is('dashboard/laporan*') ? 'active' : '' }}">Laporan</a>
            @if(session('user_role') == 'admin')
            <a href="{{ route('admin.petugas') }}" class="nav-item {{ Request::is('dashboard/petugas*') ? 'active' : '' }}">Kelola Petugas</a>
            <a href="{{ route('admin.pengguna') }}" class="nav-item {{ Request::is('dashboard/pengguna*') ? 'active' : '' }}">Kelola Pengguna</a>
            @endif
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
                <h2>Dashboard {{ session('user_role') == 'petugas' ? 'Petugas' : 'Admin' }}</h2>
            </div>
            <div class="user-icon">
                <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                    <circle cx="12" cy="7" r="4"></circle>
                </svg>
            </div>
        </header>

        <div class="content-body">
            <h1 class="welcome-text">Selamat Datang, {{ session('user_nama') ?? 'Admin' }}</h1>

            <div class="stats-grid">
                <div class="stat-card">
                    <h4>Total Pendapatan</h4>
                    <p>Rp {{ number_format($totalPendapatan ?? 0, 0, ',', '.') }}</p>
                </div>
                <div class="stat-card">
                    <h4>Total Order</h4>
                    <p>{{ $totalOrder ?? 0 }}</p>
                </div>
                <div class="stat-card">
                    <h4>Total Produk</h4>
                    <p>{{ $totalProduk ?? 0 }}</p>
                </div>
                <div class="stat-card">
                    <h4>Petugas Online</h4>
                    <p>1</p>
                </div>
            </div>

            <h3 class="section-title">Transaksi Terbaru</h3>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Id Pesanan</th>
                            <th>Pelanggan</th>
                            <th>Kategori</th>
                            <th>Tanggal Dibuat</th>
                            <th>Pembayaran</th>
                            <th>Jumlah</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders ?? [] as $order)
                        <tr>
                            <td>{{ $order->id_pesanan }}</td>
                            <td>{{ $order->pelanggan }}</td>
                            <td>{{ $order->kategori }}</td>
                            <td>{{ date('d M Y', strtotime($order->created_at)) }}</td>
                            <td>{{ $order->pembayaran }}</td>
                            <td>{{ $order->jumlah }}</td>
                            <td>
                                <span class="status {{ $order->status }}">{{ $order->status }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 30px; color: #999;">
                                Belum ada transaksi.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('active');
            document.getElementById('overlay').classList.toggle('active');
        }
    </script>

</body>
</html>




