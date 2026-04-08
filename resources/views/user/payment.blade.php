<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HyperRack - Payment</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800;900&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
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
            body { @apply font-inter text-black; }
            h1, h2, h3, h4 { @apply font-outfit; }
        }
        /* Hide default details arrow */
        details summary::-webkit-details-marker { display:none; }
        details summary { list-style: none; }
    </style>
</head>
<body class="bg-white text-black">

    <nav class="max-w-[1440px] mx-auto flex items-center justify-between px-10 py-5 border-b border-gray-100">
        <a href="{{ route('user.home') }}" class="font-outfit font-extrabold text-[2rem] tracking-tighter text-black leading-none">HyperRack</a>
        
        <div class="flex items-center gap-5">
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
                <a href="{{ route('login') }}" class="text-2xl text-black"><i class="fa-regular fa-user"></i></a>
            @endif
        </div>
    </nav>

    <div class="max-w-6xl mx-auto px-5 mt-6 text-xs text-gray-500 font-inter flex items-center gap-2">
        <a href="{{ route('user.home') }}">Home</a> <i class="fa-solid fa-chevron-right text-[10px]"></i> 
        <span class="text-black font-medium">Payment</span>
    </div>

    <main class="max-w-6xl mx-auto px-5 mt-4 mb-20">
        <h1 class="text-3xl font-black mb-6">Payment Item</h1>

        <form id="payment-form">
            @csrf
            <input type="hidden" name="cart_data" id="cart-data-input">
            <input type="hidden" name="alamat" id="address-input">
            <div class="flex flex-col lg:flex-row gap-10 items-start">
                
                <div class="flex-1 w-full">
                    <div class="flex flex-col md:flex-row justify-between items-start border-t border-b py-4 mb-6">
                        <div class="flex items-start space-x-3">
                            <i class="fa-solid fa-location-dot mt-1"></i>
                            <div>
                                <p class="font-bold text-sm">Your Address</p>
                                <p class="text-xs text-gray-600 max-w-md mt-1 italic" id="display-address">{{ !empty($alamat) ? $alamat : 'Add your address' }}</p>
                            </div>
                        </div>
                        <button type="button" id="address-btn-text" class="bg-[#484848] pt-[2px] pb-[4px] text-white px-8 md:px-12 py-2.5 rounded-lg text-sm font-bold w-full md:w-auto mt-4 md:mt-0" onclick="document.getElementById('address-modal').classList.remove('hidden')">{{ !empty($alamat) ? 'Edit Address' : 'Add Address' }}</button>
                    </div>

                    <div class="mb-8" id="payment-items-container">
                        </div>

                    <div class="mb-10">
                        <h2 class="font-bold text-gray-500 mb-4 uppercase text-[10px] tracking-widest">Payment Methods</h2>
                        
                        <label class="flex items-start space-x-3 mb-4 cursor-pointer group">
                            <input type="radio" name="payment_method" value="cod" class="mt-1 accent-black" required>
                            <div>
                                <p class="text-sm font-medium text-gray-400 group-hover:text-black transition">Pay on Delivery</p>
                                <p class="text-[10px] text-gray-400 italic">Pay with cash on delivery</p>
                            </div>
                        </label>

                        <details class="group border-t border-b" open>
                            <summary class="flex justify-between items-center cursor-pointer py-4 outline-none">
                                <div class="flex items-start space-x-3">
                                    <i class="fa-solid fa-chevron-down text-[10px] mt-1 transition-transform group-open:rotate-180"></i>
                                    <div>
                                        <p class="text-sm font-medium">Virtual Account</p>
                                        <p class="text-[10px] text-gray-500">Pay with your bank</p>
                                    </div>
                                </div>
                            </summary>
                            
                            <div class="mt-2 pl-6 space-y-4 pb-4">
                                <label class="flex items-center justify-between cursor-pointer py-2 border-b border-gray-100 last:border-0 hover:bg-gray-50 px-2 rounded transition">
                                    <div class="flex items-center space-x-4">
                                        <img src="https://upload.wikimedia.org/wikipedia/commons/5/5c/Bank_Central_Asia.svg" class="w-10">
                                        <span class="text-xs font-medium">BCA Virtual Account</span>
                                    </div>
                                    <input type="radio" name="payment_method" value="bca_va" class="accent-indigo-600 h-4 w-4">
                                </label>
                                <label class="flex items-center justify-between cursor-pointer py-2 border-b border-gray-100 last:border-0 hover:bg-gray-50 px-2 rounded transition">
                                     <div class="flex items-center space-x-4">
                                         <img src="https://upload.wikimedia.org/wikipedia/commons/2/2e/BRI_2020.svg" class="w-10">
                                         <span class="text-xs font-medium">BRI Virtual Account</span>
                                     </div>
                                     <input type="radio" name="payment_method" value="bri_va" class="accent-indigo-600 h-4 w-4">
                                 </label>
                                 <label class="flex items-center justify-between cursor-pointer py-2 hover:bg-gray-50 px-2 rounded transition">
                                     <div class="flex items-center space-x-4">
                                         <img src="https://upload.wikimedia.org/wikipedia/id/f/fa/Bank_Mandiri_logo.svg" class="w-10">
                                         <span class="text-xs font-medium">Mandiri Virtual Account</span>
                                     </div>
                                     <input type="radio" name="payment_method" value="mandiri_va" class="accent-indigo-600 h-4 w-4">
                                 </label>
                            </div>
                        </details>
                    </div>
                </div>

                <div class="w-full lg:w-[320px] flex-shrink-0">
                    <div class="border border-gray-300 p-5 rounded-sm">
                        <h3 class="text-[11px] font-medium mb-6">Check Your Transaction Summary</h3>
                        <div class="space-y-4 text-[11px]">
                            <div class="flex justify-between">
                                <span class="text-gray-500" id="summary-items-count">Total Price (0 Item)</span>
                                <span class="font-medium" id="summary-items-total">IDR 0</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Total cost</span>
                                <span class="font-medium">IDR 5.000</span>
                            </div>
                            <div id="summary-tx-fee-row" class="flex justify-between hidden transition-all duration-300">
                                <span class="text-gray-500">Transaction Fees</span>
                                <span class="font-medium text-indigo-600">IDR 1.000</span>
                            </div>
                        </div>
                        <div class="border-t my-4 pt-4 flex justify-between font-bold text-xs">
                            <span>Total Bill</span>
                            <span id="summary-total-bill">IDR 0</span>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-4 mt-8">
                        <button type="button" onclick="openCancelCheckoutModal()" class="border border-gray-300 px-6 py-2 rounded text-xs font-medium hover:bg-gray-50 transition min-w-[80px] text-center flex items-center justify-center">Back</button>
                        <button type="submit" class="bg-black text-white px-6 py-2 rounded text-xs font-medium min-w-[120px] text-center" id="pay-button">
                            IDR 0
                        </button>
                    </div>
                </div>
            </div>
        </form>
        
        <script>
            document.getElementById('payment-form').addEventListener('submit', function(e) {
                e.preventDefault();
                
                const btn = document.getElementById('pay-button');
                btn.disabled = true;
                btn.innerText = 'Processing...';

                const cartStr = localStorage.getItem('cartData');
                document.getElementById('cart-data-input').value = cartStr;
                document.getElementById('address-input').value = document.getElementById('display-address').innerText;

                const formData = new FormData(this);
                
                fetch("{{ route('cart.checkout') }}", {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if(data.success) {
                        localStorage.removeItem('cartData');
                        window.location.href = data.redirect;
                    } else {
                        alert(data.message || 'Gagal memproses checkout.');
                        btn.disabled = false;
                        btn.innerText = 'Pay Now';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan sistem.');
                    btn.disabled = false;
                });
            });
        </script>
    </main>

    <script>
        // Logika render data keranjang (tetap pakai JS agar data dinamis)
        function renderPaymentItems() {
            const cartStr = localStorage.getItem('cartData');
            const container = document.getElementById('payment-items-container');
            const payBtn = document.getElementById('pay-button');

            if (!cartStr || JSON.parse(cartStr).length === 0) {
                window.location.href = "{{ route('cart') }}";
                return;
            }

            let cart = JSON.parse(cartStr);
            let html = '';
            let total = 0;
            let totalItemsCount = 0;

            cart.forEach((item, index) => {
                total += item.subtotalNum;
                totalItemsCount += parseInt(item.quantity) || 1;
                html += `
                    <div class="mb-4">
                        <div class="flex space-x-4 items-center border-b pb-6">
                            <div class="bg-[#F0EEED] rounded p-1 w-20 h-20 flex-shrink-0 flex items-center justify-center">
                                <img src="${item.image}" class="w-full h-full object-contain mix-blend-multiply">
                            </div>
                            <div class="flex-1">
                                <h3 class="font-bold text-xs uppercase leading-snug">${item.name}</h3>
                                <p class="text-[10px] text-gray-500 mt-1">Size: ${item.size} | Color: ${item.color || 'Original'}</p>
                                <div class="flex justify-between items-center mt-2">
                                    <span class="font-black text-sm italic">${item.subtotalFmt}</span>
                                    <div class="flex items-center gap-3">
                                        <div class="flex items-center bg-black text-white rounded px-3 py-0.5 text-[10px] font-bold">
                                            Qty : ${item.quantity}
                                        </div>
                                        <button type="button" onclick="removeItemFromPayment(${index})" class="text-red-500 hover:text-red-700 transition-colors p-1" title="Hapus produk">
                                            <i class="fa-solid fa-trash-can shadow-sm"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            });

            container.innerHTML = html;

            // Update Total logic
            function updateTotal() {
                const selectedMethod = document.querySelector('input[name="payment_method"]:checked');
                const needsFee = selectedMethod && (selectedMethod.value.includes('_va') || selectedMethod.value === 'cod');
                const txFee = needsFee ? 1000 : 0;
                
                const shipping = 5000;
                const finalTotal = total + shipping + txFee;
                const finalFmt = 'IDR ' + finalTotal.toLocaleString('id-ID').replace(/,/g, '.');
                
                if (needsFee) {
                    document.getElementById('summary-tx-fee-row').classList.remove('hidden');
                } else {
                    document.getElementById('summary-tx-fee-row').classList.add('hidden');
                }

                payBtn.innerText = finalFmt;
                document.getElementById('summary-total-bill').innerText = finalFmt;
            }

            document.getElementById('summary-items-count').innerText = `Total Price (${totalItemsCount} Item)`;
            document.getElementById('summary-items-total').innerText = 'IDR ' + total.toLocaleString('id-ID').replace(/,/g, '.');
            
            // Initial update
            updateTotal();

            // Listen to payment method changes
            document.querySelectorAll('input[name="payment_method"]').forEach(radio => {
                radio.addEventListener('change', updateTotal);
            });
        }

        let indexToDelete = null;

        function removeItemFromPayment(index) {
            indexToDelete = index;
            document.getElementById('confirm-modal').classList.remove('hidden');
        }

        function closeConfirmModal() {
            document.getElementById('confirm-modal').classList.add('hidden');
            indexToDelete = null;
        }

        document.addEventListener("DOMContentLoaded", () => {
            renderPaymentItems();

            // Setup confirm delete listener after DOM is ready
            const confirmBtn = document.getElementById('confirm-delete-btn');
            if (confirmBtn) {
                confirmBtn.addEventListener('click', () => {
                    if (indexToDelete !== null) {
                        let cartStr = localStorage.getItem('cartData');
                        if(cartStr) {
                            let cart = JSON.parse(cartStr);
                            cart.splice(indexToDelete, 1);
                            localStorage.setItem('cartData', JSON.stringify(cart));
                            
                            if (cart.length === 0) {
                                window.location.href = "{{ route('cart') }}";
                            } else {
                                renderPaymentItems();
                            }
                        }
                        closeConfirmModal();
                    }
                });
            }
        });

        function saveAddress() {
            const newAddress = document.getElementById('input-complete-address').value;
            const newNote = document.getElementById('input-note').value;
            
            if (newAddress.trim() === "") {
                alert("Alamat tidak boleh kosong!");
                return;
            }

            let fullAddress = newAddress.trim();
            if (newNote.trim() !== "") {
                fullAddress += ' (Note: ' + newNote.trim() + ')';
            }

            // SIMPAN KE DATABASE Lewat AJAX
            fetch("{{ route('address.update') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    alamat: fullAddress
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('display-address').innerText = data.address;
                    document.getElementById('address-btn-text').innerText = 'Edit Address';
                    document.getElementById('address-modal').classList.add('hidden');
                } else {
                    alert(data.message || 'Gagal menyimpan alamat.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terdapat masalah sistem.');
            });
        }
    </script>
    
    <!-- Address Modal -->
    <div id="address-modal" class="fixed inset-0 bg-black bg-opacity-40 z-[999] hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-xl w-full max-w-lg p-7 relative">
            <button type="button" onclick="document.getElementById('address-modal').classList.add('hidden')" class="absolute top-4 right-4 text-gray-400 hover:text-black">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>
            <div class="space-y-4">
                <div>
                    <label class="block text-[11px] font-bold mb-1">Pin Point</label>
                    <div class="relative">
                        <i class="fa-solid fa-location-dot absolute left-4 top-1/2 -translate-y-1/2 text-black text-[12px]"></i>
                        <input type="text" class="w-full text-xs font-bold border border-black rounded-lg pl-10 pr-10 py-3 focus:outline-none focus:border-black" value="{{ !empty($alamat) ? $alamat : '' }}" placeholder="Cari lokasi pin point Anda...">
                        
                    </div>
                </div>
                <div>
                    <label class="block text-[11px] font-bold mb-1 mt-2">Complete Address</label>
                    <textarea id="input-complete-address" class="w-full text-xs font-bold border border-black rounded-lg p-3 focus:outline-none focus:border-black" rows="2" style="resize: none;" placeholder="Beri tau kami alamat lengkap Anda...">{{ !empty($alamat) ? $alamat : '' }}</textarea>
                </div>
                <div>
                    <label class="block text-[11px] font-bold mb-1 mt-2">Note</label>
                    <input type="text" id="input-note" class="w-full text-xs border border-black rounded-lg p-3 focus:outline-none focus:border-black text-gray-500 italic" placeholder="*Note for your detail address">
                </div>
                <div>
                    <label class="block text-[11px] font-bold mb-1 mt-2">Recipient</label>
                    <input type="text" class="w-full text-xs font-bold border border-black rounded-lg p-3 focus:outline-none focus:border-black" value="Haikal">
                </div>
                <div>
                    <label class="block text-[11px] font-bold mb-1 mt-2">Phone Number</label>
                    <input type="text" class="w-full text-xs font-bold border border-black rounded-lg p-3 focus:outline-none focus:border-black" value="089508120877">
                </div>
                <div class="flex justify-end pt-4">
                    <button type="button" class="bg-black text-white text-[11px] font-bold px-6 py-3 rounded-full hover:opacity-80 transition" onclick="saveAddress()">Save and Change</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Confirmation Modal -->
    <div id="confirm-modal" class="fixed inset-0 bg-black bg-opacity-40 z-[1000] hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl w-full max-w-xs p-7 text-center shadow-2xl transform transition-all">
            <div class="w-16 h-16 bg-red-50 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fa-solid fa-trash-can text-red-500 text-2xl"></i>
            </div>
            <h3 class="text-xl font-bold mb-2">Batalkan Produk?</h3>
            <p class="text-xs text-gray-400 mb-8 leading-relaxed">Produk ini akan dihapus dari daftar checkout Anda secara permanen dari sesi ini.</p>
            <div class="flex gap-3">
                <button type="button" onclick="closeConfirmModal()" class="flex-1 py-3 bg-gray-100 text-gray-700 rounded-xl text-[11px] font-bold hover:bg-gray-200 transition">Kembali</button>
                <button type="button" id="confirm-delete-btn" class="flex-1 py-3 bg-red-600 text-white rounded-xl text-[11px] font-bold hover:bg-red-700 shadow-lg shadow-red-200 transition">Hapus</button>
            </div>
        </div>
    </div>

    <!-- Cancel Checkout Confirmation Modal -->
    <div id="cancel-checkout-modal" class="fixed inset-0 bg-black bg-opacity-40 z-[1000] hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl w-full max-w-xs p-7 text-center shadow-2xl transform transition-all">
            <div class="w-16 h-16 bg-orange-50 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fa-solid fa-circle-exclamation text-orange-500 text-2xl"></i>
            </div>
            <h3 class="text-xl font-bold mb-2">Batalkan Checkout?</h3>
            <p class="text-xs text-gray-400 mb-8 leading-relaxed">Anda yakin ingin membatalkan proses checkout ini? Data yang sudah diisi tidak akan disimpan.</p>
            <div class="flex gap-3">
                <button type="button" onclick="closeCancelCheckoutModal()" class="flex-1 py-3 bg-gray-100 text-gray-700 rounded-xl text-[11px] font-bold hover:bg-gray-200 transition">Lanjut Bayar</button>
                <a href="{{ route('cart') }}" class="flex-1 py-3 bg-black text-white rounded-xl text-[11px] font-bold hover:opacity-80 shadow-lg transition flex items-center justify-center">Ya, Batalkan</a>
            </div>
        </div>
    </div>

    <script>
        function openCancelCheckoutModal() {
            document.getElementById('cancel-checkout-modal').classList.remove('hidden');
        }

        function closeCancelCheckoutModal() {
            document.getElementById('cancel-checkout-modal').classList.add('hidden');
        }
    </script>
</body>
</html>
