<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Produk - HyperRack</title>
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

        /* Edit Form layout */
        .edit-header-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 40px; }
        .edit-header-row h2 { font-size: 24px; font-weight: 800; }
        
        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px 40px; max-width: 900px; width: 100%; }
        .form-group { margin-bottom: 10px; }
        .form-group label { display: block; font-size: 13px; font-weight: 600; margin-bottom: 10px; color: #000; }
        .form-group input, .form-group select { width: 100%; padding: 12px 15px; border: 1px solid #000; border-radius: 8px; font-size: 14px; outline: none; background: #fff; }

        .submit-row { grid-column: 1 / span 2; display: flex; justify-content: space-between; align-items: center; margin-top: 20px; border-top: 1px solid #eee; padding-top: 20px; gap: 15px; flex-wrap: wrap; }
        .btn-batal { text-decoration: none; color: #666; font-size: 14px; font-weight: 600; }
        .btn-simpan { background: #000; color: #fff; border: none; padding: 12px 40px; border-radius: 8px; font-weight: 700; cursor: pointer; font-size: 14px; transition: 0.3s; }
        .btn-simpan:hover { background: #333; }

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

        @media (max-width: 768px) {
            .form-grid { grid-template-columns: 1fr; }
            .submit-row { grid-column: 1; flex-direction: column-reverse; align-items: center; text-align: center; }
            .btn-simpan { width: 100%; }
        }

        @media (max-width: 600px) {
            .edit-header-row h2 { font-size: 20px; }
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
            <div class="edit-header-row">
                <h2>Edit Produk</h2>
            </div>

            <form action="{{ route('admin.produk.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-grid">
                    <div class="form-group">
                        <label>Nama Produk</label>
                        <input type="text" name="nama_produk" value="{{ $product->nama_produk }}" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Harga Produk (Rp)</label>
                        <input type="number" name="harga" value="{{ $product->harga }}" required>
                    </div>

                    <div class="form-group">
                        <label>Stok Produk</label>
                        <input type="number" name="stok" value="{{ $product->stok }}" required>
                    </div>

                    <div class="form-group">
                        <label>Kategori Produk</label>
                        <select name="kategori" required>
                            <option value="Men's top" {{ $product->kategori == "Men's top" ? 'selected' : '' }}>Men's Top (Atasan)</option>
                            <option value="Male subordinates" {{ $product->kategori == "Male subordinates" ? 'selected' : '' }}>Male subordinates (Bawahan)</option>
                            <option value="Backpack" {{ $product->kategori == "Backpack" ? 'selected' : '' }}>Backpack (Tas)</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Brand</label>
                        <input type="text" name="brand" value="{{ $product->brand ?? 'HyperRack' }}">
                    </div>

                    <div class="form-group" style="grid-column: 1 / span 2;">
                        <label>Ganti Gambar Produk (Opsional)</label>
                        <div style="display: flex; gap: 20px; align-items: center;">
                            <img src="{{ asset('images/' . ($product->gambar ?? 'baju1.png')) }}" alt="Current Image" style="width: 80px; height: 80px; object-fit: cover; border-radius: 8px; border: 1px solid #ddd;">
                            <input type="file" name="gambar_file" accept="image/*">
                        </div>
                        <input type="hidden" name="gambar_lama" value="{{ $product->gambar }}">
                    </div>

                    <div class="form-group">
                        <label>Status Publikasi</label>
                        <select name="status">
                            <option value="publik" {{ $product->status == 'publik' ? 'selected' : '' }}>Publik (Tampilkan)</option>
                            <option value="draf" {{ $product->status == 'draf' ? 'selected' : '' }}>Draf (Sembunyikan)</option>
                        </select>
                    </div>

                    <div class="submit-row">
                        <a href="{{ route('admin.produk') }}" class="btn-back">Kembali ke Daftar</a>
                        <button type="submit" class="btn-simpan">Simpan Perubahan</button>
                    </div>
                </div>
            </form>
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





