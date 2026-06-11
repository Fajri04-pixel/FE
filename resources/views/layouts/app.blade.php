<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'HP Market') - Toko HP Premium</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; }

        :root {
            --primary: #1F4E79;
            --primary-light: #3B7EA1;
            --primary-dark: #152C45;
            --accent: #0A9396;
            --surface: #F4F6F9;
            --border: #D1D9E6;
            --text-muted: #6B7280;
        }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: var(--primary); border-radius: 10px; }

        /* Gradient utilities */
        .gradient-primary { background: var(--primary); }
        .gradient-primary-soft { background: rgba(31,78,121,0.08); }
        .text-gradient { color: var(--primary-dark); }

        /* Navbar */
        .navbar-glass {
            background: rgba(255, 255, 255, 0.96);
            backdrop-filter: blur(18px);
            border-bottom: 1px solid rgba(31, 78, 121, 0.12);
        }

        /* Card */
        .card-hover { transition: box-shadow 0.18s ease; }
        .card-hover:hover { box-shadow: 0 10px 30px rgba(15, 23, 42, 0.08); }

        /* Buttons */
        .btn-primary {
            background: var(--primary);
            color: #fff;
            transition: background-color 0.14s ease, box-shadow 0.14s ease;
            border-radius: 10px;
            padding: 10px 14px;
        }
        .btn-primary:hover { background: var(--primary-dark); box-shadow: 0 10px 25px rgba(15, 23, 42, 0.12); }

        .btn-secondary {
            border: 2px solid var(--primary);
            color: var(--primary);
            transition: all 0.22s ease;
        }
        .btn-secondary:hover {
            background: var(--primary);
            color: white;
            transform: translateY(-1px);
        }

        .btn-dark {
            background: #1F2937;
            transition: all 0.22s ease;
        }
        .btn-dark:hover {
            background: #111827;
            transform: translateY(-1px);
            box-shadow: 0 8px 18px rgba(0,0,0,0.18);
        }

        /* Nav items */
        .nav-link {
            position: relative;
            padding-bottom: 2px;
        }
        .nav-link::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--primary);
            transition: width 0.18s ease;
        }
        .nav-link:hover::after { width: 100%; }
        .nav-link.active::after { width: 100%; }

        /* Badges */
        .badge {
            padding: 3px 10px;
            border-radius: 9999px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.3px;
            text-transform: uppercase;
        }
        .badge-success { background: #ECFDF5; color: #059669; border: 1px solid #A7F3D0; }
        .badge-warning { background: #FFFBEB; color: #D97706; border: 1px solid #FDE68A; }
        .badge-danger  { background: #FEF2F2; color: #DC2626; border: 1px solid #FECACA; }
        .badge-info    { background: #EFF6FF; color: #2563EB; border: 1px solid #BFDBFE; }
        .badge-purple  { background: #EAF3FB; color: #1F4E79; border: 1px solid #C7D8EA; }

        .bg-purple-50 { background-color: #EFF4FB !important; }
        .bg-purple-100 { background-color: #DCEAF7 !important; }
        .border-purple-100 { border-color: #C7D8EA !important; }
        .text-purple-400 { color: #4F78A8 !important; }
        .text-purple-500 { color: #336699 !important; }
        .text-purple-600 { color: #1F4E79 !important; }
        .text-purple-700 { color: #173F61 !important; }
        .text-purple-800 { color: #163955 !important; }
        .hover\:bg-purple-50:hover { background-color: #EFF4FB !important; }
        .hover\:text-purple-700:hover { color: #173F61 !important; }
        .bg-purple-50 { background-color: #EFF4FB !important; }
        .bg-purple-100 { background-color: #DCEAF7 !important; }
        .text-purple-700 { color: #173F61 !important; }
        .bg-purple-50 { background-color: #EFF4FB !important; }
        .product-img-bg { background: rgba(31,78,121,0.08); }
        .sidebar-item.active { background: var(--primary); color: white; }

        /* Animations */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(24px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            33%       { transform: translateY(-12px) rotate(2deg); }
            66%       { transform: translateY(-6px) rotate(-1deg); }
        }
        @keyframes pulse-ring {
            0%   { box-shadow: 0 0 0 0 rgba(31, 78, 121, 0.4); }
            70%  { box-shadow: 0 0 0 10px rgba(31, 78, 121, 0); }
            100% { box-shadow: 0 0 0 0 rgba(31, 78, 121, 0); }
        }
        .fade-in-up  { animation: fadeInUp 0.5s ease-out forwards; }
        .float-anim  { animation: float 4s ease-in-out infinite; }

        /* Input */
        .input-field {
            border: 2px solid #E5E7EB;
            transition: all 0.3s ease;
        }
        .input-field:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(31, 78, 121, 0.12);
            outline: none;
        }

        /* Table */
        .table-row-hover:hover { background: #FBFBFB; }

        /* Sidebar item */
        .sidebar-item { transition: all 0.2s ease; }
        .sidebar-item:hover { background: rgba(31, 78, 121, 0.08); padding-left: 1.5rem; }
        .sidebar-item.active { background: linear-gradient(135deg, var(--primary), var(--accent)); color: white; }

        /* Product card image */
        .product-img-bg { background: rgba(31,78,121,0.08); }

        /* Alert */
        .alert-success { background: #ECFDF5; border: 1px solid #6EE7B7; color: #065F46; }
        .alert-error   { background: #FEF2F2; border: 1px solid #FECACA; color: #991B1B; }
        .alert-info    { background: #EFF6FF; border: 1px solid #BFDBFE; color: #1E40AF; }
        .alert-warning { background: #FFFBEB; border: 1px solid #FDE68A; color: #92400E; }

        /* Footer */
        .footer-link { transition: color 0.2s; }
        .footer-link:hover { color: var(--primary-dark); }

        /* Global purple overrides */
        .bg-purple-50 { background-color: #EFF4FB !important; }
        .bg-purple-100 { background-color: #DCEAF7 !important; }
        .bg-purple-200 { background-color: #C2D6E8 !important; }
        .bg-purple-300 { background-color: #A7C2DD !important; }
        .bg-purple-400 { background-color: #8CAECF !important; }
        .bg-purple-500 { background-color: #7397BC !important; }
        .bg-purple-600 { background-color: #5A7FA3 !important; }
        .bg-purple-700 { background-color: #466382 !important; }
        .bg-purple-800 { background-color: #324F65 !important; }
        .bg-purple-900 { background-color: #1F3748 !important; }
        .text-purple-50 { color: #E2EBF6 !important; }
        .text-purple-100 { color: #BAD0E7 !important; }
        .text-purple-200 { color: #95B4D5 !important; }
        .text-purple-300 { color: #7398C3 !important; }
        .text-purple-400 { color: #577DAE !important; }
        .text-purple-500 { color: #376192 !important; }
        .text-purple-600 { color: #1F4E79 !important; }
        .text-purple-700 { color: #173F61 !important; }
        .text-purple-800 { color: #122F47 !important; }
        .text-purple-900 { color: #0E2235 !important; }
        .border-purple-50 { border-color: #EFF4FB !important; }
        .border-purple-100 { border-color: #DCEAF7 !important; }
        .border-purple-200 { border-color: #C2D6E8 !important; }
        .border-purple-300 { border-color: #A7C2DD !important; }
        .border-purple-400 { border-color: #8CAECF !important; }
        .border-purple-500 { border-color: #7397BC !important; }
        .border-purple-600 { border-color: #5A7FA3 !important; }
        .border-purple-700 { border-color: #466382 !important; }
        .border-purple-800 { border-color: #324F65 !important; }
        .border-purple-900 { border-color: #1F3748 !important; }
        .hover\:bg-purple-50:hover { background-color: #EFF4FB !important; }
        .hover\:text-purple-700:hover { color: #173F61 !important; }

        /* Mobile menu */
        #mobileMenu { transition: max-height 0.3s ease, opacity 0.3s ease; }
    </style>

    @stack('styles')
</head>

<body class="bg-gray-50 min-h-screen flex flex-col">

    <!-- Navbar -->
    <nav class="navbar-glass sticky top-0 z-50">
        <div class="container mx-auto px-4 md:px-6">
            <div class="flex justify-between items-center h-16">

                <!-- Logo -->
                <a href="{{ route('home') }}" class="flex items-center space-x-2.5 group">
                    <div class="w-9 h-9 gradient-primary rounded-xl flex items-center justify-center shadow-md group-hover:scale-110 transition-transform">
                        <i class="fas fa-mobile-screen-button text-white text-base"></i>
                    </div>
                    <span class="text-xl font-bold text-gradient">HP Market</span>
                </a>

                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center space-x-6">
                    @if(session('user') && session('user')['role'] == 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="nav-link text-gray-600 hover:text-purple-700 font-medium text-sm transition flex items-center gap-1.5">
                            <i class="fas fa-chart-pie text-purple-500 text-xs"></i> Dashboard
                        </a>
                        <a href="{{ route('admin.users') }}" class="nav-link text-gray-600 hover:text-purple-700 font-medium text-sm transition flex items-center gap-1.5">
                            <i class="fas fa-users text-purple-500 text-xs"></i> Pengguna
                        </a>
                        <a href="{{ route('admin.transactions') }}" class="nav-link text-gray-600 hover:text-purple-700 font-medium text-sm transition flex items-center gap-1.5">
                            <i class="fas fa-receipt text-purple-500 text-xs"></i> Transaksi
                        </a>
                    @elseif(session('user') && session('user')['role'] == 'user')
                        <a href="{{ route('home') }}" class="nav-link text-gray-600 hover:text-purple-700 font-medium text-sm transition flex items-center gap-1.5">
                            <i class="fas fa-house text-purple-500 text-xs"></i> Beranda
                        </a>
                        <a href="{{ route('cart.index') }}" class="nav-link text-gray-600 hover:text-purple-700 font-medium text-sm transition relative flex items-center gap-1.5">
                            <i class="fas fa-bag-shopping text-purple-500 text-xs"></i> Keranjang
                            <span id="cartCount" class="absolute -top-2.5 -right-3 gradient-primary text-white text-[10px] font-bold rounded-full w-4.5 h-4.5 min-w-[18px] h-[18px] flex items-center justify-center px-1 hidden">0</span>
                        </a>
                        <a href="{{ route('transactions.index') }}" class="nav-link text-gray-600 hover:text-purple-700 font-medium text-sm transition flex items-center gap-1.5">
                            <i class="fas fa-clock-rotate-left text-purple-500 text-xs"></i> Riwayat
                        </a>
                    @else
                        <a href="{{ route('home') }}" class="nav-link text-gray-600 hover:text-purple-700 font-medium text-sm transition flex items-center gap-1.5">
                            <i class="fas fa-house text-purple-500 text-xs"></i> Beranda
                        </a>
                    @endif
                </div>

                <!-- User Actions -->
                <div class="flex items-center space-x-3">
                    @if(session('user'))
                        <div class="relative group">
                            <button class="flex items-center space-x-2 focus:outline-none p-1 rounded-xl hover:bg-purple-50 transition">
                                <div class="w-8 h-8 gradient-primary rounded-lg flex items-center justify-center text-white font-bold text-sm shadow">
                                    {{ strtoupper(substr(session('user')['name'] ?? (session('user')['username'] ?? 'U'), 0, 1)) }}
                                </div>
                                <span class="text-gray-700 font-medium text-sm hidden md:block">{{ session('user')['name'] ?? session('user')['username'] }}</span>
                                <i class="fas fa-chevron-down text-gray-400 text-xs hidden md:block"></i>
                            </button>

                            <div class="absolute right-0 mt-2 w-52 bg-white rounded-2xl shadow-xl border border-gray-100 py-2 invisible group-hover:visible opacity-0 group-hover:opacity-100 transition-all z-50">
                                <div class="px-4 py-3 border-b border-gray-100">
                                    <p class="text-sm font-semibold text-gray-800">{{ session('user')['name'] ?? session('user')['username'] }}</p>
                                    <p class="text-xs text-gray-500 truncate">{{ session('user')['email'] }}</p>
                                    @if(session('user')['role'] == 'admin')
                                        <span class="badge badge-purple mt-1 inline-block">Admin</span>
                                    @else
                                        <span class="badge badge-info mt-1 inline-block">Member</span>
                                    @endif
                                </div>
                                <a href="{{ route('profile') }}" class="flex items-center gap-2 px-4 py-2.5 text-gray-700 hover:bg-purple-50 hover:text-purple-700 transition text-sm">
                                    <i class="fas fa-user-circle w-4 text-purple-400"></i> Profil Saya
                                </a>
                                @if(session('user')['role'] == 'user')
                                <a href="{{ route('transactions.index') }}" class="flex items-center gap-2 px-4 py-2.5 text-gray-700 hover:bg-purple-50 hover:text-purple-700 transition text-sm">
                                    <i class="fas fa-box w-4 text-purple-400"></i> Pesanan Saya
                                </a>
                                @endif
                                <hr class="my-1 border-gray-100">
                                <a href="{{ route('logout') }}" class="flex items-center gap-2 px-4 py-2.5 text-red-600 hover:bg-red-50 transition text-sm">
                                    <i class="fas fa-arrow-right-from-bracket w-4"></i> Keluar
                                </a>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-600 hover:text-purple-700 font-medium text-sm transition">
                            Masuk
                        </a>
                    @endif

                    <!-- Mobile Hamburger -->
                    <button id="mobileMenuBtn" class="md:hidden p-2 rounded-lg hover:bg-gray-100 transition">
                        <i class="fas fa-bars text-gray-600"></i>
                    </button>
                </div>
            </div>

            <!-- Mobile Menu -->
            <div id="mobileMenu" class="md:hidden overflow-hidden max-h-0 opacity-0 pb-0">
                <div class="py-3 border-t border-gray-100 space-y-1">
                    @if(session('user') && session('user')['role'] == 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 px-3 py-2.5 rounded-lg text-gray-600 hover:bg-purple-50 hover:text-purple-700 text-sm font-medium">
                            <i class="fas fa-chart-pie w-4 text-purple-400"></i> Dashboard
                        </a>
                        <a href="{{ route('admin.users') }}" class="flex items-center gap-2 px-3 py-2.5 rounded-lg text-gray-600 hover:bg-purple-50 hover:text-purple-700 text-sm font-medium">
                            <i class="fas fa-users w-4 text-purple-400"></i> Pengguna
                        </a>
                        <a href="{{ route('admin.transactions') }}" class="flex items-center gap-2 px-3 py-2.5 rounded-lg text-gray-600 hover:bg-purple-50 hover:text-purple-700 text-sm font-medium">
                            <i class="fas fa-receipt w-4 text-purple-400"></i> Transaksi
                        </a>
                    @elseif(session('user') && session('user')['role'] == 'user')
                        <a href="{{ route('home') }}" class="flex items-center gap-2 px-3 py-2.5 rounded-lg text-gray-600 hover:bg-purple-50 hover:text-purple-700 text-sm font-medium">
                            <i class="fas fa-house w-4 text-purple-400"></i> Beranda
                        </a>
                        <a href="{{ route('cart.index') }}" class="flex items-center gap-2 px-3 py-2.5 rounded-lg text-gray-600 hover:bg-purple-50 hover:text-purple-700 text-sm font-medium">
                            <i class="fas fa-bag-shopping w-4 text-purple-400"></i> Keranjang
                        </a>
                        <a href="{{ route('transactions.index') }}" class="flex items-center gap-2 px-3 py-2.5 rounded-lg text-gray-600 hover:bg-purple-50 hover:text-purple-700 text-sm font-medium">
                            <i class="fas fa-clock-rotate-left w-4 text-purple-400"></i> Riwayat
                        </a>
                    @else
                        <a href="{{ route('home') }}" class="flex items-center gap-2 px-3 py-2.5 rounded-lg text-gray-600 hover:bg-purple-50 hover:text-purple-700 text-sm font-medium">
                            <i class="fas fa-house w-4 text-purple-400"></i> Beranda
                        </a>
                        <a href="{{ route('login') }}" class="flex items-center gap-2 px-3 py-2.5 rounded-lg text-gray-600 hover:bg-purple-50 hover:text-purple-700 text-sm font-medium">
                            <i class="fas fa-arrow-right-to-bracket w-4 text-purple-400"></i> Masuk
                        </a>

                    @endif
                </div>
            </div>
        </div>
    </nav>

    <!-- Flash Messages -->
    @if(session('success') || session('error') || session('info'))
    <div class="container mx-auto px-4 md:px-6 pt-4">
        @if(session('success'))
        <div class="alert-success rounded-xl px-4 py-3 flex items-center gap-3 mb-2 fade-in-up">
            <i class="fas fa-circle-check text-emerald-500"></i>
            <span class="text-sm font-medium">{{ session('success') }}</span>
            <button onclick="this.parentElement.remove()" class="ml-auto text-emerald-700 hover:text-emerald-900"><i class="fas fa-xmark"></i></button>
        </div>
        @endif
        @if(session('error'))
        <div class="alert-error rounded-xl px-4 py-3 flex items-center gap-3 mb-2 fade-in-up">
            <i class="fas fa-circle-xmark text-red-500"></i>
            <span class="text-sm font-medium">{{ session('error') }}</span>
            <button onclick="this.parentElement.remove()" class="ml-auto text-red-700 hover:text-red-900"><i class="fas fa-xmark"></i></button>
        </div>
        @endif
        @if(session('info'))
        <div class="alert-info rounded-xl px-4 py-3 flex items-center gap-3 mb-2 fade-in-up">
            <i class="fas fa-circle-info text-blue-500"></i>
            <span class="text-sm font-medium">{{ session('info') }}</span>
            <button onclick="this.parentElement.remove()" class="ml-auto text-blue-700 hover:text-blue-900"><i class="fas fa-xmark"></i></button>
        </div>
        @endif
    </div>
    @endif

    <!-- Main Content -->
    <main class="flex-1">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-100 mt-20">
        <div class="container mx-auto px-4 md:px-6 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
                <div>
                    <div class="flex items-center space-x-2 mb-4">
                        <div class="w-8 h-8 gradient-primary rounded-lg flex items-center justify-center">
                            <i class="fas fa-mobile-screen-button text-white text-sm"></i>
                        </div>
                        <span class="text-xl font-bold text-gradient">HP Market</span>
                    </div>
                    <p class="text-gray-500 text-sm leading-relaxed">
                        Destinasi belanja smartphone terpercaya. Produk original bergaransi resmi.
                    </p>
                    <div class="flex space-x-3 mt-4">
                        <a href="#" class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center hover:bg-purple-100 hover:text-purple-600 transition text-gray-500 text-sm">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center hover:bg-green-100 hover:text-green-600 transition text-gray-500 text-sm">
                            <i class="fab fa-whatsapp"></i>
                        </a>
                        <a href="#" class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center hover:bg-blue-100 hover:text-blue-600 transition text-gray-500 text-sm">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                    </div>
                </div>

                <div>
                    <h4 class="font-bold text-gray-800 mb-4 text-sm uppercase tracking-wider">Perusahaan</h4>
                    <ul class="space-y-2.5 text-gray-500 text-sm">
                        <li><a href="#" class="footer-link flex items-center gap-1.5"><i class="fas fa-chevron-right text-xs text-purple-400"></i> Tentang Kami</a></li>
                        <li><a href="#" class="footer-link flex items-center gap-1.5"><i class="fas fa-chevron-right text-xs text-purple-400"></i> Blog</a></li>
                        <li><a href="#" class="footer-link flex items-center gap-1.5"><i class="fas fa-chevron-right text-xs text-purple-400"></i> Karir</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="font-bold text-gray-800 mb-4 text-sm uppercase tracking-wider">Layanan</h4>
                    <ul class="space-y-2.5 text-gray-500 text-sm">
                        <li><a href="#" class="footer-link flex items-center gap-1.5"><i class="fas fa-chevron-right text-xs text-purple-400"></i> Garansi Resmi</a></li>
                        <li><a href="#" class="footer-link flex items-center gap-1.5"><i class="fas fa-chevron-right text-xs text-purple-400"></i> Service Center</a></li>
                        <li><a href="#" class="footer-link flex items-center gap-1.5"><i class="fas fa-chevron-right text-xs text-purple-400"></i> Pengiriman</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="font-bold text-gray-800 mb-4 text-sm uppercase tracking-wider">Kontak</h4>
                    <ul class="space-y-2.5 text-gray-500 text-sm">
                        <li class="flex items-center gap-2"><i class="fab fa-whatsapp text-green-500"></i> +62 83869933917</li>
                        <li class="flex items-center gap-2"><i class="fas fa-envelope text-purple-500"></i> cs@hpmarket.com</li>
                        <li class="flex items-center gap-2"><i class="fab fa-instagram text-pink-500"></i> @hpmarket.id</li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-gray-100 pt-6 flex flex-col md:flex-row justify-between items-center gap-3">
                <p class="text-gray-400 text-xs">&copy; 2024 HP Market. Seluruh hak cipta dilindungi.</p>
                <div class="flex items-center gap-4 text-xs text-gray-400">
                    <a href="#" class="hover:text-purple-600 transition">Kebijakan Privasi</a>
                    <a href="#" class="hover:text-purple-600 transition">Syarat & Ketentuan</a>
                </div>
            </div>
        </div>
    </footer>

    <script>
        // Token & API
        const token = '{{ session('token') }}';
        const apiUrl = 'http://localhost:5000/api';

        // Mobile menu toggle
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const mobileMenu    = document.getElementById('mobileMenu');
        if (mobileMenuBtn && mobileMenu) {
            mobileMenuBtn.addEventListener('click', () => {
                const isOpen = mobileMenu.style.maxHeight && mobileMenu.style.maxHeight !== '0px';
                if (isOpen) {
                    mobileMenu.style.maxHeight = '0px';
                    mobileMenu.style.opacity   = '0';
                    mobileMenu.style.paddingBottom = '0';
                } else {
                    mobileMenu.style.maxHeight = '500px';
                    mobileMenu.style.opacity   = '1';
                    mobileMenu.style.paddingBottom = '12px';
                }
            });
        }

        // Cart count
        async function updateCartCount() {
            @if(session('user') && session('user')['role'] == 'user')
            try {
                const res  = await fetch(apiUrl + '/cart', { headers: { 'Authorization': 'Bearer ' + token } });
                const data = await res.json();
                if (data.success) {
                    const count = data.data ? data.data.length : 0;
                    const el = document.getElementById('cartCount');
                    if (el) {
                        el.textContent = count;
                        el.style.display = count > 0 ? 'flex' : 'none';
                        el.classList.toggle('hidden', count === 0);
                    }
                }
            } catch(e) {}
            @endif
        }

        // Add to cart
        async function addToCart(productId, quantity = 1) {
            @if(!session('user'))
                Swal.fire({ title: 'Belum Login', text: 'Silakan login untuk menambahkan ke keranjang', icon: 'warning',
                    confirmButtonText: 'Login Sekarang', confirmButtonColor: '#1F4E79',
                    showCancelButton: true, cancelButtonText: 'Batal'
                }).then(r => { if (r.isConfirmed) window.location.href = '{{ route('login') }}'; });
                return;
            @elseif(session('user')['role'] == 'admin')
                Swal.fire({ title: 'Mode Admin', text: 'Login sebagai user untuk berbelanja.', icon: 'info', confirmButtonColor: '#1F4E79' });
                return;
            @else
                if (!token) {
                    Swal.fire({ title: 'Sesi Berakhir', text: 'Silakan login ulang.', icon: 'error', confirmButtonColor: '#1F4E79' })
                        .then(() => window.location.href = '{{ route('login') }}');
                    return;
                }
                try {
                    const res  = await fetch(apiUrl + '/cart', { method: 'POST',
                        headers: { 'Authorization': 'Bearer ' + token, 'Content-Type': 'application/json' },
                        body: JSON.stringify({ product_id: productId, quantity })
                    });
                    const data = await res.json();
                    if (data.success) {
                        Swal.fire({ title: 'Ditambahkan!', text: 'Produk berhasil masuk ke keranjang.', icon: 'success',
                            timer: 1800, showConfirmButton: false, timerProgressBar: true });
                        updateCartCount();
                        // Jika dipanggil dari buyNow(), caller bisa override dengan redirect sendiri
                        return true;
                    } else {
                        Swal.fire({ title: 'Gagal', text: data.message || 'Gagal menambahkan produk', icon: 'error', confirmButtonColor: '#1F4E79' });
                    }
                } catch(e) {
                    Swal.fire({ title: 'Koneksi Gagal', text: 'Tidak dapat menghubungi server.', icon: 'error', confirmButtonColor: '#1F4E79' });
                }
            @endif
        }

        // Checkout
        async function checkout() {
            @if(!session('user'))
                Swal.fire({ title: 'Belum Login', text: 'Silakan login untuk checkout', icon: 'warning',
                    confirmButtonText: 'Login', confirmButtonColor: '#1F4E79' })
                    .then(r => { if (r.isConfirmed) window.location.href = '{{ route('login') }}'; });
                return;
            @elseif(session('user')['role'] == 'admin')
                Swal.fire({ title: 'Mode Admin', text: 'Login sebagai user untuk berbelanja.', icon: 'info', confirmButtonColor: '#1F4E79' });
                return;
            @else
                const { isConfirmed } = await Swal.fire({
                    title: 'Konfirmasi Checkout',
                    text: 'Pastikan semua produk sudah benar sebelum melanjutkan.',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: '<i class="fas fa-check mr-1"></i> Ya, Checkout',
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#1F4E79',
                    cancelButtonColor: '#9CA3AF'
                });
                if (!isConfirmed) return;

                Swal.fire({ title: 'Memproses...', allowOutsideClick: false, showConfirmButton: false,
                    didOpen: () => Swal.showLoading() });

                try {
                    const res  = await fetch(apiUrl + '/transactions/checkout', { method: 'POST',
                        headers: { 'Authorization': 'Bearer ' + token, 'Content-Type': 'application/json' }
                    });
                    const data = await res.json();
                    if (data.success) {
                        Swal.fire({ title: 'Pesanan Berhasil!', html: `<p>${data.message}</p>`,
                            icon: 'success', confirmButtonText: 'Lihat Pesanan', confirmButtonColor: '#1F4E79' })
                            .then(() => window.location.href = '{{ route('transactions.index') }}');
                        updateCartCount();
                    } else {
                        Swal.fire({ title: 'Checkout Gagal', text: data.message || 'Terjadi kesalahan', icon: 'error', confirmButtonColor: '#1F4E79' });
                    }
                } catch(e) {
                    Swal.fire({ title: 'Koneksi Gagal', text: 'Tidak dapat menghubungi server.', icon: 'error', confirmButtonColor: '#1F4E79' });
                }
            @endif
        }

        document.addEventListener('DOMContentLoaded', () => {
            updateCartCount();
        });
    </script>

    @stack('scripts')
</body>
</html>

