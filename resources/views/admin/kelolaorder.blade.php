<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Order - HyperRack</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
        .welcome-title { font-size: 24px; font-weight: 700; margin-bottom: 30px; }

        /* Table Responsive Container */
        .table-responsive { background: #fff; overflow-x: auto; width: 100%; margin-top: 20px; }
        .order-table { width: 100%; border-collapse: collapse; min-width: 900px; }
        .order-table th { background: #E0E0E0; padding: 15px; text-align: left; font-size: 14px; font-weight: 800; color: #000; }
        .order-table td { padding: 15px; border-bottom: 1px solid #EEE; font-size: 14px; vertical-align: middle; font-weight: 600; }
        
        /* Status Select Dropdown */
        .status-select { padding: 8px 16px; border: none; border-radius: 6px; color: #fff; font-size: 13px; font-weight: 700; cursor: pointer; appearance: none; -webkit-appearance: none; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='white'%3E%3Cpath d='M7 10l5 5 5-5z'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 8px center; padding-right: 28px; min-width: 120px; transition: 0.3s; }
        .status-select:focus { outline: none; }
        .status-select.bg-selesai { background-color: #28a745; }
        .status-select.bg-diantar { background-color: #17a2b8; }
        .status-select.bg-dikemas { background-color: #dc3545; }
        .status-select option { background: #fff; color: #000; font-weight: 600; }

        .btn-bulk-delete { background: #fff; color: #000; border: 1px solid #000; padding: 9px 12px; border-radius: 8px; cursor: pointer; transition: 0.3s; font-size: 16px; }
        .btn-bulk-delete:hover { background: #ff4d4d; color: #fff; border-color: #ff4d4d; }

        .menu-toggle { display: none; font-size: 24px; cursor: pointer; margin-right: 15px; }
        .sidebar-overlay { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 900; }

        /* Checkbox */
        .check-container { display: inline-block; position: relative; cursor: pointer; width: 18px; height: 18px; }
        .check-container input { position: absolute; opacity: 0; cursor: pointer; }
        .checkmark { position: absolute; top: 0; left: 0; height: 18px; width: 18px; border: 1.5px solid #000; border-radius: 2px; }
        .check-container input:checked ~ .checkmark { background-color: #000; }
        .checkmark:after { content: ""; position: absolute; display: none; left: 5px; top: 1px; width: 5px; height: 10px; border: solid white; border-width: 0 2px 2px 0; transform: rotate(45deg); }
        .check-container input:checked ~ .checkmark:after { display: block; }

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
            @if(session('success'))
                <div class="alert-success">
                    <i class="fa fa-check-circle"></i> {{ session('success') }}
                </div>
            @endif

            <h2 class="welcome-title">Selamat Datang {{ session('user_role') == 'petugas' ? 'Petugas' : 'Admin' }}</h2>

            <div style="display: flex; justify-content: flex-end; margin-bottom: 20px;">
                <button type="button" class="btn-bulk-delete" title="Hapus yang dipilih" onclick="hapusTerpilih()">
                    <i class="fa fa-trash"></i>
                </button>
            </div>

            <form id="form-delete-selected" action="{{ route('admin.order.delete-masal') }}" method="POST" style="display:none;">
                @csrf @method('DELETE')
                <input type="hidden" name="ids" id="delete-ids">
            </form>

            <div class="table-responsive">
                <table class="order-table">
                    <thead>
                        <tr>
                            <th width="40">
                                <label class="check-container">
                                    <input type="checkbox" id="check-all" onchange="toggleAll(this)">
                                    <span class="checkmark"></span>
                                </label>
                            </th>
                            <th>Id Pesanan</th>
                            <th>Pelanggan</th>
                            <th>Kategori</th>
                            <th>Tanggal Dibuat</th>
                            <th>Pembayaran</th>
                            <th>Jumlah</th>
                            <th width="150">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders ?? [] as $order)
                        <tr>
                            <td>
                                <label class="check-container">
                                    <input type="checkbox" class="order-check" name="ids[]" value="{{ $order->id }}">
                                    <span class="checkmark"></span>
                                </label>
                            </td>
                            <td>{{ $order->id_pesanan }}</td>
                            <td>{{ $order->pelanggan }}</td>
                            <td>{{ $order->kategori }}</td>
                            <td>{{ date('d M Y', strtotime($order->created_at)) }}</td>
                            <td>{{ $order->pembayaran }}</td>
                            <td>{{ $order->jumlah }}</td>
                            <td>
                                @php
                                    $bgClass = match($order->status) {
                                        'selesai' => 'bg-selesai',
                                        'diantar' => 'bg-diantar',
                                        default => 'bg-dikemas',
                                    };
                                @endphp
                                <select class="status-select {{ $bgClass }}" onchange="updateStatus(this, {{ $order->id }})">
                                    <option value="dikemas" {{ $order->status == 'dikemas' ? 'selected' : '' }}>dikemas</option>
                                    <option value="diantar" {{ $order->status == 'diantar' ? 'selected' : '' }}>diantar</option>
                                    <option value="selesai" {{ $order->status == 'selesai' ? 'selected' : '' }}>selesai</option>
                                </select>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" style="text-align: center; color: #999;">Data order kosong.</td>
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

        function updateStatus(selectEl, orderId) {
            const status = selectEl.value;
            selectEl.classList.remove('bg-selesai', 'bg-diantar', 'bg-dikemas');
            if (status === 'selesai') selectEl.classList.add('bg-selesai');
            else if (status === 'diantar') selectEl.classList.add('bg-diantar');
            else selectEl.classList.add('bg-dikemas');

            fetch('/dashboard/order/update-status/' + orderId, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ status: status })
            });
        }

        function toggleAll(masterCheckbox) {
            document.querySelectorAll('.order-check').forEach(cb => cb.checked = masterCheckbox.checked);
        }

        function hapusTerpilih() {
            const checked = document.querySelectorAll('.order-check:checked');
            if (checked.length === 0) { alert('Pilih minimal 1 order!'); return; }
            if (confirm('Hapus order terpilih?')) {
                const ids = Array.from(checked).map(cb => cb.value);
                document.getElementById('delete-ids').value = JSON.stringify(ids);
                document.getElementById('form-delete-selected').submit();
            }
        }
    </script>
</body>
</html>





