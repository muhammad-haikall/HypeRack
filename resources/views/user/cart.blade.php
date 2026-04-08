<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HyperRack - Your Cart</title>
    <!-- Google Fonts: Outfit for headers, Inter for normal text -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800;900&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
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
            body { @apply font-inter bg-white text-black; }
            h1, h2, h3, h4 { @apply font-outfit; }
        }
    </style>
</head>
<body class="bg-white text-black">

    <nav class="flex items-center justify-between px-10 py-5 border-b">
        <a href="{{ route('user.home') }}" class="font-outfit font-extrabold text-[2rem] tracking-tighter hover:opacity-80 transition-opacity text-black">HyperRack</a>
        <div class="hidden md:flex space-x-8 font-medium">
            <a href="#" class="hover:text-gray-500">Shop <i class="fa-solid fa-chevron-down text-xs"></i></a>
            <a href="{{ route('user.home') }}#top-selling" class="hover:text-gray-500">On Sale</a>
            <a href="{{ route('user.home') }}#new-arrivals" class="hover:text-gray-500">New Arrivals</a>
            <a href="{{ route('user.home') }}#browse" class="hover:text-gray-500">Brands</a>
        </div>
        <div class="flex items-center space-x-5">
            {{-- Order History Icon --}}
            <a href="{{ route('order.history') }}" class="text-xl hover:opacity-80 transition-opacity" title="My Orders">
                <i class="fa-solid fa-truck-fast"></i>
            </a>

            <div class="relative flex items-center">
                <a href="#" class="text-xl relative block" onclick="toggleCart(event)">
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
    </nav>

    <div class="max-w-6xl mx-auto px-5 mt-8 text-sm text-gray-500 font-inter flex items-center gap-2">
        <a href="{{ route('user.home') }}" class="hover:text-black hover:underline transition-colors">Home</a> 
        <i class="fa-solid fa-chevron-right text-[10px]"></i> 
        <a href="{{ route('product.detail', ['id' => 1]) }}" class="hover:text-black hover:underline transition-colors">T-shirts</a> 
        <i class="fa-solid fa-chevron-right text-[10px]"></i> 
        <span class="text-black font-medium">Cart</span>
    </div>

    <main class="max-w-6xl mx-auto px-5 mt-5 mb-20">
        <h1 class="text-4xl font-black mb-8 italic">YOUR CART</h1>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
            
            <div class="lg:col-span-2 border rounded-2xl p-6 space-y-6" id="cart-container">
                <!-- Cart items will be loaded via script -->
                </div>

            <div class="border rounded-2xl p-6">
                <h2 class="text-xl font-bold mb-6">Order Summary</h2>
                
                <div class="space-y-4 mb-6">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Subtotal</span>
                        <span class="font-bold" id="summary-subtotal">Rp0</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Delivery Fee</span>
                        <span class="font-bold" id="summary-delivery">Rp10.000</span>
                    </div>
                    <hr>
                    <div class="flex justify-between text-lg">
                        <span>Total</span>
                        <span class="font-bold" id="summary-total">Rp10.000</span>
                    </div>
                </div>

                <div class="flex space-x-2 mb-4">
                    <div class="relative flex-1">
                        <input type="text" placeholder="Add promo code" class="w-full bg-gray-100 rounded-full py-2 px-4 focus:outline-none text-sm">
                    </div>
                    <button class="bg-black text-white px-6 py-2 rounded-full font-bold text-sm">Apply</button>
                </div>

                <a href="javascript:void(0)" onclick="proceedToCheckout()" class="w-full bg-black text-white py-4 rounded-full font-bold flex items-center justify-center space-x-2 hover:bg-gray-800 transition">
                    <span>Go to Checkout</span>
                    <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>

        </div>
    </main>

    <div class="max-w-6xl mx-auto bg-black text-white p-10 rounded-3xl flex flex-col md:flex-row items-center justify-between relative z-10 translate-y-1/2">
        <h2 class="text-4xl font-black w-full md:w-1/2 leading-tight uppercase">STAY UPTO DATE ABOUT OUR LATEST OFFERS</h2>
        <div class="w-full md:w-1/3 space-y-3 mt-5 md:mt-0">
            <div class="relative">
                <input type="text" placeholder="Enter your email address" class="w-full py-3 px-12 rounded-full text-black focus:outline-none">
                <i class="fa-regular fa-envelope absolute left-4 top-4 text-gray-400"></i>
            </div>
            <button class="w-full py-3 bg-white text-black font-bold rounded-full">Subscribe to Newsletter</button>
        </div>
    </div>

    <footer class="bg-gray-100 pt-32 pb-10 px-10">
        <div class="max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-5 gap-10 border-b pb-10">
            <div class="col-span-1">
                <h3 class="text-2xl font-black mb-4 italic uppercase">Shop.co</h3>
                <p class="text-gray-500 text-sm mb-5 leading-relaxed">We have clothes that suits your style and which you're proud to wear. From women to men.</p>
                <div class="flex space-x-3">
                    <i class="fa-brands fa-twitter p-2 bg-white rounded-full border cursor-pointer"></i>
                    <i class="fa-brands fa-facebook p-2 bg-black text-white rounded-full cursor-pointer"></i>
                    <i class="fa-brands fa-instagram p-2 bg-white rounded-full border cursor-pointer"></i>
                    <i class="fa-brands fa-github p-2 bg-white rounded-full border cursor-pointer"></i>
                </div>
            </div>
            <div>
                <h4 class="font-bold mb-4 tracking-widest uppercase text-sm">Company</h4>
                <ul class="text-gray-500 space-y-2 text-sm">
                    <li>About</li><li>Features</li><li>Works</li><li>Career</li>
                </ul>
            </div>
            <div>
                <h4 class="font-bold mb-4 tracking-widest uppercase text-sm">Help</h4>
                <ul class="text-gray-500 space-y-2 text-sm">
                    <li>Customer Support</li><li>Delivery Details</li><li>Terms & Conditions</li><li>Privacy Policy</li>
                </ul>
            </div>
            <div>
                <h4 class="font-bold mb-4 tracking-widest uppercase text-sm">FAQ</h4>
                <ul class="text-gray-500 space-y-2 text-sm">
                    <li>Account</li><li>Manage Deliveries</li><li>Orders</li><li>Payments</li>
                </ul>
            </div>
            <div>
                <h4 class="font-bold mb-4 tracking-widest uppercase text-sm">Resources</h4>
                <ul class="text-gray-500 space-y-2 text-sm">
                    <li>Free eBooks</li><li>Development Tutorial</li><li>How to - Blog</li><li>Youtube Playlist</li>
                </ul>
            </div>
        </div>
        <div class="max-w-6xl mx-auto flex flex-col md:flex-row justify-between items-center mt-5 text-sm text-gray-500">
            <p>Shop.co © 2000-2023, All Rights Reserved</p>
            <div class="flex space-x-2 mt-3 md:mt-0">
                <img src="https://upload.wikimedia.org/wikipedia/commons/5/5e/Visa_Inc._logo.svg" class="h-6 opacity-80" alt="Visa">
                </div>
        </div>
    </footer>

</body>
<script>
    document.addEventListener("DOMContentLoaded", () => {
        renderCart();
    });

    function renderCart() {
        const cartStr = localStorage.getItem('cartData');
        const cartContainer = document.getElementById('cart-container');
        const summarySubtotal = document.getElementById('summary-subtotal');
        const summaryDelivery = document.getElementById('summary-delivery');
        const summaryTotal = document.getElementById('summary-total');

        if (!cartStr) {
            cartContainer.innerHTML = '<p class="text-center text-gray-500 py-10 font-bold">Your cart is currently empty.</p>';
            summarySubtotal.innerText = 'Rp0';
            summaryDelivery.innerText = 'Rp0';
            summaryTotal.innerText = 'Rp0';
            document.getElementById('cart-indicator').classList.add('hidden');
            return;
        }
        
        let cart = JSON.parse(cartStr);
        if (cart.length === 0) {
            cartContainer.innerHTML = '<p class="text-center text-gray-500 py-10 font-bold">Your cart is currently empty.</p>';
            summarySubtotal.innerText = 'Rp0';
            summaryDelivery.innerText = 'Rp0';
            summaryTotal.innerText = 'Rp0';
            document.getElementById('cart-indicator').classList.add('hidden');
            return;
        } else {
            document.getElementById('cart-indicator').classList.remove('hidden');
        }

        let html = '';
        let total = 0;
        cart.forEach((item, index) => {
            total += item.subtotalNum;
            html += `
                <div class="flex items-center space-x-4 border-b pb-6 mb-6">
                    <div class="bg-[#F0EEED] rounded-lg p-2 w-24 h-24 flex items-center justify-center flex-shrink-0">
                        <img src="${item.image}" alt="Product" class="w-full h-full object-contain mix-blend-multiply">
                    </div>
                    <div class="flex-1">
                        <div class="flex justify-between items-start">
                            <h3 class="font-bold text-sm md:text-base leading-tight w-3/4 uppercase">${item.name}</h3>
                            <button onclick="removeItem(${index})" class="text-red-500 hover:text-red-700 transition"><i class="fa-solid fa-trash-can text-lg"></i></button>
                        </div>
                        <p class="text-xs mt-1">Size: <span class="text-gray-500">${item.size}</span></p>
                        <p class="text-xs mb-3">Color: <span class="text-gray-500">${item.color || 'Original'}</span></p>
                        <div class="flex justify-between items-center">
                            <span class="text-xl font-black">${item.subtotalFmt}</span>
                            <div class="flex items-center bg-gray-100 rounded-full px-4 py-1.5 space-x-4 border">
                                <span class="font-bold text-sm">Qty : ${item.quantity}</span>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        });
        
        cartContainer.innerHTML = html;
        const totalFmt = 'Rp' + total.toLocaleString('id-ID').replace(/,/g, '.');
        const finalFmt = 'Rp' + (total + 10000).toLocaleString('id-ID').replace(/,/g, '.');
        
        summarySubtotal.innerText = totalFmt;
        summaryDelivery.innerText = 'Rp10.000';
        summaryTotal.innerText = finalFmt;
    }

    function removeItem(index) {
        let cart = JSON.parse(localStorage.getItem('cartData'));
        cart.splice(index, 1);
        localStorage.setItem('cartData', JSON.stringify(cart));
        renderCart(); // Re-render without full reload
        updateCartPopover(); // Re-render popover as well
    }

    function proceedToCheckout() {
        @if(!Session::has('user_login'))
            alert('Silahkan login terlebih dahulu untuk melakukan checkout.');
            window.location.href = '{{ route('login') }}';
            return;
        @endif

        const cartStr = localStorage.getItem('cartData');
        if (!cartStr || JSON.parse(cartStr).length === 0) {
            alert('Keranjang anda masih kosong! Silakan tambahkan produk terlebih dahulu.');
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
            renderCart();
            updateCartPopover();
        }
    }

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

    document.addEventListener("DOMContentLoaded", () => {
        updateCartPopover();
    });
</script>
</html>
