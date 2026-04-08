<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Petugas - HyperRack</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        body { background-color: #ffffff; display: flex; min-height: 100vh; color: #000; overflow-x: hidden; }

        /* Sidebar */
        .sidebar { width: 260px; border-right: 1px solid #e0e0e0; padding: 40px 0; display: flex; flex-direction: column; position: fixed; height: 100vh; background: #fff; z-index: 1000; transition: transform 0.3s ease; }
        .logo { font-size: 32px; font-weight: 800; color: #000; text-decoration: none; text-align: center; display: block; margin-bottom: 10px; }
        .role-title { text-align: center; font-size: 18px; color: #666; margin-bottom: 30px; }
        .side-nav { list-style: none; flex-grow: 1; }
        .side-nav li { margin-bottom: 0; }
        .side-nav a { display: block; padding: 12px 30px; text-decoration: none; color: #000; font-size: 14px; font-weight: 500; transition: 0.3s; }
        .side-nav a:hover { background-color: #f5f5f5; }
        .side-nav a.active { background-color: #eeeeee; font-weight: 700; }
        
        .logout-section { padding: 0 30px; margin-top: auto; }
        .btn-logout { background: #000; color: #fff; border: none; padding: 10px 25px; border-radius: 8px; font-weight: 600; cursor: pointer; width: 100%; transition: 0.3s; }
        .btn-logout:hover { background: #333; }

        /* Main Content */
        .main-content { margin-left: 260px; flex: 1; width: calc(100% - 260px); transition: margin-left 0.3s ease; }
        .admin-header { display: flex; justify-content: space-between; align-items: center; padding: 20px 40px; border-bottom: 1px solid #e0e0e0; background: #fff; position: sticky; top: 0; z-index: 100; }
        .admin-header h3 { font-size: 20px; font-weight: 700; }
        .content-body { padding: 40px; }
        
        .welcome-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        .welcome-row h2 { font-size: 24px; font-weight: 700; }
        .btn-add { background: #000; color: #fff; border: none; padding: 10px 20px; border-radius: 8px; font-weight: 600; text-decoration: none; font-size: 14px; transition: 0.3s; }
        .btn-add:hover { background: #333; }

        /* Table Desktop */
        .table-container { background: #fff; overflow-x: auto; width: 100%; }
        .petugas-table { width: 100%; border-collapse: collapse; margin-top: 20px; min-width: 600px; }
        .petugas-table th { background: #E0E0E0; padding: 12px; text-align: left; font-size: 13px; font-weight: 800; color: #000; }
        .petugas-table td { padding: 15px 12px; border-bottom: 1px solid #EEE; font-size: 14px; vertical-align: middle; font-weight: 600; }
        
        .badge-online { color: #28a745; font-weight: 800; font-size: 13px; text-transform: uppercase; display: inline-flex; align-items: center; }
        .badge-offline { color: #888; font-weight: 800; font-size: 13px; text-transform: uppercase; display: inline-flex; align-items: center; }
        .btn-edit { color: #000; margin-right: 15px; text-decoration: none; font-size: 16px; }
        .btn-delete { color: #ff4d4d; background: none; border: none; cursor: pointer; font-size: 16px; }

        .user-icon { display: flex; align-items: center; }
        .menu-toggle { display: none; font-size: 24px; cursor: pointer; margin-right: 15px; }

        /* Overlay */
        .sidebar-overlay { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 900; }

        /* MEDIA QUERIES (RESPONSIVE) */
        @media (max-width: 1024px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.active { transform: translateX(0); }
            .main-content { margin-left: 0; width: 100%; }
            .menu-toggle { display: block; }
            .content-body { padding: 30px 20px; }
            .sidebar-overlay.active { display: block; }
        }

        @media (max-width: 600px) {
            .welcome-row { flex-direction: column; align-items: flex-start; gap: 15px; }
            .welcome-row h2 { font-size: 20px; }
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
            <div class="welcome-row">
                <h2>Selamat Datang {{ session('user_role') == 'petugas' ? 'Petugas' : 'Admin' }}</h2>
                <a href="{{ route('admin.petugas.tambah') }}" class="btn-add">+ Tambah Petugas</a>
            </div>

            <div class="table-container">
                <table class="petugas-table">
                    <thead>
                        <tr>
                            <th width="150">No Petugas</th>
                            <th>Nama Petugas</th>
                            <th>Status Petugas</th>
                            <th width="150">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($petugas ?? [] as $index => $p)
                        <tr>
                            <td>{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}-{{ $p->id }}</td>
                            <td>{{ $p->nama }}</td>
                            <td>
                                @php
                                    $isOnline = false;
                                    if ($p->updated_at) {
                                        $lastSeen = \Carbon\Carbon::parse($p->updated_at);
                                        // Dibuat sangat lama (24 jam) agar status hanya Offline jika beneran Logout
                                        $isOnline = $lastSeen->diffInHours(\Carbon\Carbon::now('Asia/Jakarta')) < 24;
                                    }
                                @endphp
                                @if($isOnline)
                                    <span class="badge-online"><i class="fa fa-circle" style="font-size: 8px; margin-right: 5px;"></i>online</span>
                                @else
                                    <span class="badge-offline"><i class="fa fa-circle" style="font-size: 8px; margin-right: 5px;"></i>offline</span>
                                @endif
                                
                                @if($p->role == 'admin')
                                    <span style="font-size: 10px; color: #666; display: block; margin-top: 4px;">(Administrator)</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.petugas.edit', $p->id) }}" class="btn-edit"><i class="fa fa-edit"></i></a>
                                
                                {{-- Cegah admin menghapus dirinya sendiri atau admin lain --}}
                                @if($p->role !== 'admin')
                                    <form action="{{ route('admin.petugas.delete', $p->id) }}" method="POST" style="display:inline;">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn-delete" onclick="return confirm('Hapus petugas ini?')"><i class="fa fa-trash"></i></button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" style="text-align: center; color: #999;">Belum ada data petugas.</td>
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




