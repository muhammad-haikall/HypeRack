<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

// --- 1. GUEST & AUTH AREA ---
Route::get('/', [LoginController::class, 'indexAdmin'])->name('admin.login');
Route::post('/login-admin', [LoginController::class, 'loginAdmin'])->name('admin.login.submit');
Route::get('/petugaslogin', [LoginController::class, 'indexPetugas'])->name('petugas.login');
Route::post('/petugaslogin', [LoginController::class, 'loginPetugas'])->name('petugas.login.submit');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Route untuk Halaman Login & Register User
Route::get('/login', function () {
    return view('user.login');
})->name('login');

Route::post('/login-user', [LoginController::class, 'loginUser'])->name('login.post');
Route::post('/register-user', [LoginController::class, 'registerUser'])->name('register.post');
Route::post('/logout-user', [LoginController::class, 'logoutUser'])->name('user.logout');

Route::get('/home', function () {
    // Auto-fix DB columns jika belum ada (Safe Migration)
    try {
        if (!\Illuminate\Support\Facades\Schema::hasColumn('produk', 'rating')) {
            \Illuminate\Support\Facades\Schema::table('produk', function($table) {
                $table->string('rating')->default('4.5');
                $table->string('brand')->default('HyperRack');
                $table->string('gambar')->default('baju1.png');
            });
        }
    } catch (\Exception $e) {}

    $latestOrderId = null;
    if (Session::has('user_id')) {
        $latestOrder = DB::table('order')
            ->where('pelanggan', Session::get('user_name'))
            ->orderBy('created_at', 'desc')
            ->first();
        $latestOrderId = $latestOrder ? $latestOrder->id : null;
    }

    // Ambil produk dari database
    $products = DB::table('produk')->get();

    // Pisahkan untuk New Arrivals (terbaru) dan Top Selling (rating tertinggi) - Ambil lebih banyak agar sinkron
    $newArrivals = DB::table('produk')->orderBy('created_at', 'desc')->get();
    $topSelling = DB::table('produk')->orderBy('rating', 'desc')->get();

    return view('user.dashboard', compact('latestOrderId', 'newArrivals', 'topSelling'));
})->name('user.home');

// --- ROUTE KERANJANG (CART) ---
Route::get('/cart', function () {
    $latestOrderId = null;
    if (Session::has('user_id')) {
        $latestOrder = DB::table('order')
            ->where('pelanggan', Session::get('user_name'))
            ->orderBy('created_at', 'desc')
            ->first();
        $latestOrderId = $latestOrder ? $latestOrder->id : null;
    }
    return view('user.cart', compact('latestOrderId'));
})->name('cart');

Route::post('/checkout', [LoginController::class, 'prosesCheckout'])->name('cart.checkout');

// --- ROUTE PEMBAYARAN (PAYMENT) ---
Route::get('/payment', function () {
    if (!Session::has('user_login')) {
        return redirect()->route('login')->with('error', 'Silahkan login terlebih dahulu.');
    }
    $alamat = '';
    if (Session::has('user_id')) {
        $userId = Session::get('user_id');
        
        // Ambil dari Users Table (Alamat Permanen)
        $user = DB::table('users')->where('id', $userId)->first();
        if ($user && !empty($user->alamat)) {
            $alamat = $user->alamat;
            Session::put('user_alamat', $alamat); // Simpan di session juga untuk backup
        } else {
            // Backup: ambil dari session jika ada
            $alamat = Session::get('user_alamat', '');
        }
    }
    return view('user.payment', compact('alamat'));
})->name('payment');

Route::post('/payment/confirm', [LoginController::class, 'konfirmasiPembayaran'])->name('payment.confirm');

Route::get('/payment/success', function () {
    $latestOrder = null;
    if (Session::has('user_id')) {
        $latestOrder = DB::table('order')
            ->where('pelanggan', Session::get('user_name'))
            ->orderBy('created_at', 'desc')
            ->first();
    }
    return view('user.payment_detail', compact('latestOrder'));
})->name('payment.success');

Route::get('/payment/status', function () {
    $orderId = 1;
    if (Session::has('user_id')) {
        $latestOrder = DB::table('order')
            ->where('pelanggan', Session::get('user_name'))
            ->orderBy('created_at', 'desc')
            ->first();

        $orderId = $latestOrder ? $latestOrder->id : 1;
        $totalPayment = $latestOrder ? $latestOrder->total_harga : 0;
        $orderDate = $latestOrder ? \Carbon\Carbon::parse($latestOrder->created_at)->format('d M Y') : date('d M Y');
        $payBefore = $latestOrder ? \Carbon\Carbon::parse($latestOrder->created_at)->addDay()->format('d M Y, H.i') . ' WIB' : date('d M Y, H.i', strtotime('+1 day')) . ' WIB';
    }
    return view('user.payment_status', compact('orderId', 'totalPayment', 'orderDate', 'payBefore'));
})->name('payment.status');

// --- ORDER DETAIL PENGGUNA ---
Route::get('/order/detail/{id}', [LoginController::class, 'orderDetail'])->name('order.detail');
Route::get('/order/history', [LoginController::class, 'orderHistory'])->name('order.history');
Route::post('/order/cancel/{id}', [LoginController::class, 'cancelOrder'])->name('order.cancel');
Route::post('/order/received/{id}', [LoginController::class, 'orderReceived'])->name('order.received');
Route::post('/address/update', [LoginController::class, 'updateAddressDatabase'])->name('address.update');

// --- DETAIL PRODUK ---
Route::get('/product/detail/{id}', function ($id) {
    // Ambil dari database
    $product = DB::table('produk')->where('id', $id)->first();
    
    // Fallback jika tidak ada
    if (!$product) {
        return redirect()->route('user.home')->with('error', 'Product not found.');
    }

    $latestOrderId = null;
    if (Session::has('user_id')) {
        $latestOrder = DB::table('order')
            ->where('pelanggan', Session::get('user_name'))
            ->orderBy('created_at', 'desc')
            ->first();
        $latestOrderId = $latestOrder ? $latestOrder->id : null;
    }

    // Penyesuaian nama field untuk view (DB menggunakan nama_produk dsb)
    // Tapi kita bisa kirim object $product langsung.
    return view('user.productdetail', compact('product', 'latestOrderId'));
})->name('product.detail');

// --- 2. ADMIN AREA ---
Route::prefix('dashboard')->group(function () {
    
    Route::get('/', function () {
        if (!Session::has('login')) {
            return redirect()->route('admin.login')->with('error', 'Silahkan login terlebih dahulu');
        }
        $orders = DB::table('order')->latest()->limit(10)->get();
        $totalOrder = DB::table('order')->count();
        $totalProduk = DB::table('produk')->count();
        $totalPendapatan = DB::table('order')->where('status', 'selesai')->sum('total_harga');
        return view('admin.dashboard', compact('orders', 'totalOrder', 'totalProduk', 'totalPendapatan'));
    })->name('admin.dashboard');

    // KELOLA PRODUK
    Route::get('/produk', [LoginController::class, 'kelolaProduk'])->name('admin.produk');
    Route::get('/produk/tambah', [LoginController::class, 'tambahProduk'])->name('admin.produk.tambah');
    Route::post('/produk/store', [LoginController::class, 'simpanProduk'])->name('admin.produk.store');
    Route::get('/produk/edit/{id}', [LoginController::class, 'editProduk'])->name('admin.produk.edit');
    Route::post('/produk/update/{id}', [LoginController::class, 'updateProduk'])->name('admin.produk.update');
    Route::get('/produk/update/{id}', function($id) { return redirect()->route('admin.produk.edit', $id); });
    Route::delete('/produk/delete/{id}', [LoginController::class, 'hapusProduk'])->name('admin.produk.delete');
    Route::delete('/produk/delete-masal', [LoginController::class, 'hapusMasal'])->name('admin.produk.delete-masal');

    // KELOLA ORDER
    Route::get('/order', [LoginController::class, 'kelolaOrder'])->name('admin.kelolaorder');
    Route::post('/order/update-status/{id}', [LoginController::class, 'updateStatusOrder'])->name('admin.order.update-status');
    Route::get('/order/update-status/{id}', function() { return redirect()->route('admin.kelolaorder'); });
    Route::delete('/order/delete/{id}', [LoginController::class, 'hapusOrder'])->name('admin.order.delete');
    Route::delete('/order/delete-masal', [LoginController::class, 'hapusOrderMasal'])->name('admin.order.delete-masal');

    // RIWAYAT PESANAN
    Route::get('/riwayat', [LoginController::class, 'riwayatPesanan'])->name('admin.riwayat');

    // KELOLA PETUGAS & PENGGUNA
    Route::get('/petugas', [LoginController::class, 'kelolaPetugas'])->name('admin.petugas');
    Route::get('/petugas/tambah', [LoginController::class, 'tambahPetugas'])->name('admin.petugas.tambah');
    Route::post('/petugas/store', [LoginController::class, 'simpanPetugas'])->name('admin.petugas.store');
    Route::get('/petugas/edit/{id}', [LoginController::class, 'editPetugas'])->name('admin.petugas.edit');
    Route::post('/petugas/update/{id}', [LoginController::class, 'updatePetugas'])->name('admin.petugas.update');
    Route::get('/petugas/update/{id}', function($id) { return redirect()->route('admin.petugas.edit', $id); });
    Route::delete('/petugas/delete/{id}', [LoginController::class, 'hapusPetugas'])->name('admin.petugas.delete');

    Route::get('/pengguna', [LoginController::class, 'kelolaPengguna'])->name('admin.pengguna');
    Route::post('/pengguna/status/{id}/{status}', [LoginController::class, 'statusPengguna'])->name('admin.pengguna.status');
    Route::delete('/pengguna/{id}', [LoginController::class, 'hapusPengguna'])->name('admin.pengguna.delete');
});