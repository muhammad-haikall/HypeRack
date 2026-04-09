<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Pesanan - HyperRack</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        body { background-color: #ffffff; display: flex; min-height: 100vh; color: #000; overflow-x: hidden; }

        /* Sidebar */
        .sidebar { width: 260px; border-right: 1px solid #e0e0e0; padding: 40px 0; display: flex; flex-direction: column; position: fixed; height: 100vh; background: #fff; z-index: 1000; transition: transform 0.3s ease; }
        .logo { font-size: 32px; font-weight: 800; color: #000; text-decoration: none; margin-bottom: 10px; display: block; text-align: center; }
        .role-title { text-align: center; font-size: 18px; color: #666; margin-bottom: 30px; }
        .side-nav { list-style: none; flex-grow: 1; }
        .side-nav li { margin-bottom: 0; }
        .side-nav a { display: block; padding: 12px 30px; text-decoration: none; color: #000; font-size: 14px; font-weight: 500; transition: 0.3s; }
        .side-nav a.active { background-color: #eeeeee; font-weight: 700; }
        .side-nav a:hover { background-color: #f5f5f5; }
        
        .logout-section { padding: 0 30px; margin-top: auto; }
        .btn-logout { background: #000; color: #fff; border: none; padding: 10px 25px; border-radius: 8px; font-weight: 600; cursor: pointer; width: 100%; transition: 0.3s; }
        .btn-logout:hover { background: #333; }

        /* Main Content */
        .main-content { margin-left: 260px; flex: 1; width: calc(100% - 260px); transition: margin-left 0.3s ease; }
        .admin-header { display: flex; justify-content: space-between; align-items: center; padding: 20px 40px; border-bottom: 1px solid #e0e0e0; background: #fff; position: sticky; top: 0; z-index: 100; }
        .admin-header h3 { font-size: 20px; font-weight: 700; }
        .content-body { padding: 40px; }
        .welcome-title { font-size: 24px; font-weight: 700; margin-bottom: 30px; }

        /* Table Responsive Container */
        .table-responsive { background: #fff; overflow-x: auto; width: 100%; margin-top: 20px; }
        .history-table { width: 100%; border-collapse: collapse; min-width: 900px; }
        .history-table th { background: #E0E0E0; padding: 15px; text-align: left; font-size: 14px; font-weight: 800; color: #000; }
        .history-table td { padding: 15px; border-bottom: 1px solid #EEE; font-size: 14px; vertical-align: middle; font-weight: 600; }

        /* Status Badges */
        .badge { padding: 4px 12px; border-radius: 4px; font-size: 11px; font-weight: 800; color: #fff; text-align: center; display: inline-block; min-width: 65px; text-transform: lowercase; }
        .badge-selesai { background: #28a745; }
        .badge-diantar { background: #17a2b8; }
        .badge-dikemas { background: #dc3545; }

        .menu-toggle { display: none; font-size: 24px; cursor: pointer; margin-right: 15px; }
        .sidebar-overlay { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 900; }

        /* MEDIA QUERIES */
        @media (max-width: 1024px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.active { transform: translateX(0); }
            .main-content { margin-left: 0; width: 100%; }
            .menu-toggle { display: block; }
            .admin-header { padding: 20px; }
            .content-body { padding: 30px 20px; }
            .sidebar-overlay.active { display: block; }
        }

        @media (max-width: 600px) {
            .welcome-title { font-size: 20px; }
            .admin-header h3 { font-size: 16px; }
        }
    </style>
</head>
<body>

    <div class="sidebar-overlay" id="overlay" onclick="toggleSidebar()"></div>

    <aside class="sidebar" id="sidebar">
        <a href="{{ route('admin.dashboard') }}" class="logo">HyperRack</a>
        <p class="role-title">{{ session('user_role') == 'petugas' ? 'Petugas' : 'Admin' }}</p>
        <ul class="side-nav">
            <li><a href="{{ route('admin.dashboard') }}" class="{{ Request::is('dashboard') ? 'active' : '' }}">Dashboard</a></li>
            <li><a href="{{ route('admin.produk') }}" class="{{ Request::is('dashboard/produk*') ? 'active' : '' }}">Kelola Produk</a></li>
            <li><a href="{{ route('admin.kelolaorder') }}" class="{{ Request::is('dashboard/order*') ? 'active' : '' }}">Kelola Order</a></li>
            <li><a href="{{ route('admin.riwayat') }}" class="{{ Request::is('dashboard/riwayat*') ? 'active' : '' }}">Riwayat Pesanan</a></li>
            <li><a href="{{ route('admin.laporan') }}" class="{{ Request::is('dashboard/laporan*') ? 'active' : '' }}">Laporan</a></li>
            @if(session('user_role') == 'admin')
            <li><a href="{{ route('admin.petugas') }}" class="{{ Request::is('dashboard/petugas*') ? 'active' : '' }}">Kelola Petugas</a></li>
            <li><a href="{{ route('admin.pengguna') }}" class="{{ Request::is('dashboard/pengguna*') ? 'active' : '' }}">Kelola Pengguna</a></li>
            @endif
        </ul>
        <div class="logout-section">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn-logout">Logout</button>
            </form>
        </div>
    </aside>

    <main class="main-content">
        <header class="admin-header">
            <div style="display: flex; align-items: center;">
                <div class="menu-toggle" onclick="toggleSidebar()">
                    <i class="fa fa-bars"></i>
                </div>
                <h3>Dashboard {{ session('user_role') == 'petugas' ? 'Petugas' : 'Admin' }}</h3>
            </div>
            <div class="user-icon">
                <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                    <circle cx="12" cy="7" r="4"></circle>
                </svg>
            </div>
        </header>

        <section class="content-body">
            <h2 class="welcome-title">Selamat Datang {{ session('user_role') == 'petugas' ? 'Petugas' : 'Admin' }}</h2>

            <div class="table-responsive">
                <table class="history-table">
                    <thead>
                        <tr>
                            <th>ID Pesanan</th>
                            <th>Nama</th>
                            <th>Kategori</th>
                            <th>Tanggal Dibuat</th>
                            <th>Pembayaran</th>
                            <th style="text-align: center;">Jumlah Pesanan</th>
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
                            <td style="text-align: center;">{{ $order->jumlah }}</td>
                            <td>
                                @php
                                    $badgeClass = match($order->status) {
                                        'selesai' => 'badge-selesai',
                                        'diantar' => 'badge-diantar',
                                        default => 'badge-dikemas',
                                    };
                                @endphp
                                <span class="badge {{ $badgeClass }}">{{ $order->status }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" style="text-align: center; color: #999;">Belum ada riwayat pesanan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </main>

    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('active');
            document.getElementById('overlay').classList.toggle('active');
        }
    </script>

</body>
</html>
