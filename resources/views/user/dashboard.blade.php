<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HyperRack - Find Local Wear</title>
    <!-- Google Fonts: Outfit for headers, Inter for normal text -->
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
        html { scroll-behavior: smooth; }
        @layer base {
            body { @apply font-inter bg-white text-black overflow-x-hidden; }
            h1, h2, h3, h4 { @apply font-outfit; }
        }
        @layer components {
            .logo-font { @apply font-outfit font-extrabold text-[2rem] tracking-tighter; }
            .bg-section-gray { @apply bg-[#F0F0F1]; }
            .bg-product-gray { @apply bg-[#F0EEED]; }
            .hero-title { @apply text-[3.5rem] lg:text-[4.5rem] leading-[1] font-black tracking-[-0.02em]; }
            .brand-logo { @apply text-[1.5rem] font-bold tracking-[0.1em] text-white; }
            .browse-card { @apply bg-white rounded-[20px] relative overflow-hidden transition-transform duration-300; }
        }
    </style>
</head>
<body class="antialiased">

    <!-- Navbar -->
    <header class="w-full bg-white border-b border-gray-100">
        <div class="max-w-[1440px] mx-auto px-10 py-5 flex items-center justify-between gap-10">
            <!-- Mobile Menu Toggle -->
            <button class="lg:hidden text-2xl"><i class="fa fa-bars"></i></button>

            <!-- Logo -->
            <a href="{{ route('user.home') }}" class="font-outfit font-extrabold text-[2rem] tracking-tighter hover:opacity-80 transition-opacity text-black">HyperRack</a>

            <!-- Nav Links -->
            <nav class="hidden lg:flex items-center gap-6">
                <a href="#" class="text-black font-medium flex items-center gap-1">Shop <i class="fa fa-chevron-down text-[10px]"></i></a>
                <a href="{{ route('user.home') }}#top-selling" class="text-black font-medium">On Sale</a>
                <a href="{{ route('user.home') }}#new-arrivals" class="text-black font-medium">New Arrivals</a>
                <a href="{{ route('user.home') }}#browse" class="text-black font-medium">Brands</a>
            </nav>

            <!-- Icons -->
            <div class="flex items-center gap-5 ml-auto">
                
                {{-- Order History Icon --}}
                <a href="{{ route('order.history') }}" class="text-2xl hover:opacity-80 transition-opacity" title="My Orders">
                    <i class="fa-solid fa-truck-fast"></i>
                </a>

                <div class="relative flex items-center">
                    <a href="#" class="text-2xl relative block" onclick="toggleCart(event)">
                        <i class="fa-solid fa-cart-shopping cursor-pointer"></i>
                        <span id="cart-indicator" class="absolute -top-1.5 -right-2 w-3 h-3 bg-red-600 border-2 border-white rounded-full hidden"></span>
                    </a>
                    
                    <div id="cart-popover" class="absolute right-0 top-full mt-6 w-[360px] bg-white border border-gray-300 shadow-[0_10px_30px_rgba(0,0,0,0.1)] p-5 hidden z-[100] text-left text-sm cursor-default rounded" onclick="event.stopPropagation()">
                        <div id="cart-items" class="min-h-[50px] flex items-center justify-center text-xs text-gray-400">
                            No items in cart
                        </div>
                        <div class="mt-4 flex flex-col gap-2">
                            <a href="javascript:void(0)" onclick="proceedToCheckout()" class="w-full text-center block bg-black text-white text-[11px] font-bold py-2.5 rounded hover:bg-gray-800 transition">Checkout</a>
                            <a href="{{ route('cart') }}" class="w-full text-center block bg-white border border-black text-black text-[11px] font-bold py-2.5 rounded hover:bg-gray-50 transition">View Detail Product</a>
                        </div>
                    </div>
                </div>
                
                @if(Session::has('user_login'))
                    <div class="relative group cursor-pointer flex items-center justify-center w-9 h-9 rounded-full bg-black text-white font-bold text-base">
                        {{ strtoupper(substr(Session::get('user_name', 'U'), 0, 1)) }}
                        
                        <!-- Dropdown Menu -->
                        <div class="absolute right-0 top-full mt-2 w-32 bg-white border border-gray-100 rounded-lg shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all z-50">
                            <form action="{{ route('user.logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-50 rounded-lg font-normal block">Logout</button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="text-2xl"><i class="fa-regular fa-user"></i></a>
                @endif
            </div>
        </div>
    </header>

    @if(session('success'))
    <div id="toast-success" class="fixed top-20 left-1/2 -translate-x-1/2 z-[200] w-full max-w-md px-6 transition-all duration-500">
        <div class="bg-green-500 text-white px-6 py-3 rounded-xl shadow-2xl flex items-center gap-3 animate-bounce">
            <i class="fa fa-check-circle text-lg"></i>
            <span class="font-bold text-sm">{{ session('success') }}</span>
        </div>
    </div>
    <script>
        setTimeout(() => {
            const toast = document.getElementById('toast-success');
            if(toast) {
                toast.style.opacity = '0';
                toast.style.transform = 'translate(-50%, -20px)';
                setTimeout(() => toast.remove(), 500);
            }
        }, 2000);
    </script>
    @endif

    @if(session('error'))
    <div id="toast-error" class="fixed top-20 left-1/2 -translate-x-1/2 z-[200] w-full max-w-md px-6 transition-all duration-500">
        <div class="bg-red-500 text-white px-6 py-3 rounded-xl shadow-2xl flex items-center gap-3">
            <i class="fa fa-exclamation-circle text-lg"></i>
            <span class="font-bold text-sm">{{ session('error') }}</span>
        </div>
    </div>
    <script>
        setTimeout(() => {
            const toast = document.getElementById('toast-error');
            if(toast) {
                toast.style.opacity = '0';
                toast.style.transform = 'translate(-50%, -20px)';
                setTimeout(() => toast.remove(), 500);
            }
        }, 2000);
    </script>
    @endif

    <!-- Hero Section -->
    <section class="bg-section-gray w-full min-h-[663px] overflow-hidden relative">
        <div class="max-w-[1440px] mx-auto px-10 flex flex-col lg:flex-row items-center pt-10 relative h-full">
            <!-- Left Text Content -->
            <div class="z-10 lg:w-1/2 pt-10 pb-20">
                <h1 class="hero-title mb-6">FIND LOCAL WEAR <br> THAT MATCHES <br> YOUR STYLE</h1>
                <p class="text-gray-600 text-lg mb-8 max-w-[545px]">Browse through our diverse range of meticulously crafted garments, designed to bring out your individuality and cater to your sense of style.</p>
                <a href="#new-arrivals" class="bg-black text-white px-14 py-4 rounded-full font-semibold text-lg inline-block hover:scale-105 transition-transform mb-12">Shop Now</a>

                <!-- Hero Stats -->
                <div class="flex flex-wrap gap-x-12 gap-y-6">
                    <div class="flex flex-col">
                        <h2 class="text-4xl font-bold">200+</h2>
                        <span class="text-gray-500 text-sm">International Brands</span>
                    </div>
                    <div class="h-10 w-[1px] bg-gray-300 hidden sm:block"></div>
                    <div class="flex flex-col">
                        <h2 class="text-4xl font-bold">2,000+</h2>
                        <span class="text-gray-500 text-sm">High-Quality Products</span>
                    </div>
                    <div class="h-10 w-[1px] bg-gray-300 hidden sm:block"></div>
                    <div class="flex flex-col">
                        <h2 class="text-4xl font-bold">30,000+</h2>
                        <span class="text-gray-500 text-sm">Happy Customers</span>
                    </div>
                </div>
            </div>

            <!-- Right Image Content -->
            <div class="relative lg:w-1/2 h-full min-h-[400px] lg:min-h-[600px] w-full flex justify-center lg:justify-end">
                <!-- Repositioned Stars -->
                <img src="{{ asset('images/star-black.svg') }}" alt="" class="absolute star-large top-10 -right-5 hidden lg:block" style="width: 56px">
                <img src="{{ asset('images/star-black.svg') }}" alt="" class="absolute star-small top-[45%] left-24 hidden lg:block" style="width: 44px">

                <!-- Models Layout (Much closer and overlapping) -->
                <div class="relative h-full flex items-end justify-center lg:justify-end w-full group">
                     <!-- Erigo0 (Left/Back Model - Pushed closer to the right) -->
                     <img src="{{ asset('images/Erigo0.png') }}" class="h-auto w-[65%] lg:w-auto lg:max-h-[580px] object-contain relative z-10 -mr-[35%] lg:-mr-[220px] transition-transform hover:scale-105 duration-500" alt="Hero Model Erigo">
                     
                     <!-- Model1 (Right/Front Model) -->
                     <img src="{{ asset('images/Model1.png') }}" class="h-auto w-[75%] lg:w-auto lg:max-h-[663px] object-contain relative z-20 transition-transform hover:scale-105 duration-500" alt="Hero Model Main">
                </div>
            </div>
        </div>
    </section>

    <!-- Brands Bar with Larger Images -->
    <div class="w-full bg-black py-12 lg:py-16 overflow-hidden">
        <div class="max-w-[1440px] mx-auto px-10 flex flex-wrap justify-between items-center gap-x-12 gap-y-10">
            <!-- Brand 1: ERIGO -->
            <img src="{{ asset('images/erigologo.png') }}" class="h-8 lg:h-14 object-contain brightness-0 invert opacity-100" alt="ERIGO">
            
            <!-- Brand 2: CHMB -->
            <img src="{{ asset('images/logochmb.png') }}" class="h-10 lg:h-16 object-contain brightness-0 invert opacity-100" alt="CHMB">
            
            <!-- Brand 3: J. -->
            <img src="{{ asset('images/jlogo.png') }}" class="h-8 lg:h-14 object-contain brightness-0 invert opacity-100" alt="J.">
            
            <!-- Brand 4: WUSHCLUB -->
            <img src="{{ asset('images/Wushclublogo.png') }}" class="h-12 lg:h-20 object-contain brightness-0 invert opacity-100" alt="WUSHCLUB">
            
            <!-- Brand 5: Star Icon -->
            <img src="{{ asset('images/callelogo.png') }}" class="h-10 lg:h-16 object-contain brightness-0 invert opacity-100" alt="Brand Icon">
        </div>
    </div>

    <!-- New Arrivals -->
    <section id="new-arrivals" class="max-w-[1440px] mx-auto px-10 py-20 border-b border-gray-100 scroll-mt-20">
        <h2 class="text-5xl font-black text-center mb-14 uppercase">New Arrivals</h2>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            @foreach($newArrivals ?? [] as $product)
            <div class="product-card">
                <a href="{{ route('product.detail', $product->id) }}" class="block bg-product-gray rounded-[20px] aspect-square flex items-center justify-center p-5 mb-5 cursor-pointer hover:opacity-80 transition-opacity">
                    <img src="{{ asset('images/' . ($product->gambar ?? 'baju1.png')) }}" alt="{{ $product->nama_produk }}" class="w-full h-full object-contain mix-blend-multiply">
                </a>
                <h4 class="text-xl font-bold mb-1 truncate" title="{{ $product->nama_produk }}">{{ $product->nama_produk }}</h4>
                <div class="flex items-center gap-2 mb-1">
                    <div class="flex text-[#FFC633] gap-1 text-sm">
                        @for($i=1; $i<=5; $i++)
                            @if($i <= floor((float)($product->rating ?? 0)))
                                <i class="fa-solid fa-star"></i>
                            @elseif($i == ceil((float)($product->rating ?? 0)) && (float)($product->rating ?? 0) != floor((float)($product->rating ?? 0)))
                                <i class="fa-solid fa-star-half-stroke"></i>
                            @else
                                <i class="fa-regular fa-star"></i>
                            @endif
                        @endfor
                    </div>
                    <span class="text-sm text-gray-500">{{ $product->rating ?? '0' }}/<span class="text-gray-300">5</span></span>
                </div>
                <div class="flex justify-between items-center">
                    <p class="text-2xl font-bold">Rp{{ number_format($product->harga, 0, ',', '.') }}</p>
                    @if(($product->stok ?? 0) <= 0)
                        <span class="text-xs font-bold text-red-600 bg-red-50 px-2 py-1 rounded">Out of Stock</span>
                    @endif
                </div>
            </div>
            @endforeach
        </div>

        <div class="flex justify-center mt-12">
            <a href="#" class="px-14 py-3 border border-gray-200 rounded-full font-medium hover:bg-black hover:text-white transition-all duration-300">View All</a>
        </div>
    </section>

    <!-- Top Selling -->
    <section id="top-selling" class="max-w-[1440px] mx-auto px-10 py-20 border-b border-gray-100 scroll-mt-20">
        <h2 class="text-5xl font-black text-center mb-14 uppercase">Top Selling</h2>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            @foreach($topSelling ?? [] as $product)
            <div class="product-card">
                <a href="{{ route('product.detail', $product->id) }}" class="block bg-product-gray rounded-[20px] aspect-square flex items-center justify-center p-5 mb-5 cursor-pointer hover:opacity-80 transition-opacity">
                    <img src="{{ asset('images/' . ($product->gambar ?? 'baju1.png')) }}" alt="{{ $product->nama_produk }}" class="w-full h-full object-contain mix-blend-multiply">
                </a>
                <h4 class="text-xl font-bold mb-1 truncate" title="{{ $product->nama_produk }}">{{ $product->nama_produk }}</h4>
                <div class="flex items-center gap-2 mb-1">
                    <div class="flex text-[#FFC633] gap-1 text-sm">
                        @for($i=1; $i<=5; $i++)
                            @if($i <= floor((float)($product->rating ?? 0)))
                                <i class="fa-solid fa-star"></i>
                            @elseif($i == ceil((float)($product->rating ?? 0)) && (float)($product->rating ?? 0) != floor((float)($product->rating ?? 0)))
                                <i class="fa-solid fa-star-half-stroke"></i>
                            @else
                                <i class="fa-regular fa-star"></i>
                            @endif
                        @endfor
                    </div>
                    <span class="text-sm text-gray-500">{{ $product->rating ?? '0' }}/<span class="text-gray-300">5</span></span>
                </div>
                <div class="flex justify-between items-center">
                    <p class="text-2xl font-bold">Rp{{ number_format($product->harga, 0, ',', '.') }}</p>
                    @if(($product->stok ?? 0) <= 0)
                        <span class="text-xs font-bold text-red-600 bg-red-50 px-2 py-1 rounded">Out of Stock</span>
                    @endif
                </div>
            </div>
            @endforeach
        </div>

        <div class="flex justify-center mt-12">
            <a href="#" class="px-14 py-3 border border-gray-200 rounded-full font-medium hover:bg-black hover:text-white transition-all duration-300">View All</a>
        </div>
    </section>

    <!-- Browse Section -->
    <section id="browse" class="max-w-[1440px] mx-auto px-10 py-10 scroll-mt-20">
        <div class="bg-[#F0F0F0] rounded-[40px] px-10 lg:px-16 py-16">
            <h2 class="text-5xl font-black text-center mb-16 uppercase">Browse by dress style</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Casual (Smaller) -->
                <div class="browse-card h-[289px] flex items-center justify-start p-10 bg-white">
                    <h3 class="z-20 text-3xl font-bold">
                                Men's top</h3>
                    <img src="{{ asset('images/baju1.png') }}" class="absolute -right-5 top-0 h-full w-auto object-contain z-10 opacity-90 p-5" alt="Casual">
                </div>
                
                <!-- Formal (Larger) -->
                <div class="browse-card h-[289px] flex items-center justify-start p-10 bg-white">
                    <h3 class="z-20 text-3xl font-bold">
                        Jackets</h3>
                    <img src="{{ asset('images/Hoodie.png') }}" class="absolute -right-10 top-0 h-full w-auto object-contain z-10 opacity-90 p-10" alt="Formal">
                </div>

                <!-- Party (Larger) -->
                <div class="browse-card h-[289px] flex items-center justify-start p-10 bg-white">
                    <h3 class="z-20 text-3xl font-bold">Backpack</h3>
                    <img src="{{ asset('images/Backpack.png') }}" class="absolute -right-5 top-0 h-full w-auto object-contain z-5 opacity-90 p-12" alt="Party">
                </div>

                <!-- Gym (Smaller) -->
                <div class="browse-card h-[289px] flex items-center justify-start p-10 bg-white">
                    <h3 class="z-20 text-3xl font-bold">Women</h3>
                    <img src="{{ asset('images/Jiniso.png') }}" class="absolute -right-5 top-0 h-full w-auto object-contain z-10 opacity-90 p-8" alt="Gym">
                </div>
            </div>
        </div>
    </div>

    <!-- Happy Customers -->
    <section class="max-w-[1440px] mx-auto px-10 py-20">
        <div class="flex items-center justify-between mb-10">
            <h2 class="text-5xl font-black uppercase">Our Happy Customers</h2>
            <div class="flex gap-4">
                <button class="w-12 h-12 flex items-center justify-center rounded-full border border-gray-100"><i class="fa fa-arrow-left"></i></button>
                <button class="w-12 h-12 flex items-center justify-center rounded-full border border-gray-100"><i class="fa fa-arrow-right"></i></button>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 overflow-hidden">
            <!-- Review 1 -->
            <div class="border border-gray-100 rounded-[20px] p-8 flex flex-col gap-3">
                <div class="flex text-[#FFC633] gap-1">
                    <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-xl font-bold">Sarah M.</span>
                    <i class="fa-solid fa-circle-check text-[#01AB31]"></i>
                </div>
                <p class="text-gray-500 leading-relaxed italic">"I'm blown away by the quality and style of the clothes I received from HyperRack. From the comfort to the design, everything exceeded my expectations."</p>
            </div>

            <!-- Review 2 -->
            <div class="border border-gray-100 rounded-[20px] p-8 flex flex-col gap-3">
                <div class="flex text-[#FFC633] gap-1">
                    <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-xl font-bold">Alex K.</span>
                    <i class="fa-solid fa-circle-check text-[#01AB31]"></i>
                </div>
                <p class="text-gray-500 leading-relaxed italic">"Finding clothes that align with my personal style used to be a challenge until I discovered HyperRack. The range of options they offer is truly remarkable."</p>
            </div>

            <!-- Review 3 -->
            <div class="border border-gray-100 rounded-[20px] p-8 flex flex-col gap-3">
                <div class="flex text-[#FFC633] gap-1">
                    <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-xl font-bold">James L.</span>
                    <i class="fa-solid fa-circle-check text-[#01AB31]"></i>
                </div>
                <p class="text-gray-500 leading-relaxed italic">"As someone who's always on the lookout for unique fashion pieces, I'm thrilled to have stumbled upon HyperRack. The selection of local wear is top-notch."</p>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-[#F0F0F0] mt-40 pt-40 pb-20 relative">
        <!-- Floating Newsletter -->
        <div class="max-w-[1440px] mx-auto px-10">
            <div class="newsletter-float bg-black rounded-[20px] p-10 lg:p-14 flex flex-col lg:flex-row items-center justify-between gap-10 absolute top-0 left-10 right-10 -translate-y-1/2">
                <h2 class="text-white text-4xl lg:text-5xl font-black uppercase max-w-[550px] leading-tight">STAY UP TO DATE ABOUT OUR LATEST OFFERS</h2>
                <div class="w-full lg:max-w-[350px] flex flex-col gap-4">
                    <div class="bg-white rounded-full px-5 py-3 flex items-center gap-3">
                        <i class="fa-regular fa-envelope text-gray-400"></i>
                        <input type="text" placeholder="Enter your email address" class="bg-transparent border-none outline-none w-full text-gray-600">
                    </div>
                    <button class="bg-white text-black font-bold py-3 rounded-full hover:bg-gray-100 transition-colors">Subscribe to Newsletter</button>
                </div>
            </div>
        </div>

        <div class="max-w-[1440px] mx-auto px-10 grid grid-cols-2 lg:grid-cols-5 gap-10 lg:gap-5 mb-20">
            <div class="col-span-2 lg:col-span-1">
                <h2 class="logo-font mb-6 leading-none">HyperRack</h2>
                <p class="text-gray-500 text-sm mb-8 leading-relaxed">We have clothes that suits your style and which you're proud to wear. From women to men.</p>
                <div class="flex gap-3">
                    <a href="#" class="w-8 h-8 rounded-full bg-white border border-gray-100 flex items-center justify-center text-black active:bg-black active:text-white transition-all"><i class="fa-brands fa-twitter"></i></a>
                    <a href="#" class="w-8 h-8 rounded-full bg-black flex items-center justify-center text-white"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="#" class="w-8 h-8 rounded-full bg-white border border-gray-100 flex items-center justify-center text-black hover:bg-black hover:text-white transition-all"><i class="fa-brands fa-instagram"></i></a>
                    <a href="#" class="w-8 h-8 rounded-full bg-white border border-gray-100 flex items-center justify-center text-black hover:bg-black hover:text-white transition-all"><i class="fa-brands fa-github"></i></a>
                </div>
            </div>
            
            <div class="lg:ml-10">
                <h4 class="uppercase font-bold mb-6 tracking-widest text-sm">Company</h4>
                <ul class="flex flex-col gap-4 text-gray-500">
                    <li><a href="#" class="hover:text-black">About</a></li>
                    <li><a href="#" class="hover:text-black">Features</a></li>
                    <li><a href="#" class="hover:text-black">Works</a></li>
                    <li><a href="#" class="hover:text-black">Career</a></li>
                </ul>
            </div>

            <div>
                <h4 class="uppercase font-bold mb-6 tracking-widest text-sm">Help</h4>
                <ul class="flex flex-col gap-4 text-gray-500">
                    <li><a href="#" class="hover:text-black">Customer Support</a></li>
                    <li><a href="#" class="hover:text-black">Delivery Details</a></li>
                    <li><a href="#" class="hover:text-black">Terms & Conditions</a></li>
                    <li><a href="#" class="hover:text-black">Privacy Policy</a></li>
                </ul>
            </div>

            <div>
                <h4 class="uppercase font-bold mb-6 tracking-widest text-sm">FAQ</h4>
                <ul class="flex flex-col gap-4 text-gray-500">
                    <li><a href="#" class="hover:text-black">Account</a></li>
                    <li><a href="#" class="hover:text-black">Manage Deliveries</a></li>
                    <li><a href="#" class="hover:text-black">Orders</a></li>
                    <li><a href="#" class="hover:text-black">Payments</a></li>
                </ul>
            </div>

            <div>
                <h4 class="uppercase font-bold mb-6 tracking-widest text-sm">Resources</h4>
                <ul class="flex flex-col gap-4 text-gray-500">
                    <li><a href="#" class="hover:text-black">Free eBooks</a></li>
                    <li><a href="#" class="hover:text-black">Development Tutorial</a></li>
                    <li><a href="#" class="hover:text-black">How to - Blog</a></li>
                    <li><a href="#" class="hover:text-black">Youtube Playlist</a></li>
                </ul>
            </div>
        </div>

        <div class="max-w-[1440px] mx-auto px-10 border-t border-gray-200 pt-8 flex flex-col md:flex-row items-center justify-between gap-5">
            <p class="text-gray-500 text-sm">HyperRack © 2000-2023, All Rights Reserved</p>
            <div class="flex items-center gap-6">
                <img src="{{ asset('images/bca.png') }}" alt="BCA" class="h-10 bg-white px-3 py-1.5 rounded-lg shadow-sm border border-gray-100">
                <img src="{{ asset('images/mandiri.png') }}" alt="Mandiri" class="h-10 bg-white px-3 py-1.5 rounded-lg shadow-sm border border-gray-100">
                <img src="{{ asset('images/bri.png') }}" alt="BRI" class="h-10 bg-white px-3 py-1.5 rounded-lg shadow-sm border border-gray-100">
            </div>
        </div>
    </footer>

<script>
    function updateCartPopover() {
        let cartStr = localStorage.getItem('cartData');
        const cartItems = document.getElementById('cart-items');
        const indicator = document.getElementById('cart-indicator');
        
        if(cartStr) {
            let cart = JSON.parse(cartStr);
            if(cart.length > 0) {
                indicator.classList.remove('hidden');
                let html = '';
                cart.forEach((item, index) => {
                    html += `
                    <div class="flex items-center justify-between mb-3 border-b pb-2 text-left">
                        <div class="flex items-center gap-3">
                            <img src="${item.image}" class="w-10 h-10 object-contain rounded">
                            <div>
                                <p class="font-bold text-xs uppercase truncate w-24" title="${item.name}">${item.name}</p>
                                <p class="text-[10px] text-gray-500">Size: ${item.size} | Qty: ${item.quantity}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="font-bold text-xs">${item.subtotalFmt}</span>
                            <button type="button" onclick="removeFromCartPopover(${index}, event)" class="text-gray-400 hover:text-red-600 transition" title="Hapus dari keranjang">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </div>
                    </div>`;
                });
                cartItems.innerHTML = html;
                cartItems.classList.remove('items-center', 'justify-center', 'text-gray-400');
                cartItems.classList.add('flex-col', 'items-stretch');
            } else {
                indicator.classList.add('hidden');
                cartItems.innerHTML = 'No items in cart';
                cartItems.classList.remove('flex-col', 'items-stretch');
                cartItems.classList.add('items-center', 'justify-center', 'text-gray-400');
            }
        } else {
            indicator.classList.add('hidden');
            cartItems.innerHTML = 'No items in cart';
            cartItems.classList.remove('flex-col', 'items-stretch');
            cartItems.classList.add('items-center', 'justify-center', 'text-gray-400');
        }
    }

    document.addEventListener("DOMContentLoaded", () => {
        updateCartPopover();
    });

    function toggleCart(event) {
        event.preventDefault();
        const popover = document.getElementById('cart-popover');
        popover.classList.toggle('hidden');
    }

    document.addEventListener('click', function(e) {
        const popover = document.getElementById('cart-popover');
        const cartIcon = document.querySelector('a[onclick="toggleCart(event)"]');
        
        if(popover && !popover.classList.contains('hidden')) {
            if(!popover.contains(e.target) && (!cartIcon || !cartIcon.contains(e.target))) {
                popover.classList.add('hidden');
            }
        }
    });

    function proceedToCheckout() {
        const cartStr = localStorage.getItem('cartData');
        if (!cartStr || JSON.parse(cartStr).length === 0) {
            alert('Keranjang anda masih kosong!');
            return;
        }
        window.location.href = '{{ route('payment') }}';
    }

    function removeFromCartPopover(index, event) {
        if(event) event.stopPropagation();
        let cartStr = localStorage.getItem('cartData');
        if(cartStr) {
            let cart = JSON.parse(cartStr);
            cart.splice(index, 1);
            localStorage.setItem('cartData', JSON.stringify(cart));
            window.location.reload();
        }
    }
</script>
</html>
