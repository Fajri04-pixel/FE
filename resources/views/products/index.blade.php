@extends('layouts.app')

@section('title', 'HP Market - Toko Smartphone Premium')

@section('content')

{{-- Hero Section --}}
<section class="relative overflow-hidden bg-white">
    <div class="absolute inset-0 gradient-primary-soft opacity-60"></div>
    <div class="container mx-auto px-4 md:px-6 py-14 md:py-20 relative">
        <div class="flex flex-col md:flex-row items-center justify-between gap-10">
            <div class="max-w-xl fade-in-up">
                <div class="inline-flex items-center gap-2 bg-purple-100 text-purple-700 text-xs font-semibold px-3 py-1.5 rounded-full mb-5">
                    <span class="w-1.5 h-1.5 bg-purple-600 rounded-full animate-pulse"></span>
                    Produk Original Bergaransi Resmi
                </div>
                <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 leading-tight mb-4">
                    Temukan <span class="text-gradient">Smartphone</span><br>Impianmu
                </h1>
                <p class="text-gray-500 text-lg mb-8">Ratusan pilihan HP premium dari brand terkemuka dunia. Harga terbaik, garansi terpercaya.</p>
                <div class="flex flex-wrap gap-4">
                    <a href="#products" class="btn-primary text-white px-6 py-3 rounded-xl font-semibold inline-flex items-center gap-2">
                        <i class="fas fa-bag-shopping"></i> Belanja Sekarang
                    </a>
                    @if(!session('user'))
                    <a href="{{ route('register') }}" class="btn-secondary px-6 py-3 rounded-xl font-semibold inline-flex items-center gap-2">
                        <i class="fas fa-user-plus"></i> Daftar Gratis
                    </a>
                    @endif
                </div>
            </div>
            <div class="hidden md:flex items-center justify-center relative">
                <div class="w-56 h-56 gradient-primary rounded-full flex items-center justify-center shadow-2xl float-anim">
                    <span class="text-8xl">📱</span>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Search Bar --}}
<section id="products" class="bg-white border-b border-gray-100 sticky top-16 z-40">
    <div class="container mx-auto px-4 md:px-6 py-4">
        <form action="{{ route('home') }}" method="GET" class="flex gap-3 max-w-2xl mx-auto">
            <div class="flex-1 relative">
                <i class="fas fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Cari brand, model, atau spesifikasi..."
                    class="input-field w-full pl-11 pr-4 py-2.5 rounded-xl bg-gray-50 text-sm">
            </div>
            <button type="submit" class="btn-primary text-white px-5 py-2.5 rounded-xl text-sm font-semibold flex items-center gap-1.5">
                <i class="fas fa-magnifying-glass"></i> Cari
            </button>
            @if(request('search'))
            <a href="{{ route('home') }}" class="bg-gray-200 text-gray-700 px-4 py-2.5 rounded-xl text-sm font-semibold hover:bg-gray-300 transition flex items-center">
                <i class="fas fa-xmark"></i>
            </a>
            @endif
        </form>
    </div>
</section>

<div class="container mx-auto px-4 md:px-6 py-10">

    {{-- Search Result Info --}}
    @if(request('search'))
    <div class="mb-6 flex items-center gap-3">
        <div class="flex-1 h-px bg-gray-200"></div>
        <div class="flex items-center gap-2 bg-purple-50 border border-purple-200 text-purple-700 px-4 py-2 rounded-full text-sm font-medium">
            <i class="fas fa-magnifying-glass text-xs"></i>
            Hasil untuk <strong>"{{ request('search') }}"</strong> — <strong>{{ count($products) }}</strong> produk
        </div>
        <div class="flex-1 h-px bg-gray-200"></div>
    </div>
    @endif

    {{-- Products Grid --}}
    @if(count($products) > 0)
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
        @foreach($products as $index => $product)
        <div class="bg-white rounded-2xl shadow-sm overflow-hidden card-hover fade-in-up border border-gray-100 group"
             style="animation-delay: {{ ($index % 8) * 0.05 }}s">

            {{-- Product Image --}}
            <div class="relative product-img-bg h-52 flex items-center justify-center overflow-hidden">
                @if(!empty($product['image_url']))
                    <img src="{{ $product['image_url'] }}"
                         alt="{{ $product['product_name'] ?? 'Produk' }}"
                         class="w-full h-full object-cover"
                         onerror="this.onerror=null;this.style.display='none';document.getElementById('emoji-{{ $index }}').style.display='flex'">
                    <span id="emoji-{{ $index }}" class="text-8xl absolute inset-0 items-center justify-center" style="display:none">📱</span>
                @else
                    <span class="text-8xl float-anim" style="animation-delay: {{ ($index % 4) * 0.3 }}s">📱</span>
                @endif

                {{-- Stock Badge --}}
                @if(($product['stock'] ?? 0) > 0)
                    <span class="absolute top-3 left-3 badge badge-success">
                        <i class="fas fa-check text-[9px] mr-0.5"></i> Stok {{ $product['stock'] }}
                    </span>
                @else
                    <span class="absolute top-3 left-3 badge badge-danger">
                        <i class="fas fa-xmark text-[9px] mr-0.5"></i> Habis
                    </span>
                @endif
            </div>

            {{-- Product Info --}}
            <div class="p-4">
                <span class="badge badge-purple">{{ $product['brand'] ?? 'Brand' }}</span>
                <h3 class="font-bold text-gray-800 text-sm mt-2 mb-1 leading-snug line-clamp-2">
                    {{ $product['product_name'] ?? $product['name'] ?? 'Produk' }}
                </h3>
                @if(!empty($product['description']))
                <p class="text-xs text-gray-400 mb-3 line-clamp-1">{{ $product['description'] }}</p>
                @endif

                <p class="text-xl font-extrabold text-purple-700 mb-4">
                    Rp {{ number_format($product['price'] ?? 0, 0, ',', '.') }}
                </p>

                @if(($product['stock'] ?? 0) > 0)
                <button onclick="addToCart({{ $product['id'] }}, 1)"
                    class="btn-dark w-full text-white py-2.5 rounded-xl text-sm font-semibold flex items-center justify-center gap-2">
                    <i class="fas fa-bag-shopping text-xs"></i> Tambah ke Keranjang
                </button>
                @else
                <button disabled class="w-full bg-gray-100 text-gray-400 py-2.5 rounded-xl text-sm font-medium cursor-not-allowed">
                    <i class="fas fa-ban mr-1"></i> Stok Habis
                </button>
                @endif

                <a href="{{ route('product.show', $product['id']) }}"
                   class="block text-center mt-2 text-purple-600 text-xs font-medium hover:text-purple-800 transition">
                    Lihat Detail <i class="fas fa-arrow-right text-[10px] ml-0.5"></i>
                </a>
            </div>
        </div>
        @endforeach
    </div>

    @else
    {{-- Empty State --}}
    <div class="flex flex-col items-center justify-center py-20 text-center">
        <div class="w-24 h-24 bg-purple-50 rounded-3xl flex items-center justify-center mb-5">
            @if(request('search'))
                <i class="fas fa-magnifying-glass text-4xl text-purple-300"></i>
            @else
                <i class="fas fa-store text-4xl text-purple-300"></i>
            @endif
        </div>
        @if(request('search'))
            <h3 class="text-2xl font-bold text-gray-700 mb-2">Produk Tidak Ditemukan</h3>
            <p class="text-gray-400 mb-6 max-w-sm">Coba kata kunci lain atau lihat semua produk</p>
            <a href="{{ route('home') }}" class="btn-primary text-white px-6 py-2.5 rounded-xl font-semibold inline-flex items-center gap-2">
                <i class="fas fa-arrow-left"></i> Semua Produk
            </a>
        @else
            <h3 class="text-2xl font-bold text-gray-700 mb-2">Belum Ada Produk</h3>
            <p class="text-gray-400 mb-6 max-w-sm">
                Produk belum tersedia. Tambahkan produk melalui Postman atau panel admin.
            </p>
            @if(session('user') && session('user')['role'] == 'admin')
            <a href="{{ route('admin.products') }}" class="btn-primary text-white px-6 py-2.5 rounded-xl font-semibold inline-flex items-center gap-2">
                <i class="fas fa-plus"></i> Tambah Produk
            </a>
            @endif
        @endif
    </div>
    @endif
</div>

@endsection
