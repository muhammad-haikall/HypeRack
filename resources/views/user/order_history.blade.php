<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders - HyperRack</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800;900&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        outfit: ['Outfit', 'sans-serif'],
                        inter: ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style type="text/tailwindcss">
        @layer base {
            body { @apply font-inter bg-white text-black; }
            h1, h2, h3, h4 { @apply font-outfit; }
        }
    </style>
</head>
<body class="antialiased">

    <!-- Navbar -->
    <header class="w-full bg-white border-b border-gray-100 sticky top-0 z-[100]">
        <div class="max-w-[1440px] mx-auto px-6 lg:px-10 py-5 flex items-center justify-between">
            <a href="{{ route('user.home') }}" class="font-outfit font-extrabold text-[2rem] tracking-tighter text-black leading-none">HyperRack</a>
            
            <div class="flex items-center gap-5">
                <a href="{{ route('order.history') }}" class="text-2xl text-black hover:opacity-70 transition-opacity">
                    <i class="fa-solid fa-truck-fast"></i>
                </a>
                <a href="{{ route('cart') }}" class="text-2xl text-black hover:opacity-70 transition-opacity">
                    <i class="fa-solid fa-cart-shopping"></i>
                </a>
                <div class="w-9 h-9 rounded-full bg-black text-white flex items-center justify-center font-bold text-sm">
                    {{ strtoupper(substr(Session::get('user_name', 'U'), 0, 1)) }}
                </div>
            </div>
        </div>
    </header>

    <main class="max-w-[1000px] mx-auto px-6 lg:px-10 py-12 min-h-[60vh]">
        <div class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-4">
            <div>
                <h1 class="text-4xl font-black uppercase mb-2">My Orders</h1>
                <p class="text-gray-500 text-sm italic">"Review your fashion journey with us"</p>
            </div>
            <a href="{{ route('user.home') }}" class="text-sm font-bold border-b-2 border-black pb-1 hover:opacity-60 transition-opacity">Continue Shopping</a>
        </div>

        @if(count($orders) > 0)
            <div class="grid gap-6">
                @foreach($orders as $order)
                <div class="border border-gray-100 rounded-2xl p-6 flex flex-col md:flex-row items-center justify-between gap-6 hover:shadow-xl transition-shadow duration-300 bg-white">
                    <div class="flex items-center gap-6 w-full md:w-auto">
                        <div class="w-20 h-24 bg-gray-50 rounded-xl flex items-center justify-center flex-shrink-0 overflow-hidden">
                            @if(isset($order->gambar) && $order->gambar)
                                <img src="{{ str_contains($order->gambar, 'http') ? $order->gambar : (str_contains($order->gambar, 'images/') ? asset($order->gambar) : asset('images/' . $order->gambar)) }}" class="w-full h-full object-contain" alt="Product">
                            @else
                                <i class="fa-solid fa-box text-3xl text-gray-300"></i>
                            @endif
                        </div>
                        <div>
                            <div class="flex items-center gap-2 mb-1">
                                <span class="bg-gray-100 text-[10px] font-bold px-2 py-0.5 rounded uppercase tracking-wider text-gray-500">Order ID: {{ $order->id_pesanan }}</span>
                                @if($order->status == 'selesai')
                                    <span class="bg-green-100 text-green-600 text-[10px] font-bold px-2 py-0.5 rounded uppercase">Delivered</span>
                                @elseif($order->status == 'diantar')
                                    <span class="bg-blue-100 text-blue-600 text-[10px] font-bold px-2 py-0.5 rounded uppercase">On Transit</span>
                                @else
                                    <span class="bg-orange-100 text-orange-600 text-[10px] font-bold px-2 py-0.5 rounded uppercase">Processing</span>
                                @endif
                            </div>
                            <h3 class="text-xl font-bold mb-1">{{ date('d M Y, H:i', strtotime($order->created_at)) }}</h3>
                            <p class="text-sm text-gray-500">{{ $order->jumlah }} Items • {{ $order->pembayaran }}</p>
                        </div>
                    </div>

                    <div class="flex items-center justify-between md:justify-end gap-10 w-full md:w-auto border-t md:border-none pt-4 md:pt-0">
                        <div class="text-right">
                            <p class="text-xs text-gray-400 mb-1">Total Amount</p>
                            <p class="text-xl font-black">Rp{{ number_format($order->total_harga, 0, ',', '.') }}</p>
                        </div>
                        <a href="{{ route('order.detail', $order->id) }}" class="bg-black text-white px-8 py-3 rounded-full text-xs font-bold hover:bg-gray-800 transition shadow-lg shadow-gray-200">View Detail</a>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <!-- Empty State -->
            <div class="flex flex-col items-center justify-center py-20 text-center">
                <div class="w-32 h-32 bg-gray-50 rounded-full flex items-center justify-center mb-8">
                    <i class="fa-solid fa-truck-fast text-5xl text-gray-200"></i>
                </div>
                <h2 class="text-2xl font-bold mb-3">No Orders Found</h2>
                <p class="text-gray-400 max-w-[320px] mb-10 leading-relaxed">It looks like you haven't made any purchases yet. Your fashion story starts here!</p>
                <a href="{{ route('user.home') }}" class="bg-black text-white px-10 py-4 rounded-full font-bold text-sm hover:scale-105 transition-transform shadow-2xl">Start Shopping</a>
            </div>
        @endif
    </main>

    <!-- Footer -->
    <footer class="bg-[#F0F0F0] mt-20 py-10">
        <div class="max-w-[1440px] mx-auto px-6 text-center">
            <h2 class="font-outfit font-extrabold text-2xl mb-4">HyperRack</h2>
            <p class="text-gray-500 text-xs italic mb-6">"Your Local Wear, Elevated"</p>
            <p class="text-gray-400 text-[10px]">© 2024 HyperRack. All Rights Reserved.</p>
        </div>
    </footer>

</body>
</html>
