@extends('layouts.app')

@section('title', 'Dashboard Admin - HP Market')

@section('content')
<div class="container mx-auto px-4 md:px-6 py-10">

    {{-- Welcome Header --}}
    <div class="gradient-primary rounded-3xl p-6 mb-8 relative overflow-hidden fade-in-up">
        <div class="absolute -top-10 -right-10 w-40 h-40 bg-white/10 rounded-full"></div>
        <div class="absolute -bottom-6 right-20 w-24 h-24 bg-white/10 rounded-full"></div>
        <div class="relative flex items-center justify-between gap-4">
            <div>
                <p class="text-white/75 text-sm font-medium mb-1">
                    <i class="fas fa-calendar-day mr-1"></i> {{ date('l, d F Y') }}
                </p>
                <h1 class="text-2xl md:text-3xl font-extrabold text-white">
                    Selamat Datang, {{ session('user')['name'] ?? session('user')['username'] ?? 'Admin' }}! 👋
                </h1>
                <p class="text-white/75 text-sm mt-1">Berikut ringkasan aktivitas HP Market</p>
            </div>
            <div class="hidden md:flex w-16 h-16 bg-white/20 rounded-2xl items-center justify-center text-3xl flex-shrink-0">
                📊
            </div>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        @php
            $stats_cards = [
                ['label'=>'Total Produk',     'value'=>$stats['totalProducts'] ?? 0,     'icon'=>'fa-box-archive',  'bg'=>'bg-purple-50', 'text'=>'text-purple-600', 'format'=>'number'],
                ['label'=>'Total Pengguna',   'value'=>$stats['totalUsers'] ?? 0,         'icon'=>'fa-users',        'bg'=>'bg-blue-50',   'text'=>'text-blue-600',   'format'=>'number'],
                ['label'=>'Total Transaksi',  'value'=>$stats['totalTransactions'] ?? 0,  'icon'=>'fa-bag-shopping', 'bg'=>'bg-amber-50',  'text'=>'text-amber-600',  'format'=>'number'],
                ['label'=>'Total Pendapatan', 'value'=>$stats['totalRevenue'] ?? 0,       'icon'=>'fa-sack-dollar',  'bg'=>'bg-green-50',  'text'=>'text-green-600',  'format'=>'currency'],
            ];
        @endphp
        @foreach($stats_cards as $i => $card)
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 fade-in-up" style="animation-delay: {{ $i * 0.08 }}s">
            <div class="w-10 h-10 {{ $card['bg'] }} rounded-xl flex items-center justify-center mb-3">
                <i class="fas {{ $card['icon'] }} {{ $card['text'] }}"></i>
            </div>
            <p class="text-gray-500 text-xs font-medium">{{ $card['label'] }}</p>
            <p class="font-extrabold text-gray-900 text-2xl mt-0.5">
                @if($card['format'] === 'currency')
                    Rp {{ number_format($card['value'] / 1000000, 1) }}jt
                @else
                    {{ number_format($card['value']) }}
                @endif
            </p>
        </div>
        @endforeach
    </div>

    {{-- Quick Actions --}}
    <h2 class="text-lg font-extrabold text-gray-800 mb-4 fade-in-up">Kelola</h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
        @php
            $actions = [
                ['href'=>route('admin.products'),    'icon'=>'fa-box-archive', 'bg'=>'bg-purple-100', 'text'=>'text-purple-600', 'label'=>'Produk',    'desc'=>'Tambah, edit, atau hapus HP'],
                ['href'=>route('admin.users'),       'icon'=>'fa-users',       'bg'=>'bg-blue-100',   'text'=>'text-blue-600',   'label'=>'Pengguna',  'desc'=>'Lihat & kelola akun pengguna'],
                ['href'=>route('admin.transactions'),'icon'=>'fa-receipt',     'bg'=>'bg-green-100',  'text'=>'text-green-600',  'label'=>'Transaksi', 'desc'=>'Update status pesanan'],
            ];
        @endphp
        @foreach($actions as $action)
        <a href="{{ $action['href'] }}"
            class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 hover:shadow-md hover:border-purple-200 transition group flex items-center justify-between fade-in-up">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 {{ $action['bg'] }} rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                    <i class="fas {{ $action['icon'] }} {{ $action['text'] }} text-xl"></i>
                </div>
                <div>
                    <p class="font-bold text-gray-800">{{ $action['label'] }}</p>
                    <p class="text-xs text-gray-500">{{ $action['desc'] }}</p>
                </div>
            </div>
            <i class="fas fa-chevron-right text-gray-300 group-hover:text-purple-500 group-hover:translate-x-1 transition-all"></i>
        </a>
        @endforeach
    </div>

    {{-- Export Section --}}
    <h2 class="text-lg font-extrabold text-gray-800 mb-4 fade-in-up">Laporan & Export</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">

        {{-- Export Excel: langsung download .xls --}}
        <a href="{{ route('admin.export.excel') }}"
            class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 hover:shadow-md hover:border-green-300 transition group flex items-center gap-4 fade-in-up">
            <div class="w-14 h-14 bg-green-50 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform flex-shrink-0">
                <i class="fas fa-file-excel text-green-600 text-2xl"></i>
            </div>
            <div class="flex-1">
                <p class="font-bold text-gray-800 flex items-center gap-2">
                    Download Excel
                    <span class="text-xs bg-green-100 text-green-700 px-2 py-0.5 rounded-full font-semibold">.xls</span>
                </p>
                <p class="text-xs text-gray-500 mt-0.5">Langsung download file Excel berisi semua data</p>
            </div>
            <i class="fas fa-download text-green-400 group-hover:scale-110 transition-transform flex-shrink-0"></i>
        </a>

        {{-- Export PDF: langsung download .pdf via dompdf --}}
        <a href="{{ route('admin.export.pdf') }}"
            class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 hover:shadow-md hover:border-red-300 transition group flex items-center gap-4 fade-in-up">
            <div class="w-14 h-14 bg-red-50 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform flex-shrink-0">
                <i class="fas fa-file-pdf text-red-500 text-2xl"></i>
            </div>
            <div class="flex-1">
                <p class="font-bold text-gray-800 flex items-center gap-2">
                    Download PDF
                    <span class="text-xs bg-red-100 text-red-600 px-2 py-0.5 rounded-full font-semibold">.pdf</span>
                </p>
                <p class="text-xs text-gray-500 mt-0.5">Langsung download file PDF laporan lengkap</p>
            </div>
            <i class="fas fa-download text-red-400 group-hover:scale-110 transition-transform flex-shrink-0"></i>
        </a>
    </div>

    {{-- Tips --}}
    <div class="bg-purple-50 border border-purple-100 rounded-2xl p-5 fade-in-up">
        <div class="flex items-start gap-3">
            <div class="w-8 h-8 gradient-primary rounded-lg flex items-center justify-center flex-shrink-0">
                <i class="fas fa-lightbulb text-white text-sm"></i>
            </div>
            <div>
                <p class="font-bold text-purple-800 text-sm">Tips Admin</p>
                <p class="text-purple-700 text-xs mt-1">
                    Pastikan stok produk selalu tersedia. Produk dengan stok kurang dari 5 perlu segera diisi ulang.
                    Update status transaksi tepat waktu untuk meningkatkan kepuasan pelanggan.
                </p>
            </div>
        </div>
    </div>

</div>
@endsection
