@extends('layouts.app')

@section('title', ($product['product_name'] ?? $product['name'] ?? 'Detail Produk') . ' - HP Market')

@section('content')
<div class="container mx-auto px-4 md:px-6 py-10">

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-sm text-gray-500 mb-8 fade-in-up">
        <a href="{{ route('home') }}" class="hover:text-purple-600 transition">Beranda</a>
        <i class="fas fa-chevron-right text-xs text-gray-300"></i>
        <span class="text-gray-700 font-medium">{{ $product['brand'] ?? '' }}</span>
        <i class="fas fa-chevron-right text-xs text-gray-300"></i>
        <span class="text-gray-900 font-semibold truncate max-w-xs">{{ $product['product_name'] ?? $product['name'] ?? 'Produk' }}</span>
    </nav>

    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden fade-in-up">
        <div class="grid md:grid-cols-2 gap-0">

            {{-- Product Image --}}
            <div class="product-img-bg min-h-80 flex items-center justify-center relative p-10 md:rounded-l-3xl overflow-hidden">
                @if(!empty($product['image_url']))
                    <img src="{{ $product['image_url'] }}" alt="{{ $product['product_name'] ?? '' }}"
                         class="w-full h-full object-contain max-h-80">
                @else
                    <div class="float-anim text-center">
                        <span class="text-[160px] leading-none block">ðŸ“±</span>
                    </div>
                @endif

                <div class="absolute top-5 left-5 flex flex-col gap-2">
                    @if(($product['stock'] ?? 0) > 0)
                        <span class="badge badge-success"><i class="fas fa-check text-[9px] mr-0.5"></i> Tersedia</span>
                    @else
                        <span class="badge badge-danger"><i class="fas fa-xmark text-[9px] mr-0.5"></i> Habis</span>
                    @endif
                    <span class="badge badge-purple">{{ $product['brand'] ?? '' }}</span>
                </div>

                {{-- Feature tags --}}
                <div class="absolute bottom-5 left-5 right-5 flex flex-wrap gap-2">
                    <span class="bg-white/80 backdrop-blur text-xs text-gray-700 font-medium px-3 py-1 rounded-full border border-white shadow-sm">
                        <i class="fas fa-shield-halved text-green-500 mr-1"></i> Garansi Resmi
                    </span>
                    <span class="bg-white/80 backdrop-blur text-xs text-gray-700 font-medium px-3 py-1 rounded-full border border-white shadow-sm">
                        <i class="fas fa-box-open text-purple-500 mr-1"></i> Box Segel
                    </span>
                </div>
            </div>

            {{-- Product Details --}}
            <div class="p-6 md:p-10 flex flex-col justify-between">
                <div>
                    <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900 leading-tight mb-4">
                        {{ $product['product_name'] ?? $product['name'] ?? 'Produk' }}
                    </h1>

                    {{-- Price --}}
                    <div class="flex items-end gap-3 mb-6 p-4 bg-purple-50 rounded-2xl">
                        <div>
                            <p class="text-xs text-gray-500 mb-0.5">Harga</p>
                            <span class="text-3xl font-extrabold text-purple-700">
                                Rp {{ number_format($product['price'] ?? 0, 0, ',', '.') }}
                            </span>
                        </div>
                        @if(isset($product['price']) && $product['price'] > 0)
                        <div class="text-right ml-auto">
                            <span class="text-gray-400 line-through text-sm block">
                                Rp {{ number_format(($product['price'] * 1.15), 0, ',', '.') }}
                            </span>
                            <span class="bg-red-100 text-red-600 text-xs font-bold px-2 py-0.5 rounded-full">Hemat 15%</span>
                        </div>
                        @endif
                    </div>

                    {{-- Stock Bar --}}
                    <div class="mb-6">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-sm font-semibold text-gray-700">Stok Tersedia</span>
                            <span class="text-sm font-bold {{ ($product['stock'] ?? 0) > 5 ? 'text-green-600' : 'text-orange-500' }}">
                                {{ $product['stock'] ?? 0 }} unit
                            </span>
                        </div>
                        <div class="h-2 bg-gray-100 rounded-full overflow-hidden">
                            <div class="h-full rounded-full gradient-primary transition-all"
                                style="width: {{ min(100, (($product['stock'] ?? 0) / 20) * 100) }}%"></div>
                        </div>
                    </div>

                    {{-- Description --}}
                    @if(!empty($product['description']))
                    <div class="mb-5">
                        <h3 class="font-bold text-gray-800 mb-2 flex items-center gap-2">
                            <i class="fas fa-align-left text-purple-400 text-sm"></i> Deskripsi
                        </h3>
                        <p class="text-gray-600 text-sm leading-relaxed">{{ $product['description'] }}</p>
                    </div>
                    @endif

                    {{-- Specifications --}}
                    @if(!empty($product['specifications']))
                    <div class="mb-6">
                        <h3 class="font-bold text-gray-800 mb-2 flex items-center gap-2">
                            <i class="fas fa-microchip text-purple-400 text-sm"></i> Spesifikasi
                        </h3>
                        <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                            <p class="text-gray-600 text-sm leading-relaxed whitespace-pre-line">{{ $product['specifications'] }}</p>
                        </div>
                    </div>
                    @endif
                </div>

                {{-- Action Buttons --}}
                <div class="space-y-3 pt-4 border-t border-gray-100">
                    @if(session('user') && session('user')['role'] == 'user')
                        @if(($product['stock'] ?? 0) > 0)
                        <form action="{{ route('cart.add') }}" method="POST" class="flex gap-3">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product['id'] ?? '' }}">
                            <div class="flex items-center border-2 border-gray-200 rounded-xl overflow-hidden">
                                <button type="button" onclick="changeQty(-1)"
                                    class="px-3 py-2.5 hover:bg-gray-100 transition text-gray-600 font-bold">âˆ’</button>
                                <input type="number" name="quantity" id="qtyInput" value="1" min="1"
                                    max="{{ $product['stock'] ?? 0 }}"
                                    class="w-14 text-center font-bold text-gray-800 focus:outline-none text-sm py-2.5 border-x-2 border-gray-200">
                                <button type="button" onclick="changeQty(1)"
                                    class="px-3 py-2.5 hover:bg-gray-100 transition text-gray-600 font-bold">+</button>
                            </div>
                            <button type="submit"
                                class="flex-1 btn-dark text-white py-2.5 rounded-xl font-semibold flex items-center justify-center gap-2 text-sm">
                                <i class="fas fa-bag-shopping"></i> Tambah ke Keranjang
                            </button>
                        </form>
                        <button onclick="addToCart({{ $product['id'] ?? 0 }}, parseInt(document.getElementById('qtyInput').value))"
                            class="btn-primary w-full text-white py-3 rounded-xl font-bold flex items-center justify-center gap-2">
                            <i class="fas fa-bolt"></i> Beli Sekarang
                        </button>
                        @else
                        <button disabled class="w-full bg-gray-100 text-gray-400 py-3 rounded-xl font-semibold cursor-not-allowed">
                            <i class="fas fa-ban mr-2"></i> Stok Habis
                        </button>
                        @endif

                    @elseif(session('user') && session('user')['role'] == 'admin')
                        <div class="grid grid-cols-2 gap-3">
                            <a href="{{ route('admin.products.edit', $product['id']) }}"
                                class="bg-amber-500 hover:bg-amber-600 text-white py-3 rounded-xl font-semibold flex items-center justify-center gap-2 transition">
                                <i class="fas fa-pen-to-square"></i> Edit Produk
                            </a>
                            <button onclick="deleteProductAdmin({{ $product['id'] }})"
                                class="bg-red-500 hover:bg-red-600 text-white py-3 rounded-xl font-semibold flex items-center justify-center gap-2 transition">
                                <i class="fas fa-trash"></i> Hapus
                            </button>
                        </div>

                    @else
                        <a href="{{ route('login') }}"
                            class="btn-primary block text-center text-white py-3 rounded-xl font-bold">
                            <i class="fas fa-arrow-right-to-bracket mr-2"></i> Login untuk Membeli
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Trust Badges --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-8">
        @foreach([
            ['fas fa-shield-halved', 'green', 'Garansi Resmi', '1-2 Tahun'],
            ['fas fa-truck-fast', 'blue', 'Pengiriman Cepat', 'Same Day'],
            ['fas fa-rotate-left', 'amber', 'Mudah Return', '7 Hari'],
            ['fas fa-headset', 'purple', 'CS 24/7', 'Siap Membantu'],
        ] as [$icon, $color, $title, $sub])
        <div class="bg-white rounded-2xl border border-gray-100 p-4 flex items-center gap-3 shadow-sm">
            <div class="w-10 h-10 bg-{{ $color }}-50 rounded-xl flex items-center justify-center flex-shrink-0">
                <i class="{{ $icon }} text-{{ $color }}-600"></i>
            </div>
            <div>
                <p class="font-bold text-gray-800 text-sm">{{ $title }}</p>
                <p class="text-xs text-gray-500">{{ $sub }}</p>
            </div>
        </div>
        @endforeach
    </div>
</div>

@push('scripts')
<script>
    function changeQty(delta) {
        const input = document.getElementById('qtyInput');
        const max   = parseInt(input.max) || 99;
        let val = parseInt(input.value) + delta;
        if (val < 1) val = 1;
        if (val > max) val = max;
        input.value = val;
    }

    @if(session('user') && session('user')['role'] == 'admin')
    const adminProductsUrl = '{{ url('admin/products') }}';
    const csrfToken = '{{ csrf_token() }}';

    async function deleteProductAdmin(id) {
        const { isConfirmed } = await Swal.fire({
            title: 'Hapus Produk?',
            text: 'Tindakan ini tidak dapat dibatalkan.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#EF4444',
            cancelButtonColor: '#9CA3AF'
        });
        if (!isConfirmed) return;
        try {
            const res = await fetch(`${adminProductsUrl}/${id}`, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': csrfToken }
            });
            const data = await res.json();
            if (res.ok && (data.success === undefined || data.success === true)) {
                Swal.fire({ title: 'Dihapus!', icon: 'success', timer: 1500, showConfirmButton: false })
                    .then(() => window.location.href = '{{ route('home') }}');
            } else {
                Swal.fire({ title: 'Gagal!', text: data.message || 'Terjadi kesalahan', icon: 'error', confirmButtonColor: '#1F4E79' });
            }
        } catch (e) {
            Swal.fire({ title: 'Error', text: 'Koneksi gagal', icon: 'error', confirmButtonColor: '#1F4E79' });
        }
    }
    @endif
</script>
@endpush

@endsection

