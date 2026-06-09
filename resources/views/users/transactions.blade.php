@extends('layouts.app')

@section('title', 'Riwayat Pesanan - HP Market')

@section('content')
<div class="container mx-auto px-4 md:px-6 py-10">
    <div class="max-w-4xl mx-auto">

        {{-- Header --}}
        <div class="flex items-center gap-3 mb-8 fade-in-up">
            <div class="w-10 h-10 gradient-primary rounded-xl flex items-center justify-center shadow">
                <i class="fas fa-box text-white"></i>
            </div>
            <div>
                <h1 class="text-2xl font-extrabold text-gray-900">Riwayat Pesanan</h1>
                <p class="text-sm text-gray-500">Pantau status semua pesananmu</p>
            </div>
        </div>

        @if(isset($transactions) && count($transactions) > 0)

        {{-- Stats --}}
        @php
            $totalSpent = array_sum(array_column($transactions, 'total_amount'));
            $completed  = count(array_filter($transactions, fn($t) => ($t['status'] ?? '') === 'completed'));
            $pending    = count(array_filter($transactions, fn($t) => ($t['status'] ?? '') === 'pending'));
        @endphp
        <div class="grid grid-cols-3 gap-4 mb-8 fade-in-up">
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 text-center">
                <p class="text-2xl font-extrabold text-purple-700">{{ count($transactions) }}</p>
                <p class="text-xs text-gray-500 mt-0.5">Total Pesanan</p>
            </div>
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 text-center">
                <p class="text-2xl font-extrabold text-green-600">{{ $completed }}</p>
                <p class="text-xs text-gray-500 mt-0.5">Selesai</p>
            </div>
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 text-center">
                <p class="text-lg font-extrabold text-gray-800">
                    Rp {{ number_format($totalSpent / 1000000, 1) }}jt
                </p>
                <p class="text-xs text-gray-500 mt-0.5">Total Belanja</p>
            </div>
        </div>

        {{-- Transaction List --}}
        <div class="space-y-4">
            @foreach($transactions as $transaction)
            @php
                $status    = $transaction['status'] ?? 'pending';
                $statusMap = [
                    'pending'   => ['label'=>'Menunggu Pembayaran', 'bg'=>'bg-amber-50',  'text'=>'text-amber-700',  'border'=>'border-amber-200', 'dot'=>'bg-amber-400',   'icon'=>'fa-clock'],
                    'paid'      => ['label'=>'Sudah Dibayar',       'bg'=>'bg-blue-50',   'text'=>'text-blue-700',   'border'=>'border-blue-200',  'dot'=>'bg-blue-400',    'icon'=>'fa-circle-check'],
                    'shipped'   => ['label'=>'Sedang Dikirim',      'bg'=>'bg-purple-50', 'text'=>'text-purple-700', 'border'=>'border-purple-200','dot'=>'bg-purple-400',  'icon'=>'fa-truck'],
                    'completed' => ['label'=>'Pesanan Selesai',     'bg'=>'bg-green-50',  'text'=>'text-green-700',  'border'=>'border-green-200', 'dot'=>'bg-green-400',   'icon'=>'fa-check-double'],
                    'cancelled' => ['label'=>'Dibatalkan',          'bg'=>'bg-red-50',    'text'=>'text-red-700',    'border'=>'border-red-200',   'dot'=>'bg-red-400',     'icon'=>'fa-ban'],
                ];
                $s         = $statusMap[$status] ?? $statusMap['pending'];
                $items     = $transaction['items'] ?? [];
                $hasItems  = count($items) > 0;
            @endphp
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden fade-in-up">

                {{-- Transaction Header --}}
                <div class="flex flex-wrap justify-between items-center gap-3 px-5 py-4 border-b border-gray-100">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 gradient-primary rounded-xl flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-receipt text-white text-xs"></i>
                        </div>
                        <div>
                            <p class="font-bold text-gray-800 text-sm">
                                {{ $transaction['invoice_number'] ?? ('INV-' . str_pad($transaction['id'] ?? 0, 6, '0', STR_PAD_LEFT)) }}
                            </p>
                            <p class="text-xs text-gray-400">
                                {{ isset($transaction['created_at']) ? date('d M Y, H:i', strtotime($transaction['created_at'])) : '-' }}
                            </p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <span class="{{ $s['bg'] }} {{ $s['text'] }} {{ $s['border'] }} border text-xs font-bold px-3 py-1.5 rounded-full flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 rounded-full {{ $s['dot'] }}"></span>
                            <i class="fas {{ $s['icon'] }} text-[10px]"></i>
                            {{ $s['label'] }}
                        </span>
                    </div>
                </div>

                {{-- Items --}}
                <div class="px-5 py-4">
                    @if($hasItems)
                    <div class="space-y-3">
                        @foreach($items as $item)
                        <div class="flex items-center gap-3">
                            {{-- Foto produk --}}
                            <div class="w-12 h-12 rounded-xl overflow-hidden flex-shrink-0 bg-purple-50 flex items-center justify-center">
                                @if(!empty($item['image_url']))
                                    <img src="{{ $item['image_url'] }}" alt="" class="w-full h-full object-cover"
                                         onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
                                    <span class="text-2xl hidden items-center justify-center w-full h-full">📱</span>
                                @else
                                    <span class="text-2xl">📱</span>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-gray-800 truncate">
                                    {{ $item['product_name'] ?? '-' }}
                                </p>
                                <p class="text-xs text-gray-400">
                                    {{ $item['quantity'] ?? 0 }} × Rp {{ number_format($item['price'] ?? 0, 0, ',', '.') }}
                                </p>
                            </div>
                            <p class="text-sm font-bold text-gray-700 flex-shrink-0">
                                Rp {{ number_format($item['subtotal'] ?? 0, 0, ',', '.') }}
                            </p>
                        </div>
                        @endforeach
                    </div>

                    {{-- Divider + Total --}}
                    <div class="flex justify-between items-center mt-4 pt-4 border-t border-gray-100">
                        <span class="text-sm text-gray-500">{{ count($items) }} produk</span>
                        <div class="text-right">
                            <p class="text-xs text-gray-400">Total Pembayaran</p>
                            <p class="text-lg font-extrabold text-purple-700">
                                Rp {{ number_format($transaction['total_amount'] ?? 0, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>

                    @else
                    {{-- Items kosong (transaksi lama / data tidak lengkap) --}}
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3 text-gray-400">
                            <i class="fas fa-circle-info text-sm"></i>
                            <span class="text-sm">Detail produk tidak tersedia</span>
                        </div>
                        <div class="text-right">
                            <p class="text-xs text-gray-400">Total Pembayaran</p>
                            <p class="text-lg font-extrabold text-purple-700">
                                Rp {{ number_format($transaction['total_amount'] ?? 0, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>
                    @endif
                </div>

                {{-- Action: Bayar sekarang --}}
                @if($status === 'pending')
                <div class="px-5 pb-5">
                    <div class="bg-amber-50 border border-amber-200 rounded-xl p-4">
                        <p class="text-sm font-bold text-amber-800 mb-3 flex items-center gap-2">
                            <i class="fas fa-triangle-exclamation"></i> Selesaikan Pembayaran
                        </p>
                        <div class="grid grid-cols-2 gap-3 text-xs text-amber-700 mb-3">
                            <div class="bg-white rounded-lg p-3">
                                <p class="font-bold text-gray-700">Bank BCA</p>
                                <p class="font-mono text-lg font-bold mt-0.5">1234567890</p>
                                <p class="text-gray-400">a.n HP Market</p>
                            </div>
                            <div class="bg-white rounded-lg p-3">
                                <p class="font-bold text-gray-700">Bank BRI</p>
                                <p class="font-mono text-lg font-bold mt-0.5">0987654321</p>
                                <p class="text-gray-400">a.n HP Market</p>
                            </div>
                        </div>
                        <p class="text-xs text-amber-600 mb-3">
                            <i class="fas fa-info-circle mr-1"></i>
                            Transfer sebesar <strong>Rp {{ number_format($transaction['total_amount'] ?? 0, 0, ',', '.') }}</strong>
                            lalu konfirmasi via WhatsApp.
                        </p>
                        <button onclick="confirmPay('{{ $transaction['invoice_number'] ?? '' }}', '{{ number_format($transaction['total_amount'] ?? 0, 0, ',', '.') }}')"
                            class="w-full bg-amber-500 hover:bg-amber-600 text-white py-2.5 rounded-xl text-sm font-bold transition flex items-center justify-center gap-2">
                            <i class="fab fa-whatsapp"></i> Konfirmasi Pembayaran via WA
                        </button>
                    </div>
                </div>
                @endif

                @if($status === 'shipped')
                <div class="px-5 pb-5">
                    <div class="bg-purple-50 border border-purple-200 rounded-xl p-3 flex items-center gap-2 text-sm text-purple-700">
                        <i class="fas fa-truck animate-bounce"></i>
                        Pesanan sedang dalam pengiriman. Harap tunggu.
                    </div>
                </div>
                @endif

                @if($status === 'completed')
                <div class="px-5 pb-5">
                    <div class="bg-green-50 border border-green-200 rounded-xl p-3 flex items-center gap-2 text-sm text-green-700">
                        <i class="fas fa-check-circle"></i>
                        Pesanan selesai. Terima kasih telah berbelanja!
                    </div>
                </div>
                @endif
            </div>
            @endforeach
        </div>

        @else
        {{-- Empty State --}}
        <div class="flex flex-col items-center justify-center py-20 bg-white rounded-3xl border border-gray-100 shadow-sm fade-in-up text-center">
            <div class="w-24 h-24 bg-purple-50 rounded-3xl flex items-center justify-center mb-5">
                <i class="fas fa-box-open text-4xl text-purple-300"></i>
            </div>
            <h3 class="text-2xl font-extrabold text-gray-700 mb-2">Belum Ada Pesanan</h3>
            <p class="text-gray-400 mb-8 max-w-sm">Mulai belanja HP impianmu dan riwayat pesananmu akan muncul di sini.</p>
            <a href="{{ route('home') }}"
                class="btn-primary text-white px-8 py-3 rounded-xl font-bold inline-flex items-center gap-2">
                <i class="fas fa-bag-shopping"></i> Belanja Sekarang
            </a>
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    function confirmPay(invoice, amount) {
        const msg = encodeURIComponent(
            `Halo Admin HP Market,\n\nSaya ingin konfirmasi pembayaran:\n` +
            `Invoice : ${invoice}\n` +
            `Total   : Rp ${amount}\n\n` +
            `Mohon segera diproses. Terima kasih.`
        );
        Swal.fire({
            title: 'Konfirmasi Pembayaran',
            html: `<p class="text-gray-600 text-sm mb-3">Klik tombol di bawah untuk menghubungi admin via WhatsApp.</p>
                   <p class="text-purple-700 font-bold">Invoice: ${invoice}</p>
                   <p class="text-purple-700 font-bold">Total: Rp ${amount}</p>`,
            icon: 'info',
            showCancelButton:   true,
            confirmButtonText:  '<i class="fab fa-whatsapp mr-1"></i> Buka WhatsApp',
            cancelButtonText:   'Tutup',
            confirmButtonColor: '#25D366',
            cancelButtonColor:  '#9CA3AF',
        }).then(r => {
            if (r.isConfirmed) window.open(`https://wa.me/6281234567890?text=${msg}`, '_blank');
        });
    }
</script>
@endpush

@push('styles')
<style>
    .btn-primary { background: linear-gradient(135deg, #7C3AED 0%, #EC4899 100%); transition: all 0.3s ease; }
    .btn-primary:hover { opacity: 0.9; transform: translateY(-1px); }
</style>
@endpush

@endsection
