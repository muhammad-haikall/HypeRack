<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Produk - HyperRack</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800;900&display=swap" rel="stylesheet">
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
        .welcome-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; flex-wrap: wrap; gap: 20px; }
        
        /* Tombol Aksi */
        .btn-add { background: #000; color: #fff; border: none; padding: 10px 20px; border-radius: 8px; font-weight: 600; text-decoration: none; cursor: pointer; transition: 0.3s; display: inline-block; font-size: 14px; }
        .btn-add:hover { background: #333; }
        
        .btn-bulk-delete { background: #fff; color: #000; border: 1px solid #000; padding: 9px 12px; border-radius: 8px; cursor: pointer; transition: 0.3s; font-size: 16px; }
        .btn-bulk-delete:hover { background: #ff4d4d; color: #fff; border-color: #ff4d4d; }

        /* Table Responsive Container */
        /* Table Container - Gunakan overflow-x: initial agar dropdown tidak terpotong */
        .table-responsive { background: #fff; overflow: visible; width: 100%; margin-top: 20px; }
        .product-table { width: 100%; border-collapse: collapse; min-width: 900px; }
        .product-table th { background: #E0E0E0; padding: 12px 15px; text-align: left; font-size: 13px; color: #333; }
        .product-table td { padding: 15px; border-bottom: 1px solid #EEE; font-size: 14px; vertical-align: middle; }
        
        /* Checkbox Styling */
        .check-container { display: block; position: relative; cursor: pointer; height: 18px; width: 18px; }
        .check-container input { position: absolute; opacity: 0; cursor: pointer; }
        .checkmark { position: absolute; top: 0; left: 0; height: 18px; width: 18px; border: 1px solid #000; border-radius: 2px; }
        .check-container input:checked ~ .checkmark { background-color: #000; }
        .checkmark:after { content: ""; position: absolute; display: none; left: 5px; top: 1px; width: 5px; height: 10px; border: solid white; border-width: 0 2px 2px 0; transform: rotate(45deg); }
        .check-container input:checked ~ .checkmark:after { display: block; }

        .status-badge { padding: 4px 12px; border-radius: 6px; font-size: 12px; font-weight: 700; color: #fff; display: inline-block; min-width: 70px; text-align: center; }
        .status-publik { background: #28a745; }
        .status-draf { background: #dc3545; }

        /* Dropdown */
        .dropdown { position: relative; display: inline-block; }
        .dropbtn { border: none; background: none; font-weight: 800; cursor: pointer; font-size: 18px; padding: 5px 10px; }
        .dropdown-content { display: none; position: absolute; right: 0; background-color: #fff; min-width: 140px; box-shadow: 0px 8px 24px rgba(0,0,0,0.15); z-index: 9999; border-radius: 8px; overflow: visible; border: 1px solid #eee; margin-top: 5px; }
        .dropdown:hover .dropdown-content { display: block; }
        .dropdown-content a, .dropdown-content button { color: black; padding: 12px 15px; text-decoration: none; display: block; font-size: 13px; border: none; background: none; width: 100%; text-align: left; cursor: pointer; transition: 0.2s; }
        .dropdown-content a:hover, .dropdown-content button:hover { background-color: #f1f1f1; }

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
                <h3>Kelola Produk</h3>
            </div>
            <div class="user-icon">
                <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                    <circle cx="12" cy="7" r="4"></circle>
                </svg>
            </div>
        </header>

        <section class="content-body">
            @if(session('success'))
                <div class="alert-success">
                    <i class="fa fa-check-circle"></i> {{ session('success') }}
                </div>
            @endif

            <div class="welcome-row">
                <h2>Selamat Datang {{ session('user_role') == 'petugas' ? 'Petugas' : 'Admin' }}</h2>
                <div style="display: flex; gap: 10px; align-items: center;">
                    @if(request('all') == 1)
                    <form action="{{ route('admin.produk.delete-masal') }}" method="POST" onsubmit="return confirm('Hapus semua produk terpilih?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-bulk-delete" title="Hapus Masal"><i class="fa fa-trash-can"></i></button>
                    </form>
                    @endif
                    <a href="{{ route('admin.produk.tambah') }}" class="btn-add">+ Tambah Produk</a>
                </div>
            </div>

            <div class="table-responsive">
                <table class="product-table">
                    <thead>
                        <tr>
                            <th width="40">
                                @php $isAllSelected = request('all') == 1; @endphp
                                <a href="{{ route('admin.produk', ['all' => $isAllSelected ? 0 : 1]) }}">
                                    <label class="check-container" style="pointer-events: none;">
                                        <input type="checkbox" {{ $isAllSelected ? 'checked' : '' }}>
                                        <span class="checkmark"></span>
                                    </label>
                                </a>
                            </th>
                            <th>Nama Produk</th>
                            <th>Harga</th>
                            <th>Stok</th>
                            <th>Dibuat</th>
                            <th>Kategori</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products ?? [] as $product)
                        <tr>
                            <td>
                                <label class="check-container">
                                    <input type="checkbox" name="ids[]" value="{{ $product->id }}" {{ $isAllSelected ? 'checked' : '' }}>
                                    <span class="checkmark"></span>
                                </label>
                            </td>
                            <td style="font-weight: 600;">{{ $product->nama_produk }}</td>
                            <td>Rp. {{ number_format($product->harga, 0, ',', '.') }}</td>
                            <td>{{ $product->stok }}</td>
                            <td>{{ date('d M Y', strtotime($product->created_at)) }}</td>
                            <td>{{ $product->kategori }}</td>
                            <td>
                                <span class="status-badge {{ $product->status == 'publik' ? 'status-publik' : 'status-draf' }}">
                                    {{ ucfirst($product->status) }}
                                </span>
                            </td>
                            <td style="position: relative;">
                                <div class="dropdown">
                                    <button class="dropbtn">...</button>
                                    <div class="dropdown-content">
                                        <a href="{{ route('admin.produk.edit', $product->id) }}"><i class="fa fa-edit"></i> Edit</a>
                                        <form action="{{ route('admin.produk.delete', $product->id) }}" method="POST" onsubmit="return confirm('Hapus?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" style="color: red;"><i class="fa fa-trash"></i> Hapus</button>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" style="text-align: center; color: #999;">Data produk kosong.</td>
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
