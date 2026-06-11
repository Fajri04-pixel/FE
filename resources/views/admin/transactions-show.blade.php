@extends('layouts.app')

@section('title', 'Detail Transaksi - Admin HP Market')

@section('content')
<div class="container mx-auto px-4 md:px-6 py-10">

    {{-- Breadcrumb --}}
    <div class="flex items-center gap-2 text-sm text-gray-500 mb-8 fade-in-up">
        <a href="{{ route('admin.dashboard') }}" class="hover:text-purple-600 transition">Dashboard</a>
        <i class="fas fa-chevron-right text-xs text-gray-300"></i>
        <a href="{{ route('admin.transactions') }}" class="hover:text-purple-600 transition">Transaksi</a>
        <i class="fas fa-chevron-right text-xs text-gray-300"></i>
        <span class="text-gray-900 font-semibold">Detail Transaksi</span>
    </div>

    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-6">
            {{ session('error') }}
        </div>
    @endif
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-6">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid gap-6 lg:grid-cols-[1.4fr_0.6fr] mb-8 fade-in-up">
        <div class="space-y-6">
            <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
                    <div>
                        <h1 class="text-2xl font-extrabold text-gray-900">Invoice {{ $transaction['invoice_number'] ?? '-' }}</h1>
                        <p class="text-sm text-gray-500">Pesanan ID #{{ $transaction['id'] ?? '-' }}</p>
                    </div>
                    <span class="inline-flex items-center justify-center rounded-full px-4 py-2 text-xs font-semibold uppercase tracking-wide
                        {{ $transaction['status'] === 'pending' ? 'bg-amber-100 text-amber-700' : '' }}
                        {{ $transaction['status'] === 'paid' ? 'bg-blue-100 text-blue-700' : '' }}
                        {{ $transaction['status'] === 'shipped' ? 'bg-purple-100 text-purple-700' : '' }}
                        {{ $transaction['status'] === 'completed' ? 'bg-green-100 text-green-700' : '' }}
                        {{ $transaction['status'] === 'cancelled' ? 'bg-red-100 text-red-700' : '' }}">
                        {{ ucfirst($transaction['status'] ?? 'pending') }}
                    </span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="rounded-2xl bg-gray-50 p-4">
                        <p class="text-xs text-gray-400 uppercase tracking-wide mb-2">Tanggal Pesanan</p>
                        <p class="font-semibold text-gray-800">{{ isset($transaction['created_at']) ? date('d M Y H:i', strtotime($transaction['created_at'])) : '-' }}</p>
                    </div>
                    <div class="rounded-2xl bg-gray-50 p-4">
                        <p class="text-xs text-gray-400 uppercase tracking-wide mb-2">Metode Pembayaran</p>
                        <p class="font-semibold text-gray-800">{{ $transaction['payment_method'] ?? 'Belum Diketahui' }}</p>
                    </div>
                    <div class="rounded-2xl bg-gray-50 p-4">
                        <p class="text-xs text-gray-400 uppercase tracking-wide mb-2">Total Pembayaran</p>
                        <p class="font-semibold text-purple-700">Rp {{ number_format($transaction['total_amount'] ?? 0, 0, ',', '.') }}</p>
                    </div>
                    <div class="rounded-2xl bg-gray-50 p-4">
                        <p class="text-xs text-gray-400 uppercase tracking-wide mb-2">Status Pembayaran</p>
                        <p class="font-semibold text-gray-800">{{ $transaction['payment_status'] ?? ucfirst($transaction['status'] ?? 'pending') }}</p>
                    </div>
                </div>
            </div>

            @php
                $shippingAddress = trim($transaction['shipping_address'] ?? $transaction['address'] ?? $transaction['user_address'] ?? $transaction['customer_address'] ?? '');
            @endphp
            <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-4">Detail Pelanggan</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="rounded-2xl bg-gray-50 p-4">
                        <p class="text-xs text-gray-400 uppercase tracking-wide mb-2">Nama</p>
                        <p class="font-semibold text-gray-800">{{ $transaction['user_name'] ?? '-' }}</p>
                        <p class="text-sm text-gray-500">{{ $transaction['user_email'] ?? '-' }}</p>
                    </div>
                    <div class="rounded-2xl bg-gray-50 p-4">
                        <p class="text-xs text-gray-400 uppercase tracking-wide mb-2">Nomor Telepon</p>
                        <p class="font-semibold text-gray-800">{{ $transaction['user_phone'] ?? '-' }}</p>
                        <p class="text-xs text-gray-400 uppercase tracking-wide mb-2 mt-4">Alamat</p>
                        <p class="text-sm text-gray-500">{{ $shippingAddress ?: 'Alamat tidak tersedia' }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-bold text-gray-900">Rincian Pesanan</h2>
                    <span class="text-xs text-gray-400">{{ count($transaction['items'] ?? []) }} item</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="text-xs uppercase tracking-wider text-gray-500 bg-gray-50">
                                <th class="px-4 py-3">Produk</th>
                                <th class="px-4 py-3">Harga</th>
                                <th class="px-4 py-3">Jumlah</th>
                                <th class="px-4 py-3">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($transaction['items'] ?? [] as $item)
                            <tr>
                                <td class="px-4 py-4">
                                    <p class="font-semibold text-gray-800">{{ $item['product_name'] ?? $item['name'] ?? '-' }}</p>
                                    <p class="text-xs text-gray-400">SKU: {{ $item['sku'] ?? '-' }}</p>
                                </td>
                                <td class="px-4 py-4">Rp {{ number_format($item['price'] ?? 0, 0, ',', '.') }}</td>
                                <td class="px-4 py-4">{{ $item['quantity'] ?? 0 }}</td>
                                <td class="px-4 py-4 font-semibold">Rp {{ number_format($item['subtotal'] ?? ($item['price'] * $item['quantity'] ?? 0), 0, ',', '.') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-4 py-6 text-center text-sm text-gray-400">Tidak ada item pesanan tersedia.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="rounded-2xl bg-gray-50 p-4">
                        <p class="text-xs uppercase tracking-wide text-gray-400 mb-2">Alamat Pengiriman</p>
                        <p class="font-semibold text-gray-800">{{ $shippingAddress ?: 'Tidak tersedia' }}</p>
                        @if(!empty($transaction['shipping_method']))
                        <p class="text-sm text-gray-500 mt-2">{{ $transaction['shipping_method'] }}</p>
                        @endif
                    </div>
                    <div class="rounded-2xl bg-gray-50 p-4">
                        <p class="text-xs uppercase tracking-wide text-gray-400 mb-2">Ringkasan Biaya</p>
                        <div class="space-y-2 text-sm text-gray-600">
                            <div class="flex justify-between">
                                <span>Subtotal</span>
                                <span>Rp {{ number_format(($transaction['subtotal_amount'] ?? null) ?? (($transaction['total_amount'] ?? 0) - ($transaction['shipping_fee'] ?? $transaction['shipping_cost'] ?? 0) - ($transaction['tax_amount'] ?? $transaction['tax'] ?? 0)), 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Ongkos Kirim</span>
                                <span>Rp {{ number_format($transaction['shipping_fee'] ?? $transaction['shipping_cost'] ?? 0, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Pajak</span>
                                <span>Rp {{ number_format($transaction['tax_amount'] ?? $transaction['tax'] ?? 0, 0, ',', '.') }}</span>
                            </div>
                        </div>
                        <div class="mt-4 pt-4 border-t border-gray-200 flex items-center justify-between font-bold text-gray-900">
                            <span>Total Tagihan</span>
                            <span>Rp {{ number_format($transaction['total_amount'] ?? 0, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                @if(!empty($transaction['notes']))
                <div class="mt-6 rounded-2xl bg-purple-50 p-4 border border-purple-100">
                    <p class="text-xs uppercase tracking-wide text-purple-700 mb-2">Catatan Pesanan</p>
                    <p class="text-sm text-purple-800">{{ $transaction['notes'] }}</p>
                </div>
                @endif
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6 sticky top-6">
                <h2 class="text-lg font-bold text-gray-900 mb-4">Perbarui Status</h2>
                <form action="{{ route('admin.transactions.status', $transaction['id']) }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')
                    <label class="block text-sm font-semibold text-gray-700">Status Transaksi</label>
                    <select name="status" required class="w-full px-4 py-3 rounded-2xl border border-gray-200 text-sm focus:border-purple-500 focus:outline-none">
                        @php
                            $statuses = ['pending' => 'Pending', 'paid' => 'Dibayar', 'shipped' => 'Dikirim', 'completed' => 'Selesai', 'cancelled' => 'Dibatalkan'];
                        @endphp
                        @foreach($statuses as $key => $label)
                            <option value="{{ $key }}" {{ ($transaction['status'] ?? '') === $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="w-full btn-primary text-white px-4 py-3 rounded-2xl font-bold">Simpan Status</button>
                </form>
            </div>

            <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-4">Informasi Tambahan</h2>
                <div class="space-y-3 text-sm text-gray-600">
                    <div class="flex justify-between gap-2">
                        <span class="font-semibold text-gray-800">Order ID</span>
                        <span>#{{ $transaction['id'] ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between gap-2">
                        <span class="font-semibold text-gray-800">Email</span>
                        <span>{{ $transaction['user_email'] ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between gap-2">
                        <span class="font-semibold text-gray-800">Ponsel</span>
                        <span>{{ $transaction['user_phone'] ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between gap-2">
                        <span class="font-semibold text-gray-800">Dibuat</span>
                        <span>{{ isset($transaction['created_at']) ? date('d M Y H:i', strtotime($transaction['created_at'])) : '-' }}</span>
                    </div>
                    <div class="flex justify-between gap-2">
                        <span class="font-semibold text-gray-800">Terakhir diperbarui</span>
                        <span>{{ isset($transaction['updated_at']) ? date('d M Y H:i', strtotime($transaction['updated_at'])) : '-' }}</span>
                    </div>
                </div>
            </div>

            {{-- Bukti Pembayaran --}}
            <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="fas fa-image text-purple-400"></i> Bukti Pembayaran
                </h2>
                @if(!empty($transaction['payment_proof']))
                    <div class="space-y-3">
                        <div class="rounded-2xl overflow-hidden border border-gray-100">
                            <img src="http://localhost:5000/uploads/{{ $transaction['payment_proof'] }}"
                                 alt="Bukti Pembayaran"
                                 class="w-full object-contain max-h-72 bg-gray-50"
                                 onerror="this.parentElement.innerHTML='<p class=\'p-4 text-sm text-gray-400 text-center\'>Gambar tidak dapat ditampilkan</p>'">
                        </div>
                        <a href="http://localhost:5000/uploads/{{ $transaction['payment_proof'] }}"
                           target="_blank"
                           class="flex items-center justify-center gap-2 text-sm font-semibold text-purple-600 hover:text-purple-800 border border-purple-200 rounded-xl py-2 hover:bg-purple-50 transition">
                            <i class="fas fa-arrow-up-right-from-square text-xs"></i> Buka di Tab Baru
                        </a>

                        {{-- Tombol konfirmasi pembayaran --}}
                        @if(($transaction['status'] ?? '') === 'pending')
                        <form action="{{ route('admin.transactions.status', $transaction['id']) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="paid">
                            <button type="submit"
                                class="w-full bg-green-500 hover:bg-green-600 text-white py-2.5 rounded-xl font-bold text-sm transition flex items-center justify-center gap-2">
                                <i class="fas fa-circle-check"></i> Konfirmasi — Tandai Sudah Dibayar
                            </button>
                        </form>
                        @endif
                    </div>
                @else
                    <div class="flex flex-col items-center justify-center py-8 text-center">
                        <div class="w-14 h-14 bg-gray-100 rounded-2xl flex items-center justify-center mb-3">
                            <i class="fas fa-image text-gray-300 text-2xl"></i>
                        </div>
                        <p class="text-sm font-semibold text-gray-500">Belum Ada Bukti</p>
                        <p class="text-xs text-gray-400 mt-1">User belum mengupload bukti transfer</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

