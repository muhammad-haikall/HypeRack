<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment - HypeRack</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html { scroll-behavior: smooth; }
        body {
            font-family: 'Inter', sans-serif;
            font-size: 14px;
            color: #111;
            background: #fff;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        a { text-decoration: none; color: inherit; }

        /* ── NAVBAR ── */
        .navbar {
            display: flex;
            align-items: center;
            gap: 32px;
            padding: 0 48px;
            height: 64px;
            border-bottom: 1px solid #e5e7eb;
        }

        .navbar-brand { font-size: 22px; font-weight: 900; letter-spacing: -0.5px; }

        .navbar-nav { display: flex; align-items: center; gap: 24px; list-style: none; }
        .navbar-nav a { font-size: 14px; font-weight: 500; color: #333; }
        .navbar-nav a:hover { color: #111; }

        .nav-dropdown { display: flex; align-items: center; gap: 4px; cursor: pointer; }
        .nav-dropdown svg { width: 14px; height: 14px; }

        .navbar-search {
            flex: 1;
            max-width: 380px;
            position: relative;
        }
        .navbar-search input {
            width: 100%;
            padding: 8px 16px 8px 40px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 14px;
            font-family: 'Inter', sans-serif;
            outline: none;
        }
        .navbar-search input::placeholder { color: #9ca3af; }
        .navbar-search svg {
            position: absolute; left: 12px; top: 50%;
            transform: translateY(-50%); width: 16px; height: 16px; color: #9ca3af;
        }

        .navbar-icons { display: flex; align-items: center; gap: 16px; margin-left: auto; }
        .navbar-icons svg { width: 22px; height: 22px; cursor: pointer; color: #374151; }

        /* Profil Dropdown Dashboard Style */
        .profile-container { position: relative; display: flex; align-items: center; }
        .profile-bubble { 
            width: 32px; height: 32px; background: #000; color: #fff; 
            border-radius: 50%; display: flex; align-items: center; justify-content: center; 
            font-weight: 700; font-size: 13px; cursor: pointer; text-transform: uppercase; 
        }
        .profile-dropdown { 
            position: absolute; right: 0; top: 100%; margin-top: 10px; width: 130px; 
            background: #fff; border: 1px solid #e5e7eb; border-radius: 8px; 
            box-shadow: 0 10px 25px rgba(0,0,0,0.08); opacity: 0; visibility: hidden; 
            transition: all 0.2s ease; z-index: 1000; overflow: hidden;
        }
        .profile-container:hover .profile-dropdown { opacity: 1; visibility: visible; transform: translateY(0); }
        .btn-logout-user { 
            width: 100%; border: none; background: none; padding: 12px 16px; 
            text-align: left; color: #ef4444; font-size: 13px; font-weight: 500; 
            cursor: pointer; transition: background 0.2s; 
        }
        .btn-logout-user:hover { background: #fef2f2; }

        /* ── BREADCRUMB ── */
        .breadcrumb {
            display: flex; align-items: center; gap: 8px;
            padding: 12px 48px;
            font-size: 13px; color: #6b7280;
            border-bottom: 1px solid #f3f4f6;
        }
        .breadcrumb a { color: #6b7280; }
        .breadcrumb a:hover { color: #111; }
        .breadcrumb-sep { color: #d1d5db; }
        .breadcrumb-current { color: #374151; font-weight: 500; }

        /* ── MAIN ── */
        .main { flex: 1; padding: 32px 48px 48px; }

        /* Greeting */
        .greeting-name {
            font-size: 32px;
            font-weight: 800;
            letter-spacing: -0.5px;
            margin-bottom: 6px;
        }
        .greeting-sub {
            font-size: 13px;
            color: #6b7280;
            line-height: 1.6;
            margin-bottom: 24px;
        }

        .divider { border: none; border-top: 1px solid #e5e7eb; margin-bottom: 24px; }

        /* Address row */
        .address-row {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 16px;
            margin-bottom: 28px;
        }
        .address-left { display: flex; gap: 10px; }
        .address-left svg { width: 18px; height: 18px; color: #374151; flex-shrink: 0; margin-top: 2px; }
        .address-label { font-size: 12px; font-weight: 600; color: #374151; margin-bottom: 4px; }
        .address-text { font-size: 13px; color: #6b7280; line-height: 1.6; }

        .btn-edit-address {
            padding: 10px 24px;
            background: #111;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            font-family: 'Inter', sans-serif;
            white-space: nowrap;
            flex-shrink: 0;
            transition: opacity 0.15s;
        }
        .btn-edit-address:hover { opacity: 0.85; }

        /* Status tabs */
        .status-tabs {
            display: flex;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            overflow: hidden;
            max-width: 480px;
            margin: 0 auto 28px;
        }
        .status-tab {
            flex: 1;
            padding: 10px 0;
            text-align: center;
            font-size: 13px;
            font-weight: 500;
            color: #6b7280;
            background: #fff;
            cursor: default;
        }
        .status-tab.active {
            background: #111;
            color: #fff;
            font-weight: 600;
            border-radius: 6px;
        }

        /* Brand row */
        .brand-row {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 12px;
        }
        .brand-row svg { width: 16px; height: 16px; }

        /* Product card */
        .product-card {
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 16px;
            display: flex;
            gap: 16px;
            align-items: flex-start;
            max-width: 480px;
            margin-bottom: 16px;
        }
        .product-thumb {
            width: 80px;
            height: 80px;
            border-radius: 6px;
            object-fit: cover;
            background: #f3f4f6;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }
        .product-thumb img { width: 100%; height: 100%; object-fit: cover; }
        .product-thumb-placeholder {
            width: 80px;
            height: 80px;
            border-radius: 6px;
            background: #e5e7eb;
            flex-shrink: 0;
        }
        .product-info { flex: 1; }
        .product-name {
            font-size: 13px;
            font-weight: 700;
            color: #111;
            line-height: 1.4;
            margin-bottom: 6px;
            text-transform: uppercase;
        }
        .product-meta {
            font-size: 12px;
            color: #6b7280;
            margin-bottom: 4px;
        }
        .product-price {
            font-size: 16px;
            font-weight: 700;
            color: #111;
            margin-top: 8px;
        }

        /* Status info */
        .status-info {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            color: #6b7280;
            margin-bottom: 40px;
        }
        .status-info svg { width: 16px; height: 16px; flex-shrink: 0; }

        /* Back button area */
        .back-row {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 12px;
            margin-top: 32px;
        }
        .btn-back {
            padding: 10px 28px;
            background: #fff;
            color: #374151;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            font-family: 'Inter', sans-serif;
            transition: background 0.15s;
            width: 140px;
            text-align: center;
        }
        .btn-back:hover { background: #f3f4f6; }

        .btn-cancel {
            padding: 10px 28px;
            background: #ef4444;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            font-family: 'Inter', sans-serif;
            transition: background 0.15s;
            width: 140px;
            text-align: center;
        }
        .btn-cancel:hover { background: #dc2626; }
        
        .btn-received {
            padding: 10px 28px;
            background: #111;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            font-family: 'Inter', sans-serif;
            transition: background 0.15s;
            width: 170px;
            text-align: center;
        }
        .btn-received:hover { background: #333; }

        /* ── NEWSLETTER ── */
        .newsletter {
            background: #111;
            color: #fff;
            padding: 56px 48px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 32px;
            flex-wrap: wrap;
        }
        .newsletter-title {
            font-size: 28px;
            font-weight: 900;
            line-height: 1.2;
            letter-spacing: -0.5px;
            text-transform: uppercase;
            max-width: 360px;
        }
        .newsletter-form { display: flex; flex-direction: column; gap: 12px; min-width: 280px; }
        .newsletter-input-wrap { position: relative; }
        .newsletter-input-wrap svg {
            position: absolute; left: 14px; top: 50%;
            transform: translateY(-50%); width: 16px; height: 16px; color: #9ca3af;
        }
        .newsletter-form input {
            width: 100%;
            padding: 12px 16px 12px 42px;
            border: 1px solid #374151;
            border-radius: 8px;
            background: #1f2937;
            color: #fff;
            font-size: 14px;
            font-family: 'Inter', sans-serif;
            outline: none;
        }
        .newsletter-form input::placeholder { color: #6b7280; }
        .newsletter-form button {
            padding: 12px;
            background: #fff;
            color: #111;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            font-family: 'Inter', sans-serif;
        }
        .newsletter-form button:hover { opacity: 0.9; }

        /* ── FOOTER ── */
        footer { padding: 48px 48px 24px; border-top: 1px solid #e5e7eb; }

        .footer-grid {
            display: grid;
            grid-template-columns: 220px repeat(4, 1fr);
            gap: 40px;
            margin-bottom: 40px;
        }
        .footer-brand-name { font-size: 20px; font-weight: 900; margin-bottom: 12px; letter-spacing: -0.5px; text-transform: uppercase; }
        .footer-tagline { font-size: 13px; color: #6b7280; line-height: 1.6; margin-bottom: 20px; }

        .footer-social { display: flex; gap: 10px; }
        .footer-social a {
            display: flex; align-items: center; justify-content: center;
            width: 32px; height: 32px;
            border: 1px solid #e5e7eb; border-radius: 6px;
            color: #374151; transition: background 0.15s;
        }
        .footer-social a:hover { background: #f3f4f6; }
        .footer-social svg { width: 15px; height: 15px; }

        .footer-col h4 {
            font-size: 12px; font-weight: 700;
            letter-spacing: 1px; text-transform: uppercase;
            color: #111; margin-bottom: 16px;
        }
        .footer-col ul { list-style: none; display: flex; flex-direction: column; gap: 10px; }
        .footer-col ul li a { font-size: 13px; color: #6b7280; transition: color 0.15s; }
        .footer-col ul li a:hover { color: #111; }

        .footer-bottom {
            display: flex; justify-content: space-between; align-items: center;
            border-top: 1px solid #e5e7eb; padding-top: 20px;
        }
        .footer-copyright { font-size: 12px; color: #9ca3af; }
        .footer-payments { display: flex; align-items: center; gap: 8px; }
        .payment-badge {
            border: 1px solid #e5e7eb; border-radius: 4px;
            padding: 4px 8px; font-size: 10px; font-weight: 700; color: #374151;
        }
        .pb-visa { color: #1a1f71; }
        .pb-bri  { color: #f97316; }
        .pb-gopay{ color: #00aa5b; }
        .pb-mandiri { color: #003087; }
        .pb-bca  { color: #003087; }
    </style>
</head>
<body>

{{--
    $customerName   : string   — nama customer, e.g. 'Haikal'
    $orderStatus    : string   — 'dikemas' | 'dikirim' | 'selesai'
    $address        : string|null — alamat lengkap (null = kosong/default)
    $products       : array    — [['name'=>'...','size'=>'XL','color'=>'White','price'=>57000,'image'=>null,'brand'=>'Wush Club'], ...]
--}}

@php
    $statusList = ['dikemas', 'diantar', 'selesai'];
    $statusLabels = [
        'dikemas' => 'Packed',
        'diantar' => 'Shipped',
        'selesai' => 'Delivered',
        'dibatalkan' => 'Cancelled'
    ];

    $statusInfo = [
        'dikemas' => [
            'icon'    => 'box',
            'message' => 'Orders are being packed',
        ],
        'diantar' => [
            'icon'    => 'truck',
            'message' => 'your package is being delivered',
        ],
        'selesai' => [
            'icon'    => 'location',
            'message' => 'The order has arrived at the destination address, please give your rating.',
        ],
        'dibatalkan' => [
            'icon'    => 'x-circle',
            'message' => 'This order has been cancelled.',
        ],
    ];

    $currentStatus  = $orderStatus ?? 'dikemas';
    $currentInfo    = $statusInfo[$currentStatus] ?? $statusInfo['dikemas'];
@endphp

    {{-- ── NAVBAR ── --}}
    <nav class="navbar">
        <a href="{{ route('user.home') }}" class="navbar-brand">HypeRack</a>

        <ul class="navbar-nav">
            <li>
                <span class="nav-dropdown">
                    Shop
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                    </svg>
                </span>
            </li>
            <li><a href="{{ route('user.home') }}#top-selling">On Sale</a></li>
            <li><a href="{{ route('user.home') }}#new-arrivals">New Arrivals</a></li>
            <li><a href="{{ route('user.home') }}#browse">Brands</a></li>
        </ul>


        <div class="navbar-icons">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13l-1.5 6h13M7 13L5.4 5M10 21a1 1 0 110-2 1 1 0 010 2zm7 0a1 1 0 110-2 1 1 0 010 2z"/>
            </svg>
            
            @if(Session::has('user_login'))
                <div class="profile-container">
                    <div class="profile-bubble">
                        {{ strtoupper(substr(Session::get('user_name', 'U'), 0, 1)) }}
                    </div>
                    <div class="profile-dropdown">
                        <form action="{{ route('user.logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn-logout-user">Logout</button>
                        </form>
                    </div>
                </div>
            @else
                <a href="{{ route('login') }}">
                    <svg viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2a7.2 7.2 0 01-6-3.22c.03-1.99 4-3.08 6-3.08s5.97 1.09 6 3.08A7.2 7.2 0 0112 19.2z"/>
                    </svg>
                </a>
            @endif
        </div>
    </nav>

    {{-- ── BREADCRUMB ── --}}
    <div class="breadcrumb">
        <a href="{{ route('user.home') }}">Home</a>
        <span class="breadcrumb-sep">›</span>
        <span class="breadcrumb-current">Payment</span>
    </div>

    {{-- ── MAIN ── --}}
    <main class="main">

        {{-- Greeting --}}
        <h1 class="greeting-name">Dear, {{ $customerName ?? 'Customer' }}</h1>
        <p class="greeting-sub">
            Your order will be delivered soon,<br>
            please wait until the specified time,<br>
            thank you for shopping at HypeRack.
        </p>

        <hr class="divider">

        {{-- Address --}}
        <div class="address-row">
            <div class="address-left">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <div>
                    <p class="address-label">Your Addres</p>
                    <p class="address-text">
                        @if(!empty($address))
                            {!! str_replace('(Note:', '<br>Note :', str_replace(')', '', $address)) !!}
                        @else
                            No address information available.
                        @endif
                    </p>
                </div>
            </div>
            {{-- Tombol Edit Dihapus --}}
        </div>

        {{-- Status Tabs --}}
        <div class="status-tabs">
            @foreach($statusList as $s)
                <div class="status-tab {{ $currentStatus === $s ? 'active' : '' }}" 
                     onclick="checkStatus('{{ $s }}', '{{ $currentStatus }}')"
                     style="cursor: pointer;">
                    {{ $statusLabels[$s] ?? ucfirst($s) }}
                </div>
            @endforeach
        </div>

        {{-- Products by brand --}}
        @php
            // group by brand
            $grouped = [];
            foreach ($products as $p) {
                $grouped[$p['brand'] ?? 'Brand'][] = $p;
            }
        @endphp

        @foreach($grouped as $brand => $items)
            <div class="brand-row">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/>
                    <rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/>
                </svg>
                {{ $brand }}
            </div>

            @foreach($items as $product)
                <div class="product-card">
                    @if(!empty($product['image']))
                        <div class="product-thumb">
                            <img src="{{ asset($product['image']) }}" alt="{{ $product['name'] }}">
                        </div>
                    @else
                        <div class="product-thumb-placeholder"></div>
                    @endif

                    <div class="product-info">
                        <p class="product-name">{{ $product['name'] }}</p>
                        <p class="product-meta">Size: {{ $product['size'] ?? '-' }}</p>
                        <p class="product-meta">Color: {{ $product['color'] ?? '-' }}</p>
                        <p class="product-price">Rp{{ number_format($product['price'] ?? 0, 0, ',', '.') }}</p>
                    </div>
                </div>
            @endforeach
        @endforeach

        {{-- Status info message --}}
        <div class="status-info" id="status-message-container">
            @if($currentInfo['icon'] === 'box')
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 10V11"/>
                </svg>
            @elseif($currentInfo['icon'] === 'truck')
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0H3m18 0h-2"/>
                </svg>
            @elseif($currentInfo['icon'] === 'x-circle')
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            @else
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            @endif
            <span id="status-text">{{ $currentInfo['message'] }}</span>
        </div>

        {{-- Back button --}}
        <div class="back-row">
            @if($currentStatus === 'dikemas')
                <button type="button" onclick="openCancelModal()" class="btn-cancel">Cancel Order</button>
                <form id="cancel-order-form" action="{{ route('order.cancel', $orderId) }}" method="POST" style="display: none;">
                    @csrf
                </form>
            @endif
            @if($currentStatus === 'diantar')
                <button type="button" onclick="openReceiveModal()" class="btn-received">Pesanan Diterima</button>
                <form id="receive-order-form" action="{{ route('order.received', $orderId) }}" method="POST" style="display: none;">
                    @csrf
                </form>
            @endif
            <a href="{{ route('user.home') }}">
                <button class="btn-back">Back</button>
            </a>
        </div>

    </main>

    <!-- Cancel Confirmation Modal -->
    <div id="cancel-modal" style="position: fixed; inset: 0; background: rgba(0,0,0,0.4); z-index: 2000; display: none; align-items: center; justify-content: center; padding: 16px;">
        <div style="background: white; border-radius: 16px; width: 100%; max-width: 320px; padding: 28px; text-align: center; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);">
            <div style="width: 64px; height: 64px; background: #fee2e2; border-radius: 9999px; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px;">
                <svg style="width: 32px; height: 32px; color: #ef4444;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <h3 style="font-size: 20px; font-weight: 700; color: #111; margin-bottom: 8px;">Batalkan Pesanan?</h3>
            <p style="font-size: 12px; color: #6b7280; margin-bottom: 24px; line-height: 1.6;">Anda yakin ingin membatalkan pesanan ini? Tindakan ini tidak dapat dibatalkan.</p>
            <div style="display: flex; gap: 12px;">
                <button type="button" onclick="closeCancelModal()" style="flex: 1; padding: 12px; background: #f3f4f6; border: none; border-radius: 12px; font-size: 11px; font-weight: 700; color: #4b5563; cursor: pointer;">Kembali</button>
                <button type="button" onclick="document.getElementById('cancel-order-form').submit()" style="flex: 1; padding: 12px; background: #ef4444; border: none; border-radius: 12px; font-size: 11px; font-weight: 700; color: #fff; cursor: pointer; box-shadow: 0 4px 14px 0 rgba(239, 68, 68, 0.3);">Ya, Batalkan</button>
            </div>
        </div>
    </div>

    <!-- Receive Confirmation Modal -->
    <div id="receive-modal" style="position: fixed; inset: 0; background: rgba(0,0,0,0.4); z-index: 2000; display: none; align-items: center; justify-content: center; padding: 16px;">
        <div style="background: white; border-radius: 16px; width: 100%; max-width: 320px; padding: 28px; text-align: center; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);">
            <div style="width: 64px; height: 64px; background: #ecfdf5; border-radius: 9999px; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px;">
                <svg style="width: 32px; height: 32px; color: #10b981;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <h3 style="font-size: 20px; font-weight: 700; color: #111; margin-bottom: 8px;">Pesanan Diterima?</h3>
            <p style="font-size: 12px; color: #6b7280; margin-bottom: 24px; line-height: 1.6;">Apakah paket produk ini sudah benar-benar Anda terima dengan baik?</p>
            <div style="display: flex; gap: 12px;">
                <button type="button" onclick="closeReceiveModal()" style="flex: 1; padding: 12px; background: #f3f4f6; border: none; border-radius: 12px; font-size: 11px; font-weight: 700; color: #4b5563; cursor: pointer;">Belum</button>
                <button type="button" onclick="document.getElementById('receive-order-form').submit()" style="flex: 1; padding: 12px; background: #111; border: none; border-radius: 12px; font-size: 11px; font-weight: 700; color: #fff; cursor: pointer; box-shadow: 0 4px 14px 0 rgba(0, 0, 0, 0.2);">Ya, Sudah</button>
            </div>
        </div>
    </div>

    <script>
        function openCancelModal() {
            document.getElementById('cancel-modal').style.display = 'flex';
        }

        function closeCancelModal() {
            document.getElementById('cancel-modal').style.display = 'none';
        }

        function openReceiveModal() {
            document.getElementById('receive-modal').style.display = 'flex';
        }

        function closeReceiveModal() {
            document.getElementById('receive-modal').style.display = 'none';
        }

        function checkStatus(clickedStatus, currentStatus) {
            const statusText = document.getElementById('status-text');
            
            // Jika status sekarang masih 'dikemas' tapi user klik diantar/selesai
            if (currentStatus === 'dikemas' && (clickedStatus === 'diantar' || clickedStatus === 'selesai')) {
                statusText.innerText = "your package is being packed";
                statusText.style.color = "red";
                setTimeout(() => { statusText.style.color = ""; }, 2000);
            } 
            // Jika status sekarang sudah diantar tapi user klik selesai
            else if (currentStatus === 'diantar' && clickedStatus === 'selesai') {
                statusText.innerText = "The order is on its way, please wait.";
                statusText.style.color = "#6b7280";
            }
            // Jika diklik status yang memang sedang aktif, kembalikan pesan asli
            else if (clickedStatus === currentStatus) {
                const originalMessages = {
                    'dikemas': 'Orders are being packed',
                    'diantar': 'your package is being delivered',
                    'selesai': 'The order has arrived at the destination address, please give your rating.'
                };
                statusText.innerText = originalMessages[currentStatus];
                statusText.style.color = "";
            }
        }
    </script>

    </main>

    {{-- ── NEWSLETTER ── --}}
    <section class="newsletter">
        <h2 class="newsletter-title">Stay upto date about our latest offers</h2>
        <div class="newsletter-form">
            <div class="newsletter-input-wrap">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                <input type="email" placeholder="Enter your email address">
            </div>
            <button type="button">Subscribe to Newsletter</button>
        </div>
    </section>

    {{-- ── FOOTER ── --}}
    <footer>
        <div class="footer-grid">

            <div>
                <div class="footer-brand-name">Shop.co</div>
                <p class="footer-tagline">We have clothes that suits your style and which you're proud to wear. From women to men.</p>
                <div class="footer-social">
                    <a href="#">
                        <svg viewBox="0 0 24 24" fill="currentColor">
                            <path d="M22.46 6c-.77.35-1.6.58-2.46.69.88-.53 1.56-1.37 1.88-2.38-.83.5-1.75.85-2.72 1.05C18.37 4.5 17.26 4 16 4c-2.35 0-4.27 1.92-4.27 4.29 0 .34.04.67.11.98C8.28 9.09 5.11 7.38 3 4.79c-.37.63-.58 1.37-.58 2.15 0 1.49.75 2.81 1.91 3.56-.71 0-1.37-.2-1.95-.5v.03c0 2.08 1.48 3.82 3.44 4.21a4.22 4.22 0 01-1.93.07 4.28 4.28 0 004 2.98 8.521 8.521 0 01-5.33 1.84c-.34 0-.68-.02-1.02-.06C3.44 20.29 5.7 21 8.12 21 16 21 20.33 14.46 20.33 8.79c0-.19 0-.37-.01-.56.84-.6 1.56-1.36 2.14-2.23z"/>
                        </svg>
                    </a>
                    <a href="#">
                        <svg viewBox="0 0 24 24" fill="currentColor">
                            <path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"/>
                        </svg>
                    </a>
                    <a href="#">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="2" y="2" width="20" height="20" rx="5" ry="5"/>
                            <circle cx="12" cy="12" r="4"/>
                            <circle cx="17.5" cy="6.5" r="0.5" fill="currentColor"/>
                        </svg>
                    </a>
                    <a href="#">
                        <svg viewBox="0 0 24 24" fill="currentColor">
                            <path d="M9 19c-5 1.5-5-2.5-7-3m14 6v-3.87a3.37 3.37 0 00-.94-2.61c3.14-.35 6.44-1.54 6.44-7A5.44 5.44 0 0020 4.77 5.07 5.07 0 0019.91 1S18.73.65 16 2.48a13.38 13.38 0 00-7 0C6.27.65 5.09 1 5.09 1A5.07 5.07 0 005 4.77a5.44 5.44 0 00-1.5 3.78c0 5.42 3.3 6.61 6.44 7A3.37 3.37 0 009 18.13V22"/>
                        </svg>
                    </a>
                </div>
            </div>

            <div class="footer-col">
                <h4>Company</h4>
                <ul>
                    <li><a href="#">About</a></li>
                    <li><a href="#">Features</a></li>
                    <li><a href="#">Works</a></li>
                    <li><a href="#">Career</a></li>
                </ul>
            </div>

            <div class="footer-col">
                <h4>Help</h4>
                <ul>
                    <li><a href="#">Customer Support</a></li>
                    <li><a href="#">Delivery Details</a></li>
                    <li><a href="#">Terms &amp; Conditions</a></li>
                    <li><a href="#">Privacy Policy</a></li>
                </ul>
            </div>

            <div class="footer-col">
                <h4>FAQ</h4>
                <ul>
                    <li><a href="#">Account</a></li>
                    <li><a href="#">Manage Deliveries</a></li>
                    <li><a href="#">Orders</a></li>
                    <li><a href="#">Payments</a></li>
                </ul>
            </div>

            <div class="footer-col">
                <h4>Resources</h4>
                <ul>
                    <li><a href="#">Free eBooks</a></li>
                    <li><a href="#">Development Tutorial</a></li>
                    <li><a href="#">How to - Blog</a></li>
                    <li><a href="#">Youtube Playlist</a></li>
                </ul>
            </div>

        </div>

        <div class="footer-bottom">
            <p class="footer-copyright">Shop.co © 2000-2023. All Rights Reserved</p>
            <div class="footer-payments" style="display: flex; align-items: center; gap: 20px;">
                <img src="{{ asset('images/bri.png') }}" alt="BRI" style="height: 32px; object-fit: contain; background: white; padding: 4px 8px; border-radius: 6px; border: 1px solid #eee;">
                <img src="{{ asset('images/mandiri.png') }}" alt="Mandiri" style="height: 32px; object-fit: contain; background: white; padding: 4px 8px; border-radius: 6px; border: 1px solid #eee;">
                <img src="{{ asset('images/bca.png') }}" alt="BCA" style="height: 32px; object-fit: contain; background: white; padding: 4px 8px; border-radius: 6px; border: 1px solid #eee;">
            </div>
        </div>
    </footer>

</body>
</html>
