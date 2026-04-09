<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Barryvdh\DomPDF\Facade\Pdf;

class LoginController extends Controller
{
    /**
     * Helper untuk mengecek session login admin.
     * Digunakan secara internal di dalam controller.
     */
    private function checkLogin()
    {
        if (!Session::has('login')) {
            return redirect()->route('admin.login')->send();
        }

        // Tanda Online: Update waktu aktivitas terakhir jika user adalah petugas atau admin
        if (Session::has('user_id')) {
            DB::table('petugas')
                ->where('id', Session::get('user_id'))
                ->update(['updated_at' => \Carbon\Carbon::now('Asia/Jakarta')]);
        }
    }

    private function checkAdmin()
    {
        if (Session::get('user_role') !== 'admin') {
            // Redirect alih-alih abort 403 agar user tidak bingung
            header('Location: ' . route('admin.dashboard') . '?error=hanya_admin');
            exit();
        }
    }

    // --- AUTH ADMIN & PETUGAS ---

    public function indexAdmin() 
    {
        return view('admin.login');
    }

    public function loginAdmin(Request $request) 
    {
        $request->validate([
            'nama' => 'required',
            'password' => 'required'
        ]);

        // AKSES DARURAT JIKA DATABASE BERMASALAH/HILANG
        if ($request->nama === 'admin' && $request->password === 'admin123') {
            Session::put('login', true);
            Session::put('user_role', 'admin');
            Session::put('user_id', 999); // ID semu untuk bypass
            Session::put('user_nama', 'Super Admin');
            return redirect()->route('admin.dashboard');
        }

        $user = DB::table('petugas')
            ->where('nama', $request->nama)
            ->where('password', $request->password)
            ->where('role', 'admin') // Diperketat: Hanya Admin
            ->first();

        if ($user) {
            Session::put('login', true);
            Session::put('user_role', 'admin');
            Session::put('user_id', $user->id);
            Session::put('user_nama', $user->nama);
            return redirect()->route('admin.dashboard');
        }

        return back()->with('error', 'Nama atau Password Admin salah!');
    }

    public function indexPetugas() 
    {
        return view('admin.petugaslogin');
    }

    public function loginPetugas(Request $request) 
    {
        $request->validate([
            'nama' => 'required',
            'password' => 'required'
        ]);

        $user = DB::table('petugas')
            ->where('nama', $request->nama)
            ->where('password', $request->password)
            ->where('role', 'petugas') // Diperketat: Hanya Petugas
            ->first();

        if ($user) {
            Session::put('login', true);
            Session::put('user_role', 'petugas');
            Session::put('user_id', $user->id);
            Session::put('user_nama', $user->nama);
            return redirect()->route('admin.dashboard');
        }

        return back()->with('error', 'Nama atau Password Petugas salah!');
    }

    public function logout() 
    {
        $role = Session::get('user_role'); // Ingat perannya sebelum dihapus

        // Ubah jadi offline saat logout (set updated_at ke 2 hari yang lalu agar lgs lari ke status offline)
        if (Session::has('user_id')) {
            DB::table('petugas')
                ->where('id', Session::get('user_id'))
                ->update(['updated_at' => \Carbon\Carbon::now('Asia/Jakarta')->subDays(2)]);
        }

        Session::flush();

        // Alihkan ke pintu login yang sesuai
        if ($role == 'petugas') {
            return redirect()->route('petugas.login')->with('success', 'Berhasil logout petugas');
        }
        
        return redirect()->route('admin.login')->with('success', 'Berhasil logout admin');
    }

    // --- KELOLA PRODUK ---
    
    public function kelolaProduk(Request $request) 
    {
        $this->checkLogin();

        $products = DB::table('produk')->get();
        $isAllSelected = $request->query('all') == 1;

        $view = Session::get('user_role') == 'petugas' ? 'petugas.kelolaproduk' : 'admin.kelolaproduk';
        return view($view, compact('products', 'isAllSelected')); 
    }

    public function tambahProduk() 
    {
        $this->checkLogin();
        $view = Session::get('user_role') == 'petugas' ? 'petugas.tambahproduk' : 'admin.tambahproduk';
        return view($view);
    }

    public function simpanProduk(Request $request) 
    {
        $this->checkLogin();

        $request->validate([
            'nama_produk' => 'required',
            'harga' => 'required|numeric',
            'stok' => 'required|integer',
            'kategori' => 'required',
            'gambar_file' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $fileName = 'baju1.png';
        if ($request->hasFile('gambar_file')) {
            $file = $request->file('gambar_file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images'), $fileName); 
        }
        
        DB::table('produk')->insert([
            'nama_produk' => $request->nama_produk,
            'harga' => $request->harga,
            'stok' => $request->stok,
            'kategori' => $request->kategori,
            'gambar' => $fileName,
            'rating' => '4.5',
            'brand' => $request->brand ?? 'HyperRack',
            'status' => $request->status ?? 'draf',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return redirect()->route('admin.produk')->with('success', 'Produk berhasil ditambahkan!');
    }

    public function editProduk($id) 
    {
        $this->checkLogin();

        $product = DB::table('produk')->where('id', $id)->first();
        
        if (!$product) {
            return redirect()->route('admin.produk')->with('error', 'Produk tidak ditemukan');
        }

        $view = Session::get('user_role') == 'petugas' ? 'petugas.editproduk' : 'admin.editproduk';
        return view($view, compact('product'));
    }

    public function updateProduk(Request $request, $id) 
    {
        $this->checkLogin();

        $product = DB::table('produk')->where('id', $id)->first();
        if (!$product) {
            return redirect()->route('admin.produk')->with('error', 'Produk tidak ditemukan');
        }

        $fileName = $request->gambar_lama ?: $product->gambar;
        if ($request->hasFile('gambar_file')) {
            $file = $request->file('gambar_file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images'), $fileName);
        }

        // Just in case fileName is still null (shouldn't happen with the fallback above)
        if (!$fileName) {
            $fileName = 'baju1.png';
        }

        DB::table('produk')->where('id', $id)->update([
            'nama_produk' => $request->nama_produk,
            'harga' => $request->harga,
            'stok' => $request->stok,
            'kategori' => $request->kategori,
            'gambar' => $fileName,
            'brand' => $request->brand,
            'status' => $request->status,
            'updated_at' => now()
        ]);

        return redirect()->route('admin.produk')->with('success', 'Produk berhasil diperbarui');
    }

    public function hapusProduk($id) 
    {
        $this->checkLogin();
        DB::table('produk')->where('id', $id)->delete();
        return back()->with('success', 'Produk berhasil dihapus!');
    }

    public function hapusMasal() 
    {
        $this->checkLogin();
        DB::table('produk')->truncate(); 
        return redirect()->route('admin.produk')->with('success', 'Semua produk berhasil dibersihkan!');
    }

    // --- KELOLA ORDER ---

    public function kelolaOrder(Request $request) 
    {
        $this->checkLogin();
        
        $orders = DB::table('order')->get();
        $isAllSelected = $request->query('all') == 1;

        $view = Session::get('user_role') == 'petugas' ? 'petugas.kelolaorder' : 'admin.kelolaorder';
        return view($view, compact('orders', 'isAllSelected'));
    }

    public function updateStatusOrder(Request $request, $id) 
    {
        $this->checkLogin();
        DB::table('order')->where('id', $id)->update([
            'status' => $request->status,
            'updated_at' => now()
        ]);
        return back()->with('success', 'Status order berhasil diperbarui!');
    }

    public function hapusOrder($id) 
    {
        $this->checkLogin();
        DB::table('order')->where('id', $id)->delete();
        return back()->with('success', 'Order berhasil dihapus!');
    }

    public function hapusOrderMasal(Request $request) 
    {
        $this->checkLogin();
        $ids = json_decode($request->ids, true);
        if (!empty($ids)) {
            DB::table('order')->whereIn('id', $ids)->delete();
        }
        return redirect()->route('admin.kelolaorder')->with('success', 'Order yang dipilih berhasil dihapus!');
    }

    // --- RIWAYAT PESANAN ---

    public function riwayatPesanan() 
    {
        $this->checkLogin();
        $orders = DB::table('order')->latest()->get();
        $view = Session::get('user_role') == 'petugas' ? 'petugas.riwayatpesanan' : 'admin.riwayatpesanan';
        return view($view, compact('orders'));
    }

    // KELOLA PETUGAS
    public function kelolaPetugas() 
    {
        $this->checkLogin();
        
        // Memastikan hanya Admin yang bisa mengelola petugas
        if (Session::get('user_role') !== 'admin') {
            abort(403, 'Akses ditolak. Hanya Admin yang memiliki izin.');
        }

        $petugas = DB::table('petugas')->get(); 
        return view('admin.kelolapetugas', compact('petugas'));
    }

public function tambahPetugas() 
{
    $this->checkLogin();
    $this->checkAdmin();
    return view('admin.tambahpetugas');
}

public function simpanPetugas(Request $request) 
{
    $this->checkLogin();
    $this->checkAdmin();
    $request->validate([
        'nama' => 'required|unique:petugas,nama',
        'password' => 'required',
    ]);
    
    DB::table('petugas')->insert([
        'nama' => $request->nama,
        'password' => $request->password,
        'role' => 'petugas', // Akun yang dibuat admin otomatis menjadi Petugas
        'created_at' => now(),
        'updated_at' => now()
    ]);

    return redirect()->route('admin.petugas')->with('success', 'Akun Petugas berhasil ditambahkan!');
}

public function editPetugas($id) 
{
    $this->checkLogin();
    $this->checkAdmin();
    $petugas = DB::table('petugas')->where('id', $id)->first();
    return view('admin.editpetugas', compact('petugas'));
}

public function updatePetugas(Request $request, $id) 
{
    $this->checkLogin();
    $this->checkAdmin();
    $request->validate([
        'nama' => 'required|unique:petugas,nama,'.$id,
    ]);

    $data = ['nama' => $request->nama, 'updated_at' => now()];
    if ($request->password) {
        $data['password'] = $request->password;
    }

    DB::table('petugas')->where('id', $id)->update($data);
    return redirect()->route('admin.petugas')->with('success', 'Data berhasil diperbarui!');
}

public function hapusPetugas($id) 
{
    $this->checkLogin();
    $this->checkAdmin();
    DB::table('petugas')->where('id', $id)->delete();
    return back()->with('success', 'Akun berhasil dihapus!');
}

// ... KELOLA PENGGUNA (Dashboard Admin) ...

    public function kelolaPengguna() 
    {
        $this->checkLogin();
        $this->checkAdmin();
        
        $users = DB::table('users')
            ->select('users.*')
            ->addSelect([
                'jumlah_order' => DB::table('order')
                    ->whereColumn('pelanggan', 'users.username')
                    ->selectRaw('count(*)')
            ])
            ->get(); 

        return view('admin.kelolapengguna', compact('users'));
    }

public function hapusPengguna($id)
{
    $this->checkLogin();
    $this->checkAdmin();
    DB::table('users')->where('id', $id)->delete();
    return back()->with('success', 'Pengguna berhasil dihapus!');
}

public function statusPengguna($id, $status)
{
    $this->checkLogin();
    $this->checkAdmin();
    DB::table('users')->where('id', $id)->update([
        'status' => $status,
        'updated_at' => now()
    ]);
    $msg = $status == 'blokir' ? 'Pengguna berhasil diblokir!' : 'Blokir akun berhasil dibuka!';
    return back()->with('success', $msg);
}

// --- KELOLA USER (CUSTOMER) ---

public function registerUser(Request $request)
{
    $request->validate([
        'username' => 'required',
        'email' => 'required|email',
        'password' => 'required|min:6|confirmed', 
    ]);

    try {
        DB::table('users')->insert([
            'name' => $request->username,
            'email' => $request->email,
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
            'status' => 'aktif',
            'created_at' => now(),
            'updated_at' => now()
        ]);
    } catch (\Exception $e) {
        DB::table('users')->insert([
            'username' => $request->username,
            'nama_lengkap' => $request->username,
            'email' => $request->email,
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
            'status' => 'aktif',
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }

    return redirect()->route('login')->with('success', 'Akun berhasil dibuat! Silahkan login dengan akun anda.');
}

public function loginUser(Request $request)
{
    $request->validate([
        'username' => 'required',
        'password' => 'required'
    ]);

    $user = DB::table('users')->where('username', $request->username)->first();
    
    if (!$user) {
        $user = DB::table('users')->where('email', $request->username)->first();
    }

    if ($user && \Illuminate\Support\Facades\Hash::check($request->password, $user->password)) {
        if (isset($user->status) && $user->status == 'blokir') {
            return back()->with('error', 'Maaf, Akun kamu diblokir oleh admin!');
        }

        Session::put('user_login', true);
        Session::put('user_id', $user->id);
        Session::put('user_name', $user->username);
        
        return redirect()->route('user.home');
    }

    return back()->with('error', 'Username atau Password salah!');
}

public function logoutUser()
{
    Session::forget('user_login');
    Session::forget('user_id');
    Session::forget('user_name');
    
    return redirect()->route('user.home');
}
    public function payment($orderId)
    {
        if (!Session::has('user_login')) {
            return redirect()->route('login');
        }

        $order = DB::table('order')->where('id', $orderId)->first();

        if (!$order) {
            return redirect()->route('user.home')->with('error', 'Order tidak ditemukan.');
        }

        return view('user.payment', [
            'orderId'        => $order->id,
            'virtualAccount' => $order->virtual_account ?? '-',
            'totalPayment'   => $order->total_harga ?? 0,
            'alamat'         => $order->alamat,
        ]);
    }

    public function prosesCheckout(Request $request)
    {
        if (!Session::has('user_login')) {
            return response()->json(['success' => false, 'message' => 'Silahkan login terlebih dahulu.'], 401);
        }

        $cartData = json_decode($request->cart_data, true);
        if (empty($cartData)) {
            return response()->json(['success' => false, 'message' => 'Keranjang kosong.'], 400);
        }

        $userId = Session::get('user_id');
        $user = DB::table('users')->where('id', $userId)->first();
        $finalAlamat = $request->alamat;

        // Cek jika alamat dari request kosong atau masih placeholder, ambil dari DB
        if (empty($finalAlamat) || trim($finalAlamat) == "Add your address" || trim($finalAlamat) == "Edit Address") {
            $finalAlamat = $user->alamat ?? null;
        }

        // Jika tetap kosong, kirim pesan error (Harus diisi agar tidak hilang)
        if (empty($finalAlamat)) {
            return response()->json(['success' => false, 'message' => 'Please set your shipping address first!'], 400);
        }

        // UPDATE PERMANEN KE DATABASE USER (Agar tidak perlu input ulang lain kali)
        DB::table('users')->where('id', $userId)->update([
            'alamat' => $finalAlamat,
            'updated_at' => now(),
        ]);
        
        // Simpan juga di Session untuk load cepat di halaman lain
        Session::put('user_alamat', $finalAlamat);

        $totalHarga = 0;
        foreach ($cartData as $item) {
            $totalHarga += $item['subtotalNum'];
        }
        $totalHarga += 5000; // Biaya layanan/ongkir
        
        // Tambahkan Biaya Transaksi jika VA atau COD
        if (strpos($request->payment_method, '_va') !== false || $request->payment_method === 'cod') {
            $totalHarga += 1000;
        }

        // Safe Migration: Tambahkan kolom gambar jika belum ada untuk preview di history
        try {
            if (!\Illuminate\Support\Facades\Schema::hasColumn('order', 'gambar')) {
                \Illuminate\Support\Facades\Schema::table('order', function($table) {
                    $table->string('gambar')->nullable();
                });
            }
        } catch (\Exception $e) {}

        // 1. Simpan ke tabel order
        $orderId = DB::table('order')->insertGetId([
            'id_pesanan' => 'ORD-' . strtoupper(uniqid()),
            'pelanggan' => Session::get('user_name'),
            'status' => 'dikemas',
            'pembayaran' => $request->payment_method ?? 'Transfer Bank',
            'kategori' => $cartData[0]['category'] ?? 'Produk',
            'jumlah' => count($cartData),
            'total_harga' => $totalHarga, 
            'alamat' => $finalAlamat, // Simpan Alamat final (yang sudah divalidasi)
            'gambar' => (!empty($cartData[0]['image'])) ? $cartData[0]['image'] : 'baju1.png', // Simpan gambar produk pertama untuk riwayat
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // 2. Simpan data barang ke Session (karena tabel order_items tidak ada)
        Session::put('last_order_items_' . $orderId, $cartData);

        // 3. Tentukan Redirect berdasarkan metode pembayaran
        $redirectUrl = route('order.detail', $orderId);
        
        // Jika pembayarannya menggunakan Virtual Account (berakhiran _va)
        if (strpos($request->payment_method, '_va') !== false) {
            $redirectUrl = route('payment.success', ['payment' => $request->payment_method]);
        }

        return response()->json([
            'success' => true, 
            'redirect' => $redirectUrl
        ]);
    }

    public function orderDetail($orderId)
    {
        $userId = Session::get('user_id') ?? 1;

        // Cari order berdasarkan ID asli atau ID Pesanan (ORD-...)
        $order = DB::table('order')->where('id', $orderId)->first();
        if (!$order) {
            $order = DB::table('order')->where('id_pesanan', $orderId)->first();
        }

        if (!$order) {
            return redirect()->route('user.home')->with('error', 'Order not found.');
        }

        try {
            $user = DB::table('users')->where('id', $userId)->first();
        } catch (\Exception $e) {
            $user = null;
        }

        $orderItems = [];
        
        // Cek data dari Session terlebih dahulu (Solusi karena tabel order_items tidak ada)
        if (Session::has('last_order_items_' . $orderId)) {
            $sessionItems = Session::get('last_order_items_' . $orderId);
            foreach ($sessionItems as $item) {
                $orderItems[] = [
                    'name'  => $item['name'],
                    'size'  => $item['size'] ?? 'L',
                    'color' => $item['color'] ?? 'Original',
                    'price' => $item['priceNum'] ?? ($item['subtotalNum'] ?? 0),
                    'brand' => $item['brand'] ?? 'HypeRack',
                    'image' => $item['image'] ?? null,
                ];
            }
        } 
        // Jika tidak ada di session, coba cari di table (jika suatu saat tabel dibuat)
        else {
            try {
                $orderItems = DB::table('order_items')
                    ->join('produk', 'order_items.produk_id', '=', 'produk.id')
                    ->where('order_items.order_id', $orderId)
                    ->select(
                        'produk.nama_produk as name',
                        'order_items.size',
                        'order_items.color',
                        'order_items.harga as price',
                        'order_items.brand',
                        'produk.gambar as image'
                    )
                    ->get()
                    ->map(fn($item) => (array) $item)
                    ->toArray();
            } catch (\Exception $e) {
                $orderItems = [];
            }
        }

        if (empty($orderItems)) {
            $orderItems = [
                [
                    'brand' => $order->brand ?? 'HypeRack',
                    'name'  => $order->nama_produk ?? 'Sample Premium Tee',
                    'size'  => 'L',
                    'color' => 'Black',
                    'price' => $order->total_harga ?? 381000,
                    'image' => null,
                ]
            ];
        }

        $statusMap = [
            'dikemas' => 'dikemas',
            'diantar' => 'diantar', 
            'selesai' => 'selesai',
        ];

        $orderStatus = $statusMap[$order->status ?? 'dikemas'] ?? 'dikemas';

        return view('user.order-detail', [
            'customerName' => $user->name ?? $user->username ?? Session::get('user_name') ?? 'Customer',
            'orderStatus'  => $orderStatus,
            'address'      => $order->alamat ?? $user->alamat ?? 'Address not found',
            'products'     => $orderItems,
            'orderId'      => $orderId,
            'pembayaran'   => $order->pembayaran ?? 'cod',
        ]);
    }

    public function cancelOrder($id)
    {
        $userId = Session::get('user_id');
        
        // PASTIKAN KOLOM PEMBAYARAN ADA (Agar tidak bug)
        try {
            $columns = DB::select("SHOW COLUMNS FROM `order` LIKE 'pembayaran'");
            if (empty($columns)) {
                DB::statement("ALTER TABLE `order` ADD pembayaran VARCHAR(255) DEFAULT 'cod'");
            }
        } catch (\Exception $e) {}

        $order = DB::table('order')->where('id', $id)->first();
        
        if (!$order) {
            return redirect()->route('user.home')->with('error', 'Order not found.');
        }

        // Ambil status (Case-insensitive)
        $status = trim(strtolower($order->status ?? ''));

        // Jika status masih 'dikemas' (Packed)
        if ($status === 'dikemas' || empty($status) || $status === 'pending') {
            // HAPUS PERMANEN (Request User: riwayat di admin/petugas juga hilang)
            DB::table('order')->where('id', $id)->delete();
            return redirect()->route('user.home')->with('success', 'Order has been successfully cancelled and removed.');
        }

        // Jika sudah diproses lebih lanjut, tidak bisa dicancel
        return redirect()->route('user.home')->with('error', 'Cancellation failed. Orders that have already been shipped cannot be cancelled.');
    }

    public function orderReceived($id)
    {
        DB::table('order')->where('id', $id)->update([
            'status' => 'selesai',
            'updated_at' => now()
        ]);
        return back()->with('success', 'Thank you! The order has been successfully completed.');
    }

    public function orderHistory()
    {
        if (!Session::has('user_login')) {
            return redirect()->route('login')->with('error', 'Silakan login untuk melihat riwayat pesanan.');
        }

        $userId = Session::get('user_id');
        $username = Session::get('user_name');

        // Safe Migration: Pastikan kolom gambar ada agar tidak error saat dipanggil di view
        try {
            if (!\Illuminate\Support\Facades\Schema::hasColumn('order', 'gambar')) {
                \Illuminate\Support\Facades\Schema::table('order', function($table) {
                    $table->string('gambar')->nullable();
                });
            }
        } catch (\Exception $e) {}

        // Backfill: Berikan gambar default untuk pesanan lama yang kolom gambar-nya masih kosong
        DB::table('order')->whereNull('gambar')->orWhere('gambar', '')->update(['gambar' => 'baju1.png']);

        $orders = DB::table('order')
            ->where('pelanggan', $username)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('user.order_history', compact('orders'));
    }

    public function updateAddressDatabase(Request $request)
    {
        $userId = Session::get('user_id');
        $address = $request->alamat;

        // PAKSA TAMBAH KOLOM (Metode yang lebih kompatibel)
        try {
            // Cek dulu apakah kolom sudah ada agar tidak error
            $columns = DB::select("SHOW COLUMNS FROM users LIKE 'alamat'");
            if (empty($columns)) {
                DB::statement("ALTER TABLE users ADD alamat TEXT NULL");
            }
        } catch (\Exception $e) {
            // Diamkan jika terjadi masalah lain
        }

        // Simpan Permanen ke Database
        DB::table('users')->where('id', $userId)->update([
            'alamat' => $address,
            'updated_at' => now(),
        ]);

        // Simpan di Session untuk backup dan load cepat
        Session::put('user_alamat', $address);

        return response()->json(['success' => true, 'address' => $address]);
    }

    public function editAddress(Request $request)
    {
        $address = $request->alamat;
        $userId = Session::get('user_id');

        // PAKSA TAMBAH KOLOM (Metode yang lebih kompatibel)
        try {
            $columns = DB::select("SHOW COLUMNS FROM users LIKE 'alamat'");
            if (empty($columns)) {
                DB::statement("ALTER TABLE users ADD alamat TEXT NULL");
            }
        } catch (\Exception $e) {}
        
        DB::table('users')->where('id', $userId)->update(['alamat' => $address]);
        
        return back()->with('success', 'Alamat berhasil diperbarui!');
    }

    public function laporan(Request $request)
    {
        $this->checkLogin();

        $bulan = $request->get('bulan', date('m'));
        $tahun = $request->get('tahun', date('Y'));

        // Pemasukan: Barang yang ditambahkan ke stok (Produk baru/update)
        $pemasukan = DB::table('produk')
            ->whereMonth('created_at', $bulan)
            ->whereYear('created_at', $tahun)
            ->orderBy('created_at', 'desc')
            ->get();

        // Penjualan (sebelumnya Pengeluaran): Pesanan yang diproses (Order)
        $penjualan = DB::table('order')
            ->whereMonth('created_at', $bulan)
            ->whereYear('created_at', $tahun)
            ->orderBy('created_at', 'desc')
            ->get();

        // Laporan Keuangan: Total Pendapatan dari Order (biasanya yang sudah selesai)
        $totalPendapatan = DB::table('order')
            ->whereMonth('created_at', $bulan)
            ->whereYear('created_at', $tahun)
            ->where('status', 'selesai')
            ->sum('total_harga');

        $totalPesanan = DB::table('order')
            ->whereMonth('created_at', $bulan)
            ->whereYear('created_at', $tahun)
            ->count();

        $view = Session::get('user_role') == 'petugas' ? 'petugas.laporan' : 'admin.laporan';
        return view($view, compact('pemasukan', 'penjualan', 'totalPendapatan', 'totalPesanan', 'bulan', 'tahun'));
    }

    public function downloadLaporan(Request $request)
    {
        $this->checkLogin();

        $bulan = $request->get('bulan', date('m'));
        $tahun = $request->get('tahun', date('Y'));

        $pemasukan = DB::table('produk')
            ->whereMonth('created_at', $bulan)
            ->whereYear('created_at', $tahun)
            ->orderBy('created_at', 'desc')
            ->get();
            
        $penjualan = DB::table('order')
            ->whereMonth('created_at', $bulan)
            ->whereYear('created_at', $tahun)
            ->orderBy('created_at', 'desc')
            ->get();

        $totalPendapatan = DB::table('order')
            ->whereMonth('created_at', $bulan)
            ->whereYear('created_at', $tahun)
            ->where('status', 'selesai')
            ->sum('total_harga');

        $data = [
            'pemasukan' => $pemasukan,
            'penjualan' => $penjualan,
            'totalPendapatan' => $totalPendapatan,
            'title' => 'Laporan Bulanan HyperRack',
            'date' => date('d/m/Y'),
            'bulanText' => date('F', mktime(0, 0, 0, $bulan, 1)),
            'tahun' => $tahun
        ];

        $pdf = Pdf::loadView('admin.laporan_pdf', $data);
        return $pdf->download('laporan-hyperrack-' . $tahun . '-' . $bulan . '.pdf');
    }

}