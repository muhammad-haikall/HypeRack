<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HyperRack - Product Detail</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Google Fonts: Outfit for headers, Inter for normal text -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800;900&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
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
<body class="bg-white">

    <header class="border-b">
        <div class="container mx-auto px-6 py-4 flex items-center justify-between">
            <a href="{{ route('user.home') }}" class="font-outfit font-extrabold text-[2rem] tracking-tighter hover:opacity-80 transition-opacity text-black">HyperRack</a>
            <nav class="hidden md:flex space-x-6 text-sm font-medium">
                <a href="#" class="flex items-center">Shop <i class="fa fa-chevron-down ml-1 text-[10px]"></i></a>
                <a href="{{ route('user.home') }}#top-selling">On Sale</a>
                <a href="{{ route('user.home') }}#new-arrivals">New Arrivals</a>
                <a href="{{ route('user.home') }}#browse">Brands</a>
            </nav>
            <div class="flex items-center space-x-6">
                <!-- Cart with Popover -->
                {{-- Order History Icon --}}
                <a href="{{ route('order.history') }}" class="text-2xl hover:opacity-80 transition-opacity" title="My Orders">
                    <i class="fa-solid fa-truck-fast"></i>
                </a>

                <div class="relative flex items-center">
                    <a href="#" class="text-xl relative block" onclick="toggleCart(event)">
                        <i class="fa fa-shopping-cart"></i>
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
                    <div class="relative group cursor-pointer flex items-center justify-center w-8 h-8 rounded-full bg-black text-white font-bold text-sm">
                        {{ strtoupper(substr(Session::get('user_name', 'U'), 0, 1)) }}
                        
                        <!-- Dropdown Menu -->
                        <div class="absolute right-0 top-full mt-2 w-32 bg-white border border-gray-100 rounded-lg shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all z-[100]">
                            <form action="{{ route('user.logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-50 rounded-lg font-normal block">Logout</button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="text-xl"><i class="fa fa-user-circle hover:opacity-80"></i></a>
                @endif
            </div>
        </div>
    </header>

    <main class="container mx-auto px-6 py-8">
        <nav class="text-xs text-gray-500 mb-6 font-inter flex items-center gap-2">
            <a href="{{ route('user.home') }}" class="hover:text-black hover:underline transition-colors">Home</a> 
            <i class="fa fa-chevron-right text-[10px]"></i> 
            <span class="hover:text-black cursor-pointer">Shop</span>
            <i class="fa fa-chevron-right text-[10px]"></i> 
            <span class="text-black font-medium">{{ $product->kategori ?? 'T-shirts' }}</span>
        </nav>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
            @php
                $imagePath = $product->gambar ?? 'baju1.png';
                // Hanya produk tertentu yang kita tahu punya varian warna
                $hasVariants = in_array($imagePath, ['baju1.png']); 
                $whiteVariant = $hasVariants ? 'putih.png' : $imagePath;
                $category = $product->kategori ?? 'Men\'s top';

                // Deteksi warna dari nama jika tidak ada varian manual
                $nameWords = explode(' ', strtoupper($product->nama_produk));
                $detectedColor = 'Original';
                $colorList = ['BLACK', 'WHITE', 'BENHUR', 'RED', 'BLUE', 'NAVY', 'MAROON', 'GREEN', 'GREY', 'YELLOW'];
                foreach($nameWords as $word) {
                    if (in_array($word, $colorList)) {
                        $detectedColor = ucfirst(strtolower($word));
                        break;
                    }
                }
            @endphp
            <div class="flex gap-4">
                <div class="flex flex-col gap-4">
                    <img src="{{ asset('images/' . $imagePath) }}" onclick="changeImage(this)" class="thumbnail-img w-24 h-24 border-2 rounded-lg object-cover border-black cursor-pointer hover:opacity-80 transition">
                    @if($hasVariants)
                    <img src="{{ asset('images/' . $whiteVariant) }}" onclick="changeImage(this)" class="thumbnail-img w-24 h-24 border-2 rounded-lg object-cover border-transparent cursor-pointer hover:opacity-80 transition">
                    @endif
                    @if(!in_array($category, ['Backpack', 'Male subordinates']))
                    <img src="{{ asset('images/uk.png') }}" onclick="changeImage(this)" class="thumbnail-img w-24 h-24 border-2 rounded-lg object-cover border-transparent cursor-pointer hover:opacity-80 transition">
                    @endif
                </div>
                <div class="flex-1 bg-gray-100 rounded-2xl flex items-center justify-center overflow-hidden">
                    <img id="main-image" src="{{ asset('images/' . $imagePath) }}" class="w-full h-full object-contain mix-blend-multiply transition-all duration-300">
                </div>
            </div>

            <div>
                <h2 class="text-3xl font-extrabold leading-tight uppercase mb-2">
                    {{ $product->nama_produk }}
                </h2>
                <div class="flex items-center mb-4">
                    <div class="flex text-yellow-400 text-sm">
                        @for($i=1; $i<=5; $i++)
                            @if($i <= floor((float)($product->rating ?? 0)))
                                <i class="fa fa-star"></i>
                            @elseif($i == ceil((float)($product->rating ?? 0)) && (float)($product->rating ?? 0) != floor((float)($product->rating ?? 0)))
                                <i class="fa-solid fa-star-half-stroke"></i>
                            @else
                                <i class="fa-regular fa-star"></i>
                            @endif
                        @endfor
                    </div>
                    <span class="text-sm ml-2 text-gray-500">{{ $product->rating ?? '0' }}/5</span>
                </div>
                <div class="text-3xl font-bold mb-6">Rp{{ number_format($product->harga, 0, ',', '.') }}</div>
                <p class="text-gray-500 text-sm mb-6 border-b pb-6">
                    This {{ strtolower($product->kategori ?? 'product') }} is perfect for any occasion. Designed with durability and style in mind, it is a must-have addition to your collection.
                </p>

                <div class="mb-6 border-b pb-6 {{ !$hasVariants ? 'hidden' : '' }}">
                    <p class="text-sm text-gray-500 mb-3">Select Colors</p>
                    <div class="flex gap-3">
                        <div onclick="selectColor(this, '{{ asset('images/' . $imagePath) }}', 'Black')" class="color-option w-8 h-8 rounded-full bg-black flex items-center justify-center text-white text-xs cursor-pointer hover:scale-110 transition-transform">
                            <i class="fa fa-check block"></i>
                        </div>
                        <div onclick="selectColor(this, '{{ asset('images/' . $whiteVariant) }}', 'White')" class="color-option w-8 h-8 rounded-full bg-white border border-gray-300 flex items-center justify-center text-black text-xs cursor-pointer hover:scale-110 transition-transform">
                            <i class="fa fa-check hidden"></i>
                        </div>
                    </div>
                </div>

                @if(!in_array(($product->kategori ?? ''), ['Backpack', 'Male subordinates']))
                <div class="mb-6 border-b pb-6">
                    <p class="text-sm text-gray-500 mb-3">Choose Size</p>
                    <div class="flex gap-3">
                        @foreach(['S', 'M', 'L', 'XL'] as $size)
                            <button onclick="selectSize(this)" class="size-btn px-6 py-2 rounded-full text-sm transition-colors {{ $size == 'L' ? 'bg-black text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                                {{ $size }}
                            </button>
                        @endforeach
                    </div>
                </div>
                @endif

                <button onclick="{{ $product->stok > 0 ? 'directCheckout()' : '' }}" 
                        class="w-full mb-4 rounded-full py-4 font-bold transition-all
                        {{ $product->stok > 0 ? 'bg-indigo-600 text-white hover:bg-indigo-700 shadow-xl shadow-indigo-100 flex items-center justify-center gap-2' : 'bg-gray-300 text-gray-500 cursor-not-allowed' }}">
                    <i class="fa-solid fa-bolt"></i>
                    {{ $product->stok > 0 ? 'Buy It Now' : 'Out of Stock' }}
                </button>

                <div class="flex gap-4">
                    <div class="flex items-center bg-gray-100 rounded-full px-4 py-3">
                        <button onclick="updateQuantity(-1)" class="text-xl w-8 h-8 flex items-center justify-center hover:bg-gray-200 rounded-full transition-colors">-</button>
                        <span id="quantity-display" class="mx-4 font-semibold text-center w-6">1</span>
                        <button onclick="updateQuantity(1)" class="text-xl w-8 h-8 flex items-center justify-center hover:bg-gray-200 rounded-full transition-colors">+</button>
                    </div>
                    <button onclick="{{ $product->stok > 0 ? 'addToCart()' : '' }}" 
                            class="flex-1 rounded-full py-3 font-medium transition-colors 
                            {{ $product->stok > 0 ? 'bg-black text-white hover:bg-gray-900' : 'bg-gray-300 text-gray-500 cursor-not-allowed' }}">
                        {{ $product->stok > 0 ? 'Add to Cart' : 'Out of Stock' }}
                    </button>
                </div>
            </div>
        </div>

        <!-- Navigation Tabs -->
        <div class="mt-16 border-b flex text-center relative">
            <div onclick="switchTab('details')" id="tab-details" class="flex-1 pb-4 border-b-2 border-black font-semibold cursor-pointer transition-all">Product Details</div>
            <div onclick="switchTab('reviews')" id="tab-reviews" class="flex-1 pb-4 text-gray-400 border-b-2 border-transparent cursor-pointer transition-all">Rating & Reviews</div>

        </div>

        <!-- Tab Content: Product Details -->
        <div id="content-details" class="tab-content transition-all duration-300">
            <section class="py-8">
                <h3 class="text-lg font-bold mb-4">Product Specifications</h3>
                <div class="grid grid-cols-2 gap-y-3 text-sm max-w-xl">
                    <div class="text-gray-400">Category</div><div class="text-blue-500">Shop > {{ $product->kategori ?? 'Men\'s top' }}</div>
                    <div class="text-gray-400">Stock</div><div>AVAILABLE</div>
                    <div class="text-gray-400">Brand</div><div class="text-blue-500">{{ $product->brand ?? 'HyperRack' }}</div>
                </div>
                <div class="mt-10 text-sm text-gray-600 leading-relaxed max-w-3xl">
                    <p class="mb-4">This graphic t-shirt which is perfect for any occasion. Crafted from a soft and breathable fabric, it offers superior comfort and style. Whether you're heading to a casual outing or just lounging at home, this tee is a must-have addition to your wardrobe.</p>
                    <p>Designed with meticulous attention to detail, the fabric is chosen for its durability and softness. The fit is modern and comfortable, ensuring you look great throughout the day.</p>
                </div>
            </section>
        </div>

        <!-- Tab Content: Rating & Reviews -->
        <div id="content-reviews" class="tab-content hidden transition-all duration-300">
            <section class="py-8">
                <div class="flex items-center justify-between mb-8">
                    <div class="flex items-center gap-2">
                        <h3 class="text-2xl font-bold">All Reviews</h3>
                        <span class="text-gray-400 font-medium">(451)</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <button class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center hover:bg-gray-200 transition">
                            <i class="fa fa-sliders-h"></i>
                        </button>
                        <select class="bg-gray-100 rounded-full py-2 px-6 font-medium text-sm focus:outline-none hidden md:block">
                            <option>Latest</option>
                            <option>Oldest</option>
                            <option>Highest Rating</option>
                        </select>
                        <button onclick="openReviewModal()" class="bg-black text-white px-6 py-2.5 rounded-full font-medium text-sm hover:bg-gray-800 transition">Write a Review</button>
                    </div>
                </div>

                <!-- Reviews Grid -->
                <div id="reviews-container" class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    @php
                        $reviews = [
                            ['name' => 'Samantha D.', 'date' => 'August 14, 2023', 'content' => '"I absolutely love this t-shirt! The design is unique and the fabric feels so comfortable. As a fellow designer, I appreciate the attention to detail. It\'s become my favorite go-to shirt."', 'rating' => 5],
                            ['name' => 'Alex M.', 'date' => 'August 15, 2023', 'content' => '"The t-shirt exceeded my expectations! The colors are vibrant and the print quality is top-notch. Being a UI/UX designer myself, I\'m quite picky about aesthetics, and this t-shirt definitely gets a thumbs up from me."', 'rating' => 4],
                            ['name' => 'Ethan R.', 'date' => 'August 16, 2023', 'content' => '"This t-shirt is a must-have for anyone who appreciates good design. The minimalistic yet stylish pattern caught my eye, and the fit is perfect. I can see the designer\'s touch in every aspect of this shirt."', 'rating' => 5],
                            ['name' => 'Olivia P.', 'date' => 'August 17, 2023', 'content' => '"As a UI/UX enthusiast, I value simplicity and functionality. This t-shirt not only represents those principles but also feels great to wear. It\'s evident that the designer poured their creativity into making this t-shirt stand out."', 'rating' => 4],
                            ['name' => 'Liam K.', 'date' => 'August 18, 2023', 'content' => '"This t-shirt is a fusion of comfort and creativity. The fabric is soft, and the design speaks volumes about the designer\'s skill. It\'s like wearing a piece of art that reflects my passion for both design and fashion."', 'rating' => 5],
                            ['name' => 'Ava H.', 'date' => 'August 19, 2023', 'content' => '"I\'m not just wearing a t-shirt; I\'m wearing a piece of design philosophy. The intricate details and thoughtful layout of the design make this shirt a conversation starter."', 'rating' => 5]
                        ];
                    @endphp

                    @foreach($reviews as $index => $r)
                    <div class="review-card border rounded-2xl p-7 relative" data-id="{{ $index }}">
                        <div class="absolute top-7 right-7">
                            <button onclick="toggleReviewDropdown(event, this)" class="text-gray-400 hover:text-black transition">
                                <i class="fa fa-ellipsis-h text-xl"></i>
                            </button>
                            <!-- Dropdown Menu -->
                            <div class="review-dropdown absolute right-0 mt-2 w-32 bg-white border border-gray-100 rounded-xl shadow-xl hidden z-50 overflow-hidden">
                                <button onclick="editReview(this)" class="w-full text-left px-4 py-2.5 text-sm hover:bg-gray-50 flex items-center gap-2 transition-colors">
                                    <i class="fa fa-edit text-blue-500"></i> Edit
                                </button>
                                <button onclick="deleteReview(this)" class="w-full text-left px-4 py-2.5 text-sm hover:bg-gray-50 text-red-500 flex items-center gap-2 border-t transition-colors font-medium">
                                    <i class="fa fa-trash-alt"></i> Delete
                                </button>
                            </div>
                        </div>
                        <div class="flex text-yellow-400 mb-3 text-sm rating-stars">
                            @for($i = 0; $i < 5; $i++)
                                @if($i < $r['rating'])
                                    <i class="fa fa-star"></i>
                                @else
                                    <i class="fa-regular fa-star"></i>
                                @endif
                            @endfor
                        </div>
                        <div class="flex items-center gap-2 mb-3">
                            <h4 class="font-bold text-lg reviewer-name">{{ $r['name'] }}</h4>
                            <div class="w-4 h-4 bg-green-500 rounded-full flex items-center justify-center text-[10px] text-white">
                                <i class="fa fa-check"></i>
                            </div>
                        </div>
                        <p class="review-text text-gray-500 text-sm leading-relaxed mb-6 italic">{{ $r['content'] }}</p>
                        <p class="text-gray-400 text-sm font-medium">Posted on <span class="review-date">{{ $r['date'] }}</span></p>
                    </div>
                    @endforeach
                </div>

                <div class="flex justify-center mt-12">
                    <button class="px-10 py-3 border rounded-full font-medium hover:bg-black hover:text-white transition-all duration-300">Load More Reviews</button>
                </div>
            </section>
        </div>

        <!-- You Might Also Like Section -->
        <section class="mt-24 mb-16">
            <h2 class="text-4xl lg:text-5xl font-black text-center mb-14 uppercase">You Might Also Like</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                @php
                    $recProducts = [
                        ['id' => 1, 'name' => 'Polo with Contrast Trims', 'price' => 'Rp212.000', 'rating' => 4.0, 'image' => 'baju1.png'],
                        ['id' => 2, 'name' => 'Gradient Graphic T-shirt', 'price' => 'Rp145.000', 'rating' => 3.5, 'image' => 'longsleve1.png'],
                        ['id' => 3, 'name' => 'Polo with Tipping Details', 'price' => 'Rp180.000', 'rating' => 4.5, 'image' => 'Hoodie.png'],
                        ['id' => 4, 'name' => 'Black Striped T-shirt', 'price' => 'Rp120.000', 'rating' => 5.0, 'image' => 'uk.png'],
                    ];
                @endphp

                @foreach($recProducts as $p)
                <a href="{{ route('product.detail', $p['id']) }}" class="group block">
                    <div class="bg-gray-100 rounded-2xl aspect-square flex items-center justify-center p-8 mb-4 hover:opacity-80 transition cursor-pointer overflow-hidden">
                        <img src="{{ asset('images/'.$p['image']) }}" class="w-full h-full object-contain mix-blend-multiply group-hover:scale-105 transition-transform duration-500">
                    </div>
                    <h4 class="font-bold text-lg mb-1 truncate">{{ $p['name'] }}</h4>
                    <div class="flex items-center mb-1">
                        <div class="flex text-yellow-400 text-xs">
                            <i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star-half-alt"></i>
                        </div>
                        <span class="text-xs ml-2 text-gray-500">{{ $p['rating'] }}/5</span>
                    </div>
                    <p class="font-bold text-xl">{{ $p['price'] }}</p>
                </a>
                @endforeach
            </div>
        </section>

        <section class="mt-20 bg-black rounded-3xl p-10 flex flex-col md:flex-row items-center justify-between">
            <h2 class="text-white text-4xl font-extrabold max-w-md leading-tight mb-6 md:mb-0">
                STAY UPTO DATE ABOUT OUR LATEST OFFERS
            </h2>
            <div class="w-full max-w-sm flex flex-col gap-3">
                <div class="relative">
                    <i class="fa fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <input type="text" placeholder="Enter your email address" class="w-full rounded-full py-3 pl-12 pr-4">
                </div>
                <button class="bg-white text-black font-bold py-3 rounded-full">Subscribe to Newsletter</button>
            </div>
        </section>
    </main>

    <footer class="bg-gray-100 pt-20 pb-10 mt-10">
        <div class="container mx-auto px-6 grid grid-cols-1 md:grid-cols-5 gap-10 border-b pb-10">
            <div class="col-span-1 md:col-span-1">
                <h2 class="text-2xl font-extrabold mb-4">HyperRack</h2>
                <p class="text-gray-500 text-sm mb-6">We have clothes that suits your style and which you're proud to wear. From women to men.</p>
                <div class="flex gap-3">
                    <i class="fab fa-twitter"></i><i class="fab fa-facebook"></i><i class="fab fa-instagram"></i><i class="fab fa-github"></i>
                </div>
            </div>
            @foreach(['COMPANY' => ['About', 'Features', 'Works', 'Career'], 'HELP' => ['Customer Support', 'Delivery Details', 'Terms & Conditions', 'Privacy Policy']] as $title => $links)
            <div>
                <h4 class="font-bold mb-4 tracking-widest text-sm uppercase">{{ $title }}</h4>
                <ul class="text-gray-500 text-sm space-y-3">
                    @foreach($links as $link) <li>{{ $link }}</li> @endforeach
                </ul>
            </div>
            @endforeach
        </div>
    </footer>
    <!-- Review Modal -->
    <div id="review-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-[200]">
        <div class="bg-white rounded-3xl p-8 max-w-lg w-full mx-4 relative">
            <button onclick="closeReviewModal()" class="absolute top-6 right-6 text-2xl text-gray-400 hover:text-black transition">
                <i class="fa fa-times"></i>
            </button>
            <h3 id="modal-title" class="text-3xl font-black mb-6 uppercase">Write a Review</h3>
            
            <div class="mb-6">
                <p class="text-sm font-bold text-gray-600 mb-3">Your Rating</p>
                <div class="flex gap-2 text-2xl" id="star-input-container">
                    <i onclick="setRating(1)" class="fa-solid fa-star star-input cursor-pointer text-gray-200 hover:text-yellow-400 transition" data-val="1"></i>
                    <i onclick="setRating(2)" class="fa-solid fa-star star-input cursor-pointer text-gray-200 hover:text-yellow-400 transition" data-val="2"></i>
                    <i onclick="setRating(3)" class="fa-solid fa-star star-input cursor-pointer text-gray-200 hover:text-yellow-400 transition" data-val="3"></i>
                    <i onclick="setRating(4)" class="fa-solid fa-star star-input cursor-pointer text-gray-200 hover:text-yellow-400 transition" data-val="4"></i>
                    <i onclick="setRating(5)" class="fa-solid fa-star star-input cursor-pointer text-gray-200 hover:text-yellow-400 transition" data-val="5"></i>
                </div>
            </div>

            <div class="mb-6">
                <label for="review-content" class="block text-sm font-bold text-gray-600 mb-3">Review Content</label>
                <textarea id="review-content" rows="4" placeholder="Share your experience with this product..." class="w-full bg-gray-50 border border-gray-200 rounded-2xl p-4 text-sm focus:outline-none focus:border-black transition"></textarea>
            </div>

            <button id="submit-review-btn" onclick="submitReview()" class="w-full bg-black text-white font-bold py-4 rounded-full hover:bg-gray-800 transition">Post Review</button>
        </div>
    </div>

    <script>
        let selectedReviewRating = 0;

        let editingReviewId = null;

        function openReviewModal(isEdit = false, reviewData = null) {
            @if(!Session::has('user_login'))
                alert('Please login to write a review');
                window.location.href = '{{ route('login') }}';
                return;
            @endif

            const modal = document.getElementById('review-modal');
            const title = document.getElementById('modal-title');
            const submitBtn = document.getElementById('submit-review-btn');

            if (isEdit && reviewData) {
                editingReviewId = reviewData.id;
                title.innerText = 'Edit Your Review';
                submitBtn.innerText = 'Save Changes';
                document.getElementById('review-content').value = reviewData.content.replace(/^"|"$/g, '');
                setRating(reviewData.rating);
            } else {
                editingReviewId = null;
                title.innerText = 'Write a Review';
                submitBtn.innerText = 'Post Review';
                resetReviewForm();
            }

            modal.classList.remove('hidden');
        }

        function closeReviewModal() {
            document.getElementById('review-modal').classList.add('hidden');
            resetReviewForm();
        }

        function toggleReviewDropdown(event, button) {
            event.stopPropagation();
            // Close all other dropdowns
            document.querySelectorAll('.review-dropdown').forEach(dropdown => {
                if (dropdown !== button.nextElementSibling) {
                    dropdown.classList.add('hidden');
                }
            });
            const dropdown = button.nextElementSibling;
            dropdown.classList.toggle('hidden');
        }

        function editReview(button) {
            const card = button.closest('.review-card');
            const content = card.querySelector('.review-text').innerText;
            // Count stars that are NOT regular (empty)
            const rating = card.querySelectorAll('.rating-stars i:not(.fa-regular)').length;
            const id = card.getAttribute('data-id');

            openReviewModal(true, {
                id: id,
                content: content,
                rating: rating
            });
            
            // Close dropdown
            button.closest('.review-dropdown').classList.add('hidden');
        }

        function deleteReview(button) {
            if (confirm('Are you sure you want to delete this review?')) {
                const card = button.closest('.review-card');
                card.style.opacity = '0';
                card.style.transform = 'scale(0.95)';
                setTimeout(() => {
                    card.remove();
                    // Update count if needed
                }, 300);
            }
        }

        // Close dropdowns when clicking anywhere
        window.addEventListener('click', () => {
            document.querySelectorAll('.review-dropdown').forEach(dropdown => {
                dropdown.classList.add('hidden');
            });
        });

        function setRating(val) {
            selectedReviewRating = val;
            const stars = document.querySelectorAll('.star-input');
            stars.forEach((star, idx) => {
                if (idx < val) {
                    star.classList.remove('text-gray-200');
                    star.classList.add('text-yellow-400');
                } else {
                    star.classList.add('text-gray-200');
                    star.classList.remove('text-yellow-400');
                }
            });
        }

        function submitReview() {
            const content = document.getElementById('review-content').value;
            if (selectedReviewRating === 0) {
                alert('Please select a rating');
                return;
            }
            if (!content.trim()) {
                alert('Please write your review content');
                return;
            }

            if (editingReviewId !== null) {
                // UPDATE EXISTING REVIEW
                const card = document.querySelector(`.review-card[data-id="${editingReviewId}"]`);
                if (card) {
                    card.querySelector('.review-text').innerText = `"${content}"`;
                    
                    // Update stars
                    let starsHtml = '';
                    for(let i=0; i<5; i++) {
                        starsHtml += (i < selectedReviewRating) ? '<i class="fa fa-star"></i>' : '<i class="fa-regular fa-star"></i>';
                    }
                    card.querySelector('.rating-stars').innerHTML = starsHtml;
                    
                    // Visual feedback
                    card.classList.add('ring-2', 'ring-black');
                    setTimeout(() => card.classList.remove('ring-2', 'ring-black'), 1000);
                }
                closeReviewModal();
                return;
            }

            const userName = '{{ Session::get('user_name', 'Guest') }}';
            const date = new Date().toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' });
            const newId = 'new-' + Date.now();
            
            // Generate Stars HTML
            let starsHtml = '';
            for(let i=0; i<5; i++) {
                if(i < selectedReviewRating) {
                    starsHtml += '<i class="fa fa-star"></i>';
                } else {
                    starsHtml += '<i class="fa-regular fa-star"></i>';
                }
            }

            const reviewHtml = `
                <div class="review-card border rounded-2xl p-7 relative transition-all animate-fade-in" data-id="${newId}">
                    <div class="absolute top-7 right-7">
                        <button onclick="toggleReviewDropdown(event, this)" class="text-gray-400 hover:text-black transition">
                            <i class="fa fa-ellipsis-h text-xl"></i>
                        </button>
                        <div class="review-dropdown absolute right-0 mt-2 w-32 bg-white border border-gray-100 rounded-xl shadow-xl hidden z-50 overflow-hidden">
                            <button onclick="editReview(this)" class="w-full text-left px-4 py-2.5 text-sm hover:bg-gray-50 flex items-center gap-2 transition-colors">
                                <i class="fa fa-edit text-blue-500"></i> Edit
                            </button>
                            <button onclick="deleteReview(this)" class="w-full text-left px-4 py-2.5 text-sm hover:bg-gray-50 text-red-500 flex items-center gap-2 border-t transition-colors font-medium">
                                <i class="fa fa-trash-alt"></i> Delete
                            </button>
                        </div>
                    </div>
                    <div class="flex text-yellow-400 mb-3 text-sm rating-stars">
                        ${starsHtml}
                    </div>
                    <div class="flex items-center gap-2 mb-3">
                        <h4 class="font-bold text-lg reviewer-name">${userName}</h4>
                        <div class="w-4 h-4 bg-green-500 rounded-full flex items-center justify-center text-[10px] text-white">
                            <i class="fa fa-check"></i>
                        </div>
                    </div>
                    <p class="review-text text-gray-500 text-sm leading-relaxed mb-6 italic">"${content}"</p>
                    <p class="text-gray-400 text-sm font-medium">Posted on <span class="review-date">${date}</span></p>
                </div>
            `;

            const container = document.getElementById('reviews-container');
            container.insertAdjacentHTML('afterbegin', reviewHtml);
            
            // Switch to reviews tab to show the new review
            switchTab('reviews');
            closeReviewModal();
        }

        function resetReviewForm() {
            selectedReviewRating = 0;
            document.getElementById('review-content').value = '';
            const stars = document.querySelectorAll('.star-input');
            stars.forEach(star => {
                star.classList.add('text-gray-200');
                star.classList.remove('text-yellow-400');
            });
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
                    cartItems.classList.remove('items-center', 'justify-center', 'text-gray-400'); // Remove empty styling
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

        function updateMainImage(src) {
            const mainImg = document.getElementById('main-image');
            mainImg.style.opacity = '0.5';
            
            setTimeout(() => {
                mainImg.src = src;
                mainImg.style.opacity = '1';
            }, 150);
        }

        function changeImage(element) {
            updateMainImage(element.src);

            // Update active state on thumbnails
            const thumbnails = document.querySelectorAll('.thumbnail-img');
            thumbnails.forEach(img => {
                img.classList.remove('border-black');
                img.classList.add('border-transparent');
            });

            element.classList.remove('border-transparent');
            element.classList.add('border-black');
        }

        function selectColor(element, imageSrc, colorName) {
            updateMainImage(imageSrc);
            activeColor = colorName;

            // Update active state on color options
            const colorOptions = document.querySelectorAll('.color-option');
            colorOptions.forEach(opt => {
                const icon = opt.querySelector('i');
                if (icon) {
                    icon.classList.remove('block');
                    icon.classList.add('hidden');
                }
            });

            // Show checkmark on the clicked option
            const selectedIcon = element.querySelector('i');
            if (selectedIcon) {
                selectedIcon.classList.remove('hidden');
                selectedIcon.classList.add('block');
            }
        }

        let activeSize = 'L'; // Default size default in blade
        let activeColor = '{{ $detectedColor }}'; // Default color detected from name/variants

        function selectSize(element) {
            activeSize = element.innerText.trim();
            const sizeButtons = document.querySelectorAll('.size-btn');
            sizeButtons.forEach(btn => {
                btn.classList.remove('bg-black', 'text-white');
                btn.classList.add('bg-gray-100', 'text-gray-600', 'hover:bg-gray-200');
            });
            element.classList.remove('bg-gray-100', 'text-gray-600', 'hover:bg-gray-200');
            element.classList.add('bg-black', 'text-white');
        }

        let currentQuantity = 1;
        function updateQuantity(amount) {
            currentQuantity += amount;
            if (currentQuantity < 1) {
                currentQuantity = 1; // Prevent going below 1
            }
            document.getElementById('quantity-display').innerText = currentQuantity;
        }

        function addToCart() {
            @if(!Session::has('user_login'))
                alert('Silahkan login terlebih dahulu untuk menambahkan produk ke keranjang.');
                window.location.href = '{{ route('login') }}';
                return;
            @endif

            // Show red dot indicator
            document.getElementById('cart-indicator').classList.remove('hidden');
            
            const pName = @json($product->nama_produk);
            const pPriceRaw = @json($product->harga);
            
            // Parse numerical price (convert to float first to handle decimals from DB)
            const priceNum = Math.floor(parseFloat(pPriceRaw));
            const subtotal = priceNum * currentQuantity;
            const subtotalFmt = 'Rp' + subtotal.toLocaleString('id-ID').replace(/,/g, '.');
            const pPriceFmt = 'Rp' + priceNum.toLocaleString('id-ID').replace(/,/g, '.');

            // Document elements are already updated via updateCartPopover, we don't need manual HTML replacement here.
            
            // Save to LocalStorage
            let cartStr = localStorage.getItem('cartData');
            let cart = cartStr ? JSON.parse(cartStr) : [];
            cart.push({
                name: pName,
                priceFmt: pPriceFmt,
                subtotalFmt: subtotalFmt,
                subtotalNum: subtotal,
                size: activeSize,
                color: activeColor,
                quantity: currentQuantity,
                image: document.getElementById('main-image').src
            });
            localStorage.setItem('cartData', JSON.stringify(cart));
            
            // Re-render popover and indicator
            updateCartPopover();
            
            // Buka popover sebagai feedback visual
            document.getElementById('cart-popover').classList.remove('hidden');
        }

        function directCheckout() {
            @if(!Session::has('user_login'))
                alert('Silahkan login terlebih dahulu untuk melakukan checkout.');
                window.location.href = '{{ route('login') }}';
                return;
            @endif

            const pName = @json($product->nama_produk);
            const pPriceRaw = @json($product->harga);
            const priceNum = Math.floor(parseFloat(pPriceRaw));
            const subtotal = priceNum * currentQuantity;
            const subtotalFmt = 'Rp' + subtotal.toLocaleString('id-ID').replace(/,/g, '.');
            const pPriceFmt = 'Rp' + priceNum.toLocaleString('id-ID').replace(/,/g, '.');

            let cartStr = localStorage.getItem('cartData');
            let cart = cartStr ? JSON.parse(cartStr) : [];
            cart.push({
                name: pName,
                priceFmt: pPriceFmt,
                subtotalFmt: subtotalFmt,
                subtotalNum: subtotal,
                size: activeSize,
                color: activeColor,
                quantity: currentQuantity,
                image: document.getElementById('main-image').src
            });
            localStorage.setItem('cartData', JSON.stringify(cart));
            
            window.location.href = '{{ route('payment') }}';
        }

        function toggleCart(event) {
            event.preventDefault();
            const popover = document.getElementById('cart-popover');
            popover.classList.toggle('hidden');
        }

        // Close dropdown when clicking outside
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
            @if(!Session::has('user_login'))
                alert('Silahkan login terlebih dahulu untuk melakukan checkout.');
                window.location.href = '{{ route('login') }}';
                return;
            @endif

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

        function switchTab(tab) {
            const detailContent = document.getElementById('content-details');
            const reviewContent = document.getElementById('content-reviews');
            const detailTab = document.getElementById('tab-details');
            const reviewTab = document.getElementById('tab-reviews');

            if (tab === 'details') {
                detailContent.classList.remove('hidden');
                reviewContent.classList.add('hidden');
                detailTab.classList.add('border-black', 'font-semibold', 'text-black');
                detailTab.classList.remove('text-gray-400', 'border-transparent');
                reviewTab.classList.add('text-gray-400', 'border-transparent');
                reviewTab.classList.remove('border-black', 'font-semibold', 'text-black');
            } else if (tab === 'reviews') {
                detailContent.classList.add('hidden');
                reviewContent.classList.remove('hidden');
                reviewTab.classList.add('border-black', 'font-semibold', 'text-black');
                reviewTab.classList.remove('text-gray-400', 'border-transparent');
                detailTab.classList.add('text-gray-400', 'border-transparent');
                detailTab.classList.remove('border-black', 'font-semibold', 'text-black');
            }
        }
    </script>
</body>
</html>
