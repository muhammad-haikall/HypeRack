<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HyperRack - Login & Register</title>
    <!-- Google Fonts: Outfit for headers, Inter for normal text -->
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
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-white text-black">

    <nav class="flex items-center justify-between px-10 py-5 border-b">
        <a href="{{ route('user.home') }}" class="font-outfit font-extrabold text-[2rem] tracking-tighter hover:opacity-80 transition-opacity text-black">HyperRack</a>
        <div class="hidden md:flex space-x-8 font-medium">
            <a href="#" class="hover:text-gray-500">Shop <i class="fa-solid fa-chevron-down text-xs"></i></a>
            <a href="#" class="hover:text-gray-500">On Sale</a>
            <a href="#" class="hover:text-gray-500">New Arrivals</a>
            <a href="#" class="hover:text-gray-500">Brands</a>
        </div>
        <div class="flex items-center space-x-5">
            <i class="fa-solid fa-cart-shopping cursor-pointer"></i>
            <i class="fa-regular fa-circle-user cursor-pointer"></i>
        </div>
    </nav>

    <main class="max-w-6xl mx-auto my-16 px-5">
        
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-8">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-8">{{ session('error') }}</div>
        @endif
        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-8">
                <ul class="list-disc ml-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
        <div class="border-2 border-black p-8">
            <h2 class="text-3xl font-black mb-2">LOGIN</h2>
            <p class="text-sm mb-6">If you have an account, login with your username.</p>
            
            <form action="{{ route('login.post') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block font-bold mb-1">Username</label>
                    <input type="text" name="username" placeholder="Enter Your Username" class="w-full border border-black p-2 placeholder-gray-300 focus:outline-none">
                </div>
                <div class="mb-6">
                    <label class="block font-bold mb-1">Password</label>
                    <input type="password" name="password" placeholder="Enter Your Password" class="w-full border border-black p-2 placeholder-gray-300 focus:outline-none">
                </div>
                <button type="submit" class="w-full bg-black text-white py-3 font-bold hover:bg-gray-800 transition">LOGIN</button>
            </form>
            <div class="text-center mt-4">
                <a href="#" class="text-xs font-medium border-b border-black">Forgot Your Password and Username?</a>
            </div>
        </div>

        <div class="border-2 border-black p-8">
            <h2 class="text-3xl font-black mb-2">Register</h2>
            <p class="text-sm mb-6">Create Your Account if you dont have account</p>
            
            <form action="{{ route('register.post') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block font-bold mb-1">Username</label>
                    <input type="text" name="username" placeholder="Enter Your Username" class="w-full border border-black p-2 placeholder-gray-300 focus:outline-none">
                </div>
                <div class="mb-4">
                    <label class="block font-bold mb-1">Email Address</label>
                    <input type="email" name="email" placeholder="Enter Your Username" class="w-full border border-black p-2 placeholder-gray-300 focus:outline-none">
                </div>
                <div class="mb-4">
                    <label class="block font-bold mb-1">Password</label>
                    <input type="password" name="password" placeholder="Enter Your Username" class="w-full border border-black p-2 placeholder-gray-300 focus:outline-none">
                </div>
                <div class="mb-6">
                    <label class="block font-bold mb-1">Confirm Password</label>
                    <input type="password" name="password_confirmation" placeholder="Enter Your Password" class="w-full border border-black p-2 placeholder-gray-300 focus:outline-none">
                </div>
                <button type="submit" class="w-full bg-black text-white py-3 font-bold hover:bg-gray-800 transition">Register</button>
            </form>
            <div class="text-center mt-4">
                <a href="#" class="text-xs font-medium border-b border-black">Forgot Your Password and Username?</a>
            </div>
        </div>

        </div>
    </main>

    <div class="max-w-6xl mx-auto bg-black text-white p-10 rounded-3xl flex flex-col md:row items-center justify-between relative z-10 translate-y-1/2">
        <h2 class="text-4xl font-black w-full md:w-1/2 leading-tight">STAY UPTO DATE ABOUT OUR LATEST OFFERS</h2>
        <div class="w-full md:w-1/3 space-y-3 mt-5 md:mt-0">
            <div class="relative">
                <input type="text" placeholder="Enter your email address" class="w-full py-3 px-12 rounded-full text-black">
                <i class="fa-regular fa-envelope absolute left-4 top-4 text-gray-400"></i>
            </div>
            <button class="w-full py-3 bg-white text-black font-bold rounded-full">Subscribe to Newsletter</button>
        </div>
    </div>

    <footer class="bg-gray-100 pt-32 pb-10 px-10">
        <div class="max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-5 gap-10 border-b pb-10">
            <div class="col-span-1 md:col-span-1">
                <h3 class="font-outfit font-black text-2xl mb-4 italic uppercase">HyperRack</h3>
                <p class="text-gray-500 text-sm mb-5">We have clothes that suits your style and which you're proud to wear. From women to men.</p>
                <div class="flex space-x-3">
                    <i class="fa-brands fa-twitter p-2 bg-white rounded-full border"></i>
                    <i class="fa-brands fa-facebook p-2 bg-black text-white rounded-full"></i>
                    <i class="fa-brands fa-instagram p-2 bg-white rounded-full border"></i>
                    <i class="fa-brands fa-github p-2 bg-white rounded-full border"></i>
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
        <div class="max-w-6xl mx-auto flex flex-col md:row justify-between items-center mt-5 text-sm text-gray-500">
            <p>Shop.co © 2000-2023, All Rights Reserved</p>
            <div class="flex space-x-2 mt-3 md:mt-0">
                <img src="https://upload.wikimedia.org/wikipedia/commons/5/5e/Visa_Inc._logo.svg" class="h-8 border bg-white px-2 rounded">
                </div>
        </div>
    </footer>

</body>
</html>
