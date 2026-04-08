<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment - HypeRack</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        *, *::before, *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Inter', sans-serif;
            font-size: 14px;
            color: #111;
            background: #fff;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        /* ───── NAVBAR ───── */
        .navbar {
            display: flex;
            align-items: center;
            gap: 32px;
            padding: 0 48px;
            height: 64px;
            border-bottom: 1px solid #e5e7eb;
        }

        .navbar-brand {
            font-size: 22px;
            font-weight: 900;
            letter-spacing: -0.5px;
            color: #111;
        }

        .navbar-nav {
            display: flex;
            align-items: center;
            gap: 24px;
            list-style: none;
        }

        .navbar-nav a {
            font-size: 14px;
            font-weight: 500;
            color: #333;
        }

        .navbar-nav a:hover {
            color: #111;
        }

        .nav-dropdown {
            display: flex;
            align-items: center;
            gap: 4px;
            cursor: pointer;
        }

        .nav-dropdown svg {
            width: 14px;
            height: 14px;
        }

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
            color: #374151;
            outline: none;
            background: #fff;
        }

        .navbar-search input::placeholder {
            color: #9ca3af;
        }

        .navbar-search svg {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            width: 16px;
            height: 16px;
            color: #9ca3af;
        }

        .navbar-icons {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-left: auto;
        }

        .navbar-icons svg {
            width: 22px;
            height: 22px;
            cursor: pointer;
            color: #374151;
        }

        /* ───── BREADCRUMB ───── */
        .breadcrumb {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 12px 48px;
            font-size: 13px;
            color: #6b7280;
            border-bottom: 1px solid #f3f4f6;
        }

        .breadcrumb a {
            color: #6b7280;
        }

        .breadcrumb a:hover {
            color: #111;
        }

        .breadcrumb-sep {
            color: #d1d5db;
        }

        .breadcrumb-current {
            color: #374151;
            font-weight: 500;
        }

        /* ───── MAIN CONTENT ───── */
        .main {
            flex: 1;
            padding: 40px 48px;
        }

        .payment-card {
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 32px;
            max-width: 780px;
            margin: 0 auto;
        }

        .payment-title {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 24px;
            color: #111;
        }

        .payment-info-box {
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            overflow: hidden;
        }

        /* Top bar */
        .payment-topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 16px;
            background: #f9fafb;
            border-bottom: 1px solid #e5e7eb;
            font-size: 12px;
            color: #6b7280;
        }

        .payment-topbar .shopping-label {
            display: flex;
            align-items: center;
            gap: 6px;
            font-weight: 500;
        }

        .payment-topbar .shopping-label svg {
            width: 14px;
            height: 14px;
        }

        .payment-topbar .date {
            font-size: 12px;
            color: #6b7280;
        }

        .payment-topbar .pay-before {
            font-size: 12px;
            color: #374151;
        }

        /* Payment body */
        .payment-body {
            padding: 20px 16px;
        }

        .payment-method-label {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 14px;
            color: #111;
        }

        .bank-badge {
            display: flex;
            align-items: center;
            gap: 4px;
            color: #fff;
            font-size: 10px;
            font-weight: 800;
            padding: 2px 6px;
            border-radius: 3px;
            letter-spacing: 0.5px;
        }
        .bg-bca { background: #003087; }
        .bg-bri { background: #f97316; }
        .bg-mandiri { background: #003087; }

        .payment-detail-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px 32px;
            padding-left: 2px;
        }

        .payment-detail-item label {
            display: block;
            font-size: 12px;
            color: #6b7280;
            margin-bottom: 4px;
        }

        .payment-detail-item p {
            font-size: 13px;
            font-weight: 600;
            color: #111;
        }

        /* Payment footer / action buttons */
        .payment-footer {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            padding: 12px 16px;
            border-top: 1px solid #e5e7eb;
            background: #f9fafb;
        }

        .btn {
            padding: 7px 18px;
            font-size: 12px;
            font-weight: 600;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-family: 'Inter', sans-serif;
            transition: opacity 0.15s;
        }

        .btn:hover {
            opacity: 0.85;
        }

        .btn-dark {
            background: #111;
            color: #fff;
        }

        .btn-outline {
            background: #fff;
            color: #374151;
            border: 1px solid #d1d5db;
        }

        /* ───── NEWSLETTER SECTION ───── */
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

        .newsletter-form {
            display: flex;
            flex-direction: column;
            gap: 12px;
            min-width: 280px;
        }

        .newsletter-input-wrap {
            position: relative;
        }

        .newsletter-input-wrap svg {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            width: 16px;
            height: 16px;
            color: #9ca3af;
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

        .newsletter-form input::placeholder {
            color: #6b7280;
        }

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
            transition: opacity 0.15s;
        }

        .newsletter-form button:hover {
            opacity: 0.9;
        }

        /* ───── FOOTER ───── */
        footer {
            padding: 48px 48px 24px;
            border-top: 1px solid #e5e7eb;
        }

        .footer-grid {
            display: grid;
            grid-template-columns: 220px repeat(4, 1fr);
            gap: 40px;
            margin-bottom: 40px;
        }

        .footer-brand-name {
            font-size: 20px;
            font-weight: 900;
            margin-bottom: 12px;
            letter-spacing: -0.5px;
        }

        .footer-tagline {
            font-size: 13px;
            color: #6b7280;
            line-height: 1.6;
            margin-bottom: 20px;
        }

        .footer-social {
            display: flex;
            gap: 10px;
        }

        .footer-social a {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            color: #374151;
            transition: background 0.15s;
        }

        .footer-social a:hover {
            background: #f3f4f6;
        }

        .footer-social svg {
            width: 15px;
            height: 15px;
        }

        .footer-col h4 {
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: #111;
            margin-bottom: 16px;
        }

        .footer-col ul {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .footer-col ul li a {
            font-size: 13px;
            color: #6b7280;
            transition: color 0.15s;
        }

        .footer-col ul li a:hover {
            color: #111;
        }

        .footer-bottom {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-top: 1px solid #e5e7eb;
            padding-top: 20px;
        }

        .footer-copyright {
            font-size: 12px;
            color: #9ca3af;
        }

        .footer-payments {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .payment-badge {
            border: 1px solid #e5e7eb;
            border-radius: 4px;
            padding: 4px 8px;
            font-size: 10px;
            font-weight: 700;
            color: #374151;
            letter-spacing: 0.5px;
        }

        .pb-visa   { color: #1a1f71; }
        .pb-bri    { color: #f97316; }
        .pb-gopay  { color: #00aa5b; }
        .pb-mandiri{ color: #003087; }
        .pb-bca    { color: #003087; }
    </style>
</head>
<body>

    {{-- ── NAVBAR ── --}}
    <nav class="navbar">
        <a href="{{ route('user.home') }}" class="navbar-brand">HypeRack</a>

    
        <div class="navbar-icons">
            {{-- Cart --}}
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13l-1.5 6h13M7 13L5.4 5M10 21a1 1 0 110-2 1 1 0 010 2zm7 0a1 1 0 110-2 1 1 0 010 2z"/>
            </svg>
            {{-- Profile / shamrock icon --}}
            <svg viewBox="0 0 24 24" fill="currentColor">
                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2a7.2 7.2 0 01-6-3.22c.03-1.99 4-3.08 6-3.08s5.97 1.09 6 3.08A7.2 7.2 0 0112 19.2z"/>
            </svg>
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
        <div class="payment-card">
            <h1 class="payment-title">payment successful</h1>

            <div class="payment-info-box">

                {{-- Top bar --}}
                <div class="payment-topbar" style="text-transform: capitalize;">
                    <span class="shopping-label">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                            <path stroke-linecap="round" d="M3 9h18M9 21V9"/>
                        </svg>
                        shopping
                        <span class="date">{{ $orderDate ?? date('d M Y') }}</span>
                    </span>
                    <span class="pay-before" style="text-transform: lowercase;">pay before {{ $payBefore ?? date('d M Y, H.i', strtotime('+1 day')) . ' WIB' }}</span>
                </div>

                {{-- Payment detail --}}
                <div class="payment-body">
                    <div class="payment-method-label">
                        @php
                            $payment = request()->get('payment', 'bca_va');
                            $vaNumber = '80771002399992016';
                            $bankName = 'BCA';
                            $bankLogo = asset('images/bca.png');
                            
                            if ($payment == 'bri_va') {
                                $vaNumber = '12871002399992016';
                                $bankName = 'BRI';
                                $bankLogo = asset('images/bri.png');
                            } elseif ($payment == 'mandiri_va') {
                                $vaNumber = '88701002399992016';
                                $bankName = 'Mandiri';
                                $bankLogo = asset('images/mandiri.png');
                            }
                        @endphp
                        <img src="{{ $bankLogo }}" style="height: 24px; width: auto; margin-right: 8px;" alt="{{ $bankName }}">
                        Payment Method
                    </div>

                    <div class="payment-detail-grid">
                        <div class="payment-detail-item">
                            <label>Number Virtual Account</label>
                            <p>{{ $vaNumber }}</p>
                        </div>
                        <div class="payment-detail-item">
                            <label>Total Payment</label>
                            <p id="status-total-payment">IDR {{ number_format($totalPayment ?? 0, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>

                {{-- Action buttons --}}
                <div class="payment-footer">
                    <a href="{{ route('order.detail', $orderId ?? '1') }}">
                        <button class="btn btn-dark">Order Details</button>
                    </a>
                    <a href="{{ route('user.home') }}">
                        <button class="btn btn-outline">Back</button>
                    </a>
                </div>

            </div>
        </div>
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

            {{-- Brand --}}
            <div>
                <div class="footer-brand-name">HypeRack</div>
                <p class="footer-tagline">We have clothes that suits your style and which you're proud to wear. From women to men.</p>
                <div class="footer-social">
                    {{-- Twitter --}}
                    <a href="#">
                        <svg viewBox="0 0 24 24" fill="currentColor">
                            <path d="M22.46 6c-.77.35-1.6.58-2.46.69.88-.53 1.56-1.37 1.88-2.38-.83.5-1.75.85-2.72 1.05C18.37 4.5 17.26 4 16 4c-2.35 0-4.27 1.92-4.27 4.29 0 .34.04.67.11.98C8.28 9.09 5.11 7.38 3 4.79c-.37.63-.58 1.37-.58 2.15 0 1.49.75 2.81 1.91 3.56-.71 0-1.37-.2-1.95-.5v.03c0 2.08 1.48 3.82 3.44 4.21a4.22 4.22 0 01-1.93.07 4.28 4.28 0 004 2.98 8.521 8.521 0 01-5.33 1.84c-.34 0-.68-.02-1.02-.06C3.44 20.29 5.7 21 8.12 21 16 21 20.33 14.46 20.33 8.79c0-.19 0-.37-.01-.56.84-.6 1.56-1.36 2.14-2.23z"/>
                        </svg>
                    </a>
                    {{-- Facebook --}}
                    <a href="#">
                        <svg viewBox="0 0 24 24" fill="currentColor">
                            <path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"/>
                        </svg>
                    </a>
                    {{-- Instagram --}}
                    <a href="#">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="2" y="2" width="20" height="20" rx="5" ry="5"/>
                            <circle cx="12" cy="12" r="4"/>
                            <circle cx="17.5" cy="6.5" r="0.5" fill="currentColor"/>
                        </svg>
                    </a>
                    {{-- GitHub --}}
                    <a href="#">
                        <svg viewBox="0 0 24 24" fill="currentColor">
                            <path d="M9 19c-5 1.5-5-2.5-7-3m14 6v-3.87a3.37 3.37 0 00-.94-2.61c3.14-.35 6.44-1.54 6.44-7A5.44 5.44 0 0020 4.77 5.07 5.07 0 0019.91 1S18.73.65 16 2.48a13.38 13.38 0 00-7 0C6.27.65 5.09 1 5.09 1A5.07 5.07 0 005 4.77a5.44 5.44 0 00-1.5 3.78c0 5.42 3.3 6.61 6.44 7A3.37 3.37 0 009 18.13V22"/>
                        </svg>
                    </a>
                </div>
            </div>

            {{-- Company --}}
            <div class="footer-col">
                <h4>Company</h4>
                <ul>
                    <li><a href="#">About</a></li>
                    <li><a href="#">Features</a></li>
                    <li><a href="#">Works</a></li>
                    <li><a href="#">Career</a></li>
                </ul>
            </div>

            {{-- Help --}}
            <div class="footer-col">
                <h4>Help</h4>
                <ul>
                    <li><a href="#">Customer Support</a></li>
                    <li><a href="#">Delivery Details</a></li>
                    <li><a href="#">Terms &amp; Conditions</a></li>
                    <li><a href="#">Privacy Policy</a></li>
                </ul>
            </div>

            {{-- FAQ --}}
            <div class="footer-col">
                <h4>FAQ</h4>
                <ul>
                    <li><a href="#">Account</a></li>
                    <li><a href="#">Manage Deliveries</a></li>
                    <li><a href="#">Orders</a></li>
                    <li><a href="#">Payments</a></li>
                </ul>
            </div>

            {{-- Resources --}}
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

        {{-- Footer bottom --}}
        <div class="footer-bottom">
            <p class="footer-copyright">Shop.co © 2000-2023. All Rights Reserved</p>
            <div class="footer-payments">
                <span class="payment-badge pb-visa">VISA</span>
                <span class="payment-badge pb-bri">BRI</span>
                <span class="payment-badge pb-gopay">GoPay</span>
                <span class="payment-badge pb-mandiri">mandiri</span>
                <span class="payment-badge pb-bca">BCA</span>
            </div>
        </div>
    </footer>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            // Data sudah dimuat secara dinamis dari Controller melalui (compact)
        });
    </script>
</body>
</html>
