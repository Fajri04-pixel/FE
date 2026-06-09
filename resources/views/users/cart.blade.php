@extends('layouts.app')

@section('title', 'Keranjang Belanja - HP Market')

@section('content')
<div class="container mx-auto px-4 md:px-6 py-10">
    <div class="max-w-5xl mx-auto">

        {{-- Header --}}
        <div class="flex items-center gap-3 mb-8 fade-in-up">
            <div class="w-10 h-10 gradient-primary rounded-xl flex items-center justify-center shadow">
                <i class="fas fa-bag-shopping text-white"></i>
            </div>
            <div>
                <h1 class="text-2xl font-extrabold text-gray-900">Keranjang Belanja</h1>
                <p class="text-sm text-gray-500" id="cartCountText">
                    @if(isset($cart) && count($cart) > 0)
                        <span id="itemCount">{{ count($cart) }}</span> produk dipilih
                    @else
                        Keranjang kosong
                    @endif
                </p>
            </div>
        </div>

        @if(isset($cart) && count($cart) > 0)

        {{-- Simpan data harga dari PHP ke JS --}}
        <script>
            // Data cart dari server — dipakai JS untuk hitung ulang total
            const cartData = {
                @foreach($cart as $item)
                {{ $item['id'] }}: {
                    price:    {{ (float)($item['price'] ?? 0) }},
                    quantity: {{ (int)($item['quantity'] ?? 1) }},
                    stock:    {{ (int)($item['stock'] ?? 99) }},
                    name:     "{{ addslashes($item['product_name'] ?? $item['name'] ?? '') }}",
                },
                @endforeach
            };
            const FREE_SHIPPING_MIN = 500000;
            const SHIPPING_COST     = 25000;
        </script>

        <div class="grid lg:grid-cols-3 gap-6">

            {{-- ── CART ITEMS ── --}}
            <div class="lg:col-span-2 space-y-4">
                @foreach($cart as $item)
                @php
                    $itemId  = $item['id'];
                    $price   = (float)($item['price'] ?? 0);
                    $qty     = (int)($item['quantity'] ?? 1);
                    $sub     = $price * $qty;
                @endphp
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 flex items-center gap-4 fade-in-up cart-item"
                     id="item-{{ $itemId }}"
                     data-id="{{ $itemId }}"
                     data-price="{{ $price }}">

                    {{-- Foto --}}
                    <div class="w-20 h-20 product-img-bg rounded-xl flex-shrink-0 overflow-hidden flex items-center justify-center">
                        @if(!empty($item['image_url']))
                            <img src="{{ $item['image_url'] }}" alt="" class="w-full h-full object-cover"
                                 onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
                            <span class="text-4xl hidden items-center justify-center w-full h-full">📱</span>
                        @else
                            <span class="text-4xl">📱</span>
                        @endif
                    </div>

                    {{-- Info --}}
                    <div class="flex-1 min-w-0">
                        <span class="text-xs font-bold text-purple-600 bg-purple-50 px-2 py-0.5 rounded-full">{{ $item['brand'] ?? '-' }}</span>
                        <h3 class="font-bold text-gray-800 text-sm mt-1 truncate">{{ $item['product_name'] ?? $item['name'] ?? '-' }}</h3>
                        <p class="text-purple-700 font-extrabold text-base mt-0.5">
                            Rp {{ number_format($price, 0, ',', '.') }}
                        </p>
                        <p class="text-xs text-gray-400 mt-0.5">
                            Subtotal:
                            <span class="font-semibold text-gray-700" id="sub-{{ $itemId }}">
                                Rp {{ number_format($sub, 0, ',', '.') }}
                            </span>
                        </p>
                    </div>

                    {{-- Qty + Delete --}}
                    <div class="flex flex-col items-end gap-3 flex-shrink-0">

                        {{-- Tombol hapus (AJAX) --}}
                        <button onclick="removeItem({{ $itemId }}, this)"
                            class="w-7 h-7 bg-red-50 text-red-400 hover:bg-red-100 hover:text-red-600 rounded-lg flex items-center justify-center transition">
                            <i class="fas fa-trash text-xs"></i>
                        </button>

                        {{-- Qty spinner (AJAX) --}}
                        <div class="flex items-center border-2 border-gray-200 rounded-xl overflow-hidden">
                            <button type="button" onclick="changeQty({{ $itemId }}, -1)"
                                class="px-2.5 py-1.5 hover:bg-gray-100 transition text-gray-600 font-bold text-xs select-none">−</button>
                            <input type="number"
                                   id="qty-{{ $itemId }}"
                                   value="{{ $qty }}" min="1" max="{{ (int)($item['stock'] ?? 99) }}"
                                   data-stock="{{ (int)($item['stock'] ?? 99) }}"
                                   class="w-10 text-center text-sm font-bold text-gray-800 focus:outline-none py-1 border-x-2 border-gray-200"
                                   onchange="setQty({{ $itemId }}, this.value)">
                            <button type="button" onclick="changeQty({{ $itemId }}, 1)"
                                class="px-2.5 py-1.5 hover:bg-gray-100 transition text-gray-600 font-bold text-xs select-none">+</button>
                        </div>
                        {{-- Label stok tersedia --}}
                        <p class="text-xs text-gray-400 text-right" id="stockLabel-{{ $itemId }}">
                            Stok:
                            <span class="{{ $qty >= (int)($item['stock'] ?? 99) ? 'text-red-500 font-bold' : 'text-gray-500' }}">
                                {{ (int)($item['stock'] ?? 0) }}
                            </span>
                        </p>
                    </div>
                </div>
                @endforeach

                <div class="mt-2">
                    <a href="{{ route('home') }}"
                        class="inline-flex items-center gap-2 text-purple-600 hover:text-purple-800 font-semibold text-sm transition">
                        <i class="fas fa-arrow-left text-xs"></i> Lanjutkan Belanja
                    </a>
                </div>
            </div>

            {{-- ── ORDER SUMMARY ── --}}
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 sticky top-24">
                    <h3 class="font-extrabold text-gray-800 mb-5 flex items-center gap-2">
                        <i class="fas fa-receipt text-purple-400"></i> Ringkasan Pesanan
                    </h3>

                    {{-- Daftar item (update real-time) --}}
                    <div id="summaryItems" class="space-y-2 pb-4 mb-4 border-b border-gray-100">
                        @php $total = 0; @endphp
                        @foreach($cart as $item)
                        @php
                            $sub    = (float)($item['price'] ?? 0) * (int)($item['quantity'] ?? 0);
                            $total += $sub;
                        @endphp
                        <div class="flex justify-between text-xs text-gray-600" id="summary-{{ $item['id'] }}">
                            <span class="truncate max-w-[130px]" id="sumName-{{ $item['id'] }}">
                                {{ $item['product_name'] ?? '-' }} ×{{ $item['quantity'] ?? 0 }}
                            </span>
                            <span class="font-semibold text-gray-800 ml-2 whitespace-nowrap" id="sumSub-{{ $item['id'] }}">
                                Rp {{ number_format($sub, 0, ',', '.') }}
                            </span>
                        </div>
                        @endforeach
                    </div>

                    {{-- Ongkir --}}
                    <div class="flex justify-between text-sm text-gray-600 mb-1">
                        <span>Ongkos Kirim</span>
                        <span id="shippingText" class="font-semibold">
                            @if($total >= 500000)
                                <span class="text-green-600"><i class="fas fa-tag mr-1"></i> Gratis</span>
                            @else
                                Rp 25.000
                            @endif
                        </span>
                    </div>
                    <p id="freeShippingHint" class="text-xs text-gray-400 mb-3 {{ $total >= 500000 ? 'hidden' : '' }}">
                        Tambah Rp {{ number_format(500000 - $total, 0, ',', '.') }} lagi untuk gratis ongkir
                    </p>

                    {{-- Total --}}
                    <div class="border-t border-gray-100 pt-4 mb-5">
                        <div class="flex justify-between items-center">
                            <span class="font-bold text-gray-800">Total Pembayaran</span>
                            <span id="grandTotal" class="text-2xl font-extrabold text-purple-700">
                                Rp {{ number_format($total < 500000 ? $total + 25000 : $total, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>

                    <button onclick="checkout()"
                        class="btn-primary w-full text-white py-3 rounded-xl font-bold flex items-center justify-center gap-2">
                        <i class="fas fa-bolt"></i> Checkout Sekarang
                    </button>

                    <div class="mt-4 pt-4 border-t border-gray-100 space-y-2">
                        <div class="flex items-center gap-2 text-xs text-gray-500">
                            <i class="fas fa-shield-halved text-green-500"></i> Transaksi aman & terlindungi
                        </div>
                        <div class="flex items-center gap-2 text-xs text-gray-500">
                            <i class="fas fa-rotate-left text-blue-500"></i> Retur mudah dalam 7 hari
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @else
        {{-- Empty Cart --}}
        <div class="flex flex-col items-center justify-center py-20 bg-white rounded-3xl border border-gray-100 shadow-sm fade-in-up text-center">
            <div class="w-24 h-24 bg-purple-50 rounded-3xl flex items-center justify-center mb-5">
                <i class="fas fa-bag-shopping text-4xl text-purple-300"></i>
            </div>
            <h3 class="text-2xl font-extrabold text-gray-700 mb-2">Keranjangmu Kosong</h3>
            <p class="text-gray-400 mb-8 max-w-sm">Temukan HP impianmu dan tambahkan ke keranjang sekarang!</p>
            <a href="{{ route('home') }}"
                class="btn-primary text-white px-8 py-3 rounded-xl font-bold inline-flex items-center gap-2">
                <i class="fas fa-bag-shopping"></i> Mulai Belanja
            </a>
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
// ─── Format angka ke Rupiah ────────────────────────────────────────────────
function formatRp(num) {
    return 'Rp ' + Math.round(num).toLocaleString('id-ID');
}

// ─── Hitung ulang semua total & tampilkan ─────────────────────────────────
function recalcTotal() {
    let subtotal = 0;

    Object.keys(cartData).forEach(id => {
        const item = cartData[id];
        const sub  = item.price * item.quantity;
        subtotal  += sub;

        // Update subtotal per item
        const subEl = document.getElementById('sub-' + id);
        if (subEl) subEl.textContent = formatRp(sub);

        // Update summary di panel kanan
        const sumNameEl = document.getElementById('sumName-' + id);
        const sumSubEl  = document.getElementById('sumSub-' + id);
        if (sumNameEl) sumNameEl.textContent = item.name + ' ×' + item.quantity;
        if (sumSubEl)  sumSubEl.textContent  = formatRp(sub);
    });

    const isFreeShip = subtotal >= FREE_SHIPPING_MIN;
    const shipping   = isFreeShip ? 0 : SHIPPING_COST;
    const grand      = subtotal + shipping;

    // Update ongkir
    const shipEl = document.getElementById('shippingText');
    if (shipEl) {
        shipEl.innerHTML = isFreeShip
            ? '<span class="text-green-600"><i class="fas fa-tag mr-1"></i> Gratis</span>'
            : formatRp(SHIPPING_COST);
    }

    // Update hint gratis ongkir
    const hintEl = document.getElementById('freeShippingHint');
    if (hintEl) {
        if (isFreeShip) {
            hintEl.classList.add('hidden');
        } else {
            hintEl.textContent = 'Tambah ' + formatRp(FREE_SHIPPING_MIN - subtotal) + ' lagi untuk gratis ongkir';
            hintEl.classList.remove('hidden');
        }
    }

    // Update grand total dengan animasi kecil
    const totalEl = document.getElementById('grandTotal');
    if (totalEl) {
        totalEl.classList.add('scale-110', 'text-purple-900');
        totalEl.textContent = formatRp(grand);
        setTimeout(() => totalEl.classList.remove('scale-110', 'text-purple-900'), 300);
    }
}

// ─── Tombol + / - ─────────────────────────────────────────────────────────
function changeQty(id, delta) {
    const input = document.getElementById('qty-' + id);
    if (!input) return;

    const stock = cartData[id] ? cartData[id].stock : parseInt(input.dataset.stock || 99);
    let val = parseInt(input.value) + delta;

    if (val < 1) val = 1;

    // Batasi maksimal sesuai stok
    if (val > stock) {
        val = stock;
        Swal.fire({
            toast:            true,
            position:         'top-end',
            icon:             'warning',
            title:            `Maksimal ${stock} unit (sesuai stok tersedia)`,
            showConfirmButton: false,
            timer:            2000,
            timerProgressBar: true,
        });
    }

    input.value = val;
    setQty(id, val);
}

// ─── Set qty (update cart di backend lalu hitung ulang tampilan) ──────────
let qtyTimer = {};   // debounce per item

function setQty(id, rawVal) {
    const input = document.getElementById('qty-' + id);
    const stock = cartData[id] ? cartData[id].stock : parseInt(input?.dataset.stock || 99);
    let qty     = Math.max(1, parseInt(rawVal) || 1);

    // Paksa tidak melebihi stok
    if (qty > stock) {
        qty = stock;
        Swal.fire({
            toast:            true,
            position:         'top-end',
            icon:             'warning',
            title:            `Maksimal ${stock} unit (sesuai stok tersedia)`,
            showConfirmButton: false,
            timer:            2000,
            timerProgressBar: true,
        });
    }

    if (input) input.value = qty;

    // Update label stok — merah kalau sudah mentok
    const labelEl = document.getElementById('stockLabel-' + id);
    if (labelEl) {
        const spanEl = labelEl.querySelector('span');
        if (spanEl) {
            spanEl.className = qty >= stock
                ? 'text-red-500 font-bold'
                : 'text-gray-500';
        }
    }

    // Update tombol + — disable kalau sudah mentok
    const plusBtn = input ? input.nextElementSibling : null;
    if (plusBtn) {
        plusBtn.disabled        = qty >= stock;
        plusBtn.style.opacity   = qty >= stock ? '0.3' : '1';
        plusBtn.style.cursor    = qty >= stock ? 'not-allowed' : 'pointer';
    }

    // Update data lokal & tampilan LANGSUNG
    if (cartData[id]) {
        cartData[id].quantity = qty;
        recalcTotal();
    }

    // Debounce: kirim ke backend 600ms setelah berhenti
    clearTimeout(qtyTimer[id]);
    qtyTimer[id] = setTimeout(() => updateCartBackend(id, qty), 600);
}

// ─── Init: disable tombol + yang sudah mentok stok saat halaman load ────────
document.addEventListener('DOMContentLoaded', () => {
    Object.keys(cartData).forEach(id => {
        const item  = cartData[id];
        const input = document.getElementById('qty-' + id);
        if (!input) return;
        const plusBtn = input.nextElementSibling;
        if (plusBtn && item.quantity >= item.stock) {
            plusBtn.disabled      = true;
            plusBtn.style.opacity = '0.3';
            plusBtn.style.cursor  = 'not-allowed';
        }
        // Juga update label warna
        const labelEl = document.getElementById('stockLabel-' + id);
        if (labelEl) {
            const spanEl = labelEl.querySelector('span');
            if (spanEl && item.quantity >= item.stock) {
                spanEl.className = 'text-red-500 font-bold';
            }
        }
    });
});

// ─── Kirim update quantity ke backend via AJAX ────────────────────────────
async function updateCartBackend(cartItemId, qty) {
    try {
        const res = await fetch('/cart/' + cartItemId, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN':  document.querySelector('meta[name="csrf-token"]').content,
                'Accept':        'application/json',
            },
            body: JSON.stringify({ quantity: qty }),
        });
        // Tidak perlu reload — tampilan sudah terupdate real-time
    } catch (e) {
        console.error('Gagal update cart:', e);
    }
}

// ─── Hapus item (AJAX) ────────────────────────────────────────────────────
async function removeItem(id, btn) {
    const { isConfirmed } = await Swal.fire({
        title: 'Hapus produk ini?',
        icon:  'question',
        showCancelButton:   true,
        confirmButtonText:  '<i class="fas fa-trash mr-1"></i> Hapus',
        cancelButtonText:   'Batal',
        confirmButtonColor: '#EF4444',
        cancelButtonColor:  '#9CA3AF',
    });
    if (!isConfirmed) return;

    try {
        const res = await fetch('/cart/' + id, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept':       'application/json',
            },
        });

        // Hapus baris item dari DOM
        const row = document.getElementById('item-' + id);
        if (row) {
            row.style.transition = 'all 0.3s ease';
            row.style.opacity    = '0';
            row.style.transform  = 'translateX(30px)';
            setTimeout(() => row.remove(), 300);
        }

        // Hapus dari summary panel
        const sumRow = document.getElementById('summary-' + id);
        if (sumRow) sumRow.remove();

        // Hapus dari cartData & hitung ulang
        delete cartData[id];
        recalcTotal();

        // Update jumlah item di header
        const count  = Object.keys(cartData).length;
        const countEl = document.getElementById('itemCount');
        if (countEl) countEl.textContent = count;
        if (count === 0) location.reload(); // Tampilkan empty state

    } catch (e) {
        Swal.fire({ title: 'Gagal', text: 'Tidak dapat menghapus item.', icon: 'error', confirmButtonColor: '#7C3AED' });
    }
}
</script>
@endpush

@push('styles')
<style>
    .btn-primary { background: linear-gradient(135deg, #7C3AED 0%, #EC4899 100%); transition: all 0.3s ease; }
    .btn-primary:hover { opacity: 0.9; transform: translateY(-1px); box-shadow: 0 8px 25px rgba(124,58,237,0.4); }
    .product-img-bg { background: linear-gradient(135deg, #EDE9FE 0%, #FCE7F3 100%); }
    #grandTotal { transition: all 0.25s ease; }
    #grandTotal.scale-110 { transform: scale(1.1); }
</style>
@endpush

@endsection
