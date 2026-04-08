<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HyperRack - Payment Instructions</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800;900&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
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
            body { @apply font-inter text-black; }
            h1, h2, h3, h4 { @apply font-outfit; }
        }
    </style>
</head>
<body class="bg-white text-black font-inter">

    <nav class="flex items-center justify-between px-10 py-5 border-b">
        <a href="{{ route('user.home') }}" class="font-outfit font-extrabold text-[2rem] tracking-tighter hover:opacity-80 transition-opacity text-black leading-none">HyperRack</a>
        <div class="flex items-center space-x-5 text-lg">
            <div class="relative">
                <input type="text" placeholder="Search for products..." class="bg-gray-100 rounded-full py-2 px-10 w-80 focus:outline-none text-sm">
                <i class="fa-solid fa-magnifying-glass absolute left-4 top-3 text-gray-400"></i>
            </div>
            {{-- Order History Icon --}}
            <a href="{{ route('order.history') }}" class="text-2xl hover:opacity-80 transition-opacity" title="My Orders">
                <i class="fa-solid fa-truck-fast"></i>
            </a>
            <i class="fa-solid fa-cart-shopping cursor-pointer"></i>
            <i class="fa-solid fa-clover text-green-600"></i>
        </div>
    </nav>

    <div class="max-w-6xl mx-auto px-5 mt-6 text-xs text-gray-500 flex items-center gap-2">
        <a href="{{ route('user.home') }}">Home</a> <i class="fa-solid fa-chevron-right text-[8px]"></i> <span>Payment</span>
    </div>

    <main class="max-w-6xl mx-auto px-5 mt-10 mb-20">
        <div class="border border-gray-300 rounded-xl p-10 relative overflow-hidden">
            <div class="absolute top-5 right-5 text-gray-400 cursor-pointer">
                <i class="fa-solid fa-xmark text-xl"></i>
            </div>

            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 border-b pb-6">
                <div class="flex items-center space-x-4">
                    <div class="text-3xl"><i class="fa-regular fa-clock"></i></div>
                    <div>
                        <p class="text-gray-600 font-bold">Pay Before</p>
                        <p class="text-sm font-medium" id="pay-before-text">05 Feb 2026, 07.00 WIB</p>
                    </div>
                </div>
                <div class="flex space-x-2 mt-4 md:mt-0">
                    <span class="bg-red-500 text-white px-2 py-1 rounded text-xs font-bold" id="timer-hours">01</span>
                    <span class="text-red-500 font-bold">:</span>
                    <span class="bg-red-500 text-white px-2 py-1 rounded text-xs font-bold" id="timer-minutes">00</span>
                    <span class="text-red-500 font-bold">:</span>
                    <span class="bg-red-500 text-white px-2 py-1 rounded text-xs font-bold" id="timer-seconds">00</span>
                </div>
            </div>

            <div class="space-y-8 mb-20">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-gray-500 text-sm mb-2">Number Virtual Account</p>
                        <div class="flex items-center space-x-3">
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
                            <span class="text-xl font-black tracking-wider">{{ $vaNumber }}</span>
                            <i class="fa-regular fa-copy text-gray-400 cursor-pointer hover:text-black"></i>
                        </div>
                    </div>
                    <img src="{{ $bankLogo }}" class="h-12" alt="{{ $bankName }}">
                </div>

                <div class="flex justify-between items-end">
                    <div>
                        <div class="flex items-center space-x-3">
                            <span class="text-xl font-black" id="main-total-cost">IDR {{ number_format($latestOrder->total_harga ?? 0, 0, ',', '.') }}</span>
                            <i class="fa-regular fa-copy text-gray-400 cursor-pointer hover:text-black"></i>
                        </div>
                    </div>
                    <a href="javascript:void(0)" onclick="togglePaymentDetail(event)" class="text-sm font-bold border-b border-black">Payment Detail</a>
                </div>
            </div>

            <div class="flex justify-end">
                <a href="{{ route('payment.status', ['payment' => request()->get('payment')]) }}" class="bg-black text-white px-8 py-3 rounded-lg font-bold text-sm hover:bg-gray-800 transition inline-block text-center">
                    Check Payment Status
                </a>
            </div>
        </div>
    </main>

    <div class="max-w-6xl mx-auto bg-black text-white p-10 rounded-3xl flex flex-col md:flex-row items-center justify-between relative z-10 translate-y-1/2">
        <h2 class="text-4xl font-black w-full md:w-1/2 leading-tight">STAY UPTO DATE ABOUT OUR LATEST OFFERS</h2>
        <div class="w-full md:w-1/3 space-y-3 mt-5 md:mt-0 text-black">
            <div class="relative">
                <input type="text" placeholder="Enter your email address" class="w-full py-3 px-12 rounded-full outline-none">
                <i class="fa-regular fa-envelope absolute left-4 top-4 text-gray-400"></i>
            </div>
            <button class="w-full py-3 bg-white text-black font-bold rounded-full">Subscribe to Newsletter</button>
        </div>
    </div>

    <footer class="bg-gray-100 pt-32 pb-10 px-10">
        </footer>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            let deadlineStr = localStorage.getItem('paymentDeadline');
            let deadlineDate;

            // Initialize or reset if expired
            if (!deadlineStr || new Date(parseInt(deadlineStr)) < new Date()) {
                deadlineDate = new Date();
                deadlineDate.setHours(deadlineDate.getHours() + 1);
                localStorage.setItem('paymentDeadline', deadlineDate.getTime());
            } else {
                deadlineDate = new Date(parseInt(deadlineStr));
            }

            // Format date for "Pay Before"
            const months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
            const day = String(deadlineDate.getDate()).padStart(2, '0');
            const month = months[deadlineDate.getMonth()];
            const year = deadlineDate.getFullYear();
            const hours = String(deadlineDate.getHours()).padStart(2, '0');
            const minutes = String(deadlineDate.getMinutes()).padStart(2, '0');
            
            document.getElementById('pay-before-text').innerText = `${day} ${month} ${year}, ${hours}.${minutes} WIB`;

            // Setup timer elements
            const timerHours = document.getElementById('timer-hours');
            const timerMinutes = document.getElementById('timer-minutes');
            const timerSeconds = document.getElementById('timer-seconds');

            function updateTimer() {
                const now = new Date();
                const diff = deadlineDate - now;

                if (diff <= 0) {
                    timerHours.innerText = '00';
                    timerMinutes.innerText = '00';
                    timerSeconds.innerText = '00';
                    clearInterval(interval);
                    return;
                }

                const h = Math.floor((diff / (1000 * 60 * 60)) % 24);
                const m = Math.floor((diff / 1000 / 60) % 60);
                const s = Math.floor((diff / 1000) % 60);

                timerHours.innerText = String(h).padStart(2, '0');
                timerMinutes.innerText = String(m).padStart(2, '0');
                timerSeconds.innerText = String(s).padStart(2, '0');
            }

            updateTimer();
            const interval = setInterval(updateTimer, 1000);

            // Fetch and set initial total
            let cartStr = localStorage.getItem('cartData');
            if(cartStr) {
                let cart = JSON.parse(cartStr);
                let total = 0;
                cart.forEach(item => total += item.subtotalNum);
                
                const paymentMethod = new URLSearchParams(window.location.search).get('payment') || 'bca_va';
                const txFee = (paymentMethod.includes('_va') || paymentMethod === 'cod') ? 1000 : 0;
                const finalCost = total + 5000 + txFee; // shipping + tx fee
                document.getElementById('main-total-cost').innerText = 'IDR ' + finalCost.toLocaleString('id-ID').replace(/,/g, '.');
            }
        });

        function togglePaymentDetail(e) {
            if(e) e.preventDefault();
            const modal = document.getElementById('payment-detail-modal');
            modal.classList.toggle('hidden');
            
            if(!modal.classList.contains('hidden')) {
                renderModalData();
            }
        }

        function renderModalData() {
            let cartStr = localStorage.getItem('cartData');
            if(cartStr) {
                let cart = JSON.parse(cartStr);

                let total = 0;
                let itemCount = 0;
                let itemsHtml = '';

                cart.forEach(item => {
                    total += item.subtotalNum;
                    itemCount += parseInt(item.quantity) || 1;
                    itemsHtml += `
                    <div class="flex justify-between text-[10px] items-start">
                        <div class="max-w-[70%]">
                            <span class="block text-gray-800 font-bold uppercase mb-1">${item.name}</span>
                            <span class="block text-gray-500">Size: ${item.size} | Color: ${item.color || 'Original'}</span>
                            <span class="block text-gray-400 mt-2">Estimation<br>05-09 Feb</span>
                        </div>
                        <span class="font-bold">${item.subtotalFmt.replace('Rp', 'IDR ')}</span>
                    </div>
                    <hr class="my-3 border-gray-100">
                    `;
                });

                document.getElementById('modal-items-list').innerHTML = itemsHtml;
                document.getElementById('modal-item-count').innerText = itemCount;
                
                const shippingCost = 5000;
                const paymentMethod = new URLSearchParams(window.location.search).get('payment') || 'bca_va';
                const txFee = (paymentMethod.includes('_va') || paymentMethod === 'cod') ? 1000 : 0;
                
                const totalBill = total + shippingCost;
                const finalCost = totalBill + txFee;

                const formatCurrency = (num) => 'IDR ' + num.toLocaleString('id-ID').replace(/,/g, '.');

                // Hide fee row if COD
                if (txFee === 0) {
                    document.getElementById('modal-tx-fee-row').classList.add('hidden');
                } else {
                    document.getElementById('modal-tx-fee-row').classList.remove('hidden');
                }

                document.getElementById('modal-total-price').innerText = formatCurrency(total);
                document.getElementById('modal-total-bill').innerText = formatCurrency(totalBill);
                document.getElementById('modal-final-cost').innerText = formatCurrency(finalCost);
                document.getElementById('modal-final-payment').innerText = formatCurrency(finalCost);
            } else {
                // Fallback ke data dari database jika cartData di localStorage sudah dihapus
                const dbTotal = {{ $latestOrder->total_harga ?? 0 }};
                const shipping = 5000;
                const paymentMethod = new URLSearchParams(window.location.search).get('payment') || 'bca_va';
                const txFee = (paymentMethod.includes('_va') || paymentMethod === 'cod') ? 1000 : 0;
                const format = (num) => 'IDR ' + num.toLocaleString('id-ID').replace(/,/g, '.');

                // dbTotal sudah termasuk shipping + txFee (setelah fix di controller)
                const itemsTotal = dbTotal - shipping - txFee;
                const totalBill = dbTotal - txFee;

                // Hide fee row if COD
                if (txFee === 0) {
                    document.getElementById('modal-tx-fee-row').classList.add('hidden');
                } else {
                    document.getElementById('modal-tx-fee-row').classList.remove('hidden');
                }

                document.getElementById('modal-total-price').innerText = format(itemsTotal);
                document.getElementById('modal-total-bill').innerText = format(totalBill);
                document.getElementById('modal-final-cost').innerText = format(dbTotal);
                document.getElementById('modal-final-payment').innerText = format(dbTotal);
                document.getElementById('modal-item-count').innerText = "{{ $latestOrder->jumlah ?? 0 }}";
            }

            // Gunakan Alamat dari Database (Order) sebagai prioritas utama
            const orderAddress = @json($latestOrder->alamat ?? '');
            if(orderAddress) {
                let addressText = orderAddress;
                // Format note jika ada
                if(addressText.includes('(Note:')) {
                    addressText = addressText.replace('(Note:', '<br>Note :').replace(')', '');
                }
                document.getElementById('modal-address-text').innerHTML = addressText;
            }
        }
    </script>
    
    <!-- Payment Detail Modal -->
    <div id="payment-detail-modal" class="fixed inset-0 bg-black bg-opacity-40 z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-xl w-full max-w-lg p-8 relative max-h-[90vh] overflow-y-auto">
            <button type="button" onclick="togglePaymentDetail(event)" class="absolute top-6 right-6 text-gray-400 hover:text-black">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>
            <h3 class="text-[14px] font-medium mb-8">Check Your Transaction Summary</h3>
            
            <div class="space-y-4 text-[11px] border-b border-gray-200 pb-6 mb-6">
                <div class="flex justify-between">
                    <span>Total Price (<span id="modal-item-count">0</span> item)</span>
                    <span id="modal-total-price">IDR 0</span>
                </div>
                <div class="flex justify-between">
                    <span>Total cost</span>
                    <span>IDR 5.000</span>
                </div>
                <div class="flex justify-between">
                    <span>Total Bill</span>
                    <span id="modal-total-bill">IDR 0</span>
                </div>
            </div>

            <div id="modal-tx-fee-row" class="flex justify-between text-[11px] border-b border-gray-200 pb-6 mb-6">
                <span>Transaction Fees</span>
                <span>IDR 1000</span>
            </div>

            <div class="flex justify-between text-[11px] border-b border-gray-200 pb-6 mb-6">
                <span>Total Cost</span>
                <span id="modal-final-cost">IDR 0</span>
            </div>

            <div class="flex justify-between items-start text-[11px] border-b border-gray-200 pb-6 mb-6">
                <div>
                    <span class="block mb-2">Payment with</span>
                    <span class="text-gray-500">{{ $bankName }} Virtual Account</span>
                </div>
                <span id="modal-final-payment" class="text-gray-500">IDR 0</span>
            </div>

            <div>
                <h4 class="font-bold text-sm mb-4">Items</h4>
                <div id="modal-items-list" class="mb-6">
                    <!-- item block -->
                </div>

                <div class="pb-4">
                    <span class="block text-[11px] font-bold mb-2">Address</span>
                    <p class="text-[10px] text-gray-600 leading-relaxed" id="modal-address-text">
                        {!! str_replace('(Note:', '<br>Note :', str_replace(')', '', $latestOrder->alamat ?? 'Add your address')) !!}
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
