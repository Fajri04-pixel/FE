@extends('layouts.app')

@section('title', 'Kelola Transaksi - Admin HP Market')

@section('content')
<div class="container mx-auto px-4 md:px-6 py-10">

    {{-- Header --}}
    <div class="flex items-center gap-3 mb-8 fade-in-up">
        <div class="w-10 h-10 gradient-primary rounded-xl flex items-center justify-center shadow">
            <i class="fas fa-receipt text-white"></i>
        </div>
        <div>
            <h1 class="text-2xl font-extrabold text-gray-900">Kelola Transaksi</h1>
            <p class="text-sm text-gray-500">Lihat & perbarui status pesanan pelanggan</p>
        </div>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-8 fade-in-up">
        @php
            $statItems = [
                ['label' => 'Semua',    'value' => $totalTransactions ?? count($transactions), 'bg' => 'bg-gray-50',   'text' => 'text-gray-700'],
                ['label' => 'Pending',  'value' => $pendingCount ?? 0,                          'bg' => 'bg-amber-50',  'text' => 'text-amber-600'],
                ['label' => 'Dibayar',  'value' => $paidCount ?? 0,                             'bg' => 'bg-blue-50',   'text' => 'text-blue-600'],
                ['label' => 'Dikirim',  'value' => $shippedCount ?? 0,                          'bg' => 'bg-purple-50', 'text' => 'text-purple-600'],
                ['label' => 'Revenue',  'value' => $totalRevenue ?? 0,                          'bg' => 'bg-green-50',  'text' => 'text-green-600', 'currency' => true],
            ];
        @endphp
        @foreach($statItems as $s)
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 {{ $s['bg'] }}">
            <p class="text-xs font-semibold {{ $s['text'] }}">{{ $s['label'] }}</p>
            <p class="text-xl font-extrabold text-gray-800 mt-0.5">
                @if(!empty($s['currency']))
                    Rp {{ number_format($s['value'] / 1000000, 1) }}jt
                @else
                    {{ $s['value'] }}
                @endif
            </p>
        </div>
        @endforeach
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden fade-in-up">
        @if(count($transactions) > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="px-5 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Invoice</th>
                        <th class="px-5 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Pelanggan</th>
                        <th class="px-5 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Total</th>
                        <th class="px-5 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-5 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-5 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($transactions as $transaction)
                    @php
                        $s = $transaction['status'] ?? 'pending';
                        $badgeMap = [
                            'pending'   => 'badge-warning',
                            'paid'      => 'badge-info',
                            'shipped'   => 'badge-purple',
                            'completed' => 'badge-success',
                            'cancelled' => 'badge-danger',
                        ];
                        $badgeClass = $badgeMap[$s] ?? 'badge-warning';
                        $statusLabel = ['pending'=>'Pending','paid'=>'Dibayar','shipped'=>'Dikirim','completed'=>'Selesai','cancelled'=>'Batal'][$s] ?? ucfirst($s);
                    @endphp
                    <tr class="table-row-hover transition">
                        <td class="px-5 py-4">
                            <p class="font-semibold text-gray-800 text-sm">{{ $transaction['invoice_number'] ?? '-' }}</p>
                            <p class="text-xs text-gray-400">#{{ $transaction['id'] ?? '-' }}</p>
                        </td>
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 gradient-primary rounded-lg flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                                    {{ strtoupper(substr($transaction['user_name'] ?? 'U', 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-800 text-sm">{{ $transaction['user_name'] ?? '-' }}</p>
                                    <p class="text-xs text-gray-400">{{ $transaction['user_email'] ?? '-' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-4">
                            <span class="font-bold text-purple-700 text-sm">Rp {{ number_format($transaction['total_amount'] ?? 0, 0, ',', '.') }}</span>
                        </td>
                        <td class="px-5 py-4">
                            <span class="badge {{ $badgeClass }}">{{ $statusLabel }}</span>
                        </td>
                        <td class="px-5 py-4">
                            <p class="text-sm text-gray-600">
                                {{ isset($transaction['created_at']) ? date('d M Y', strtotime($transaction['created_at'])) : '-' }}
                            </p>
                            <p class="text-xs text-gray-400">
                                {{ isset($transaction['created_at']) ? date('H:i', strtotime($transaction['created_at'])) : '' }}
                            </p>
                        </td>
                        <td class="px-5 py-4">
                            <a href="{{ route('admin.transactions.show', $transaction['id']) }}"
                                class="w-8 h-8 bg-blue-50 text-blue-600 hover:bg-blue-100 rounded-lg flex items-center justify-center transition"
                                title="Lihat Detail">
                                <i class="fas fa-eye text-xs"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="flex flex-col items-center justify-center py-20 text-center">
            <div class="w-20 h-20 bg-purple-50 rounded-3xl flex items-center justify-center mb-4">
                <i class="fas fa-receipt text-3xl text-purple-300"></i>
            </div>
            <h3 class="text-lg font-bold text-gray-600 mb-1">Belum Ada Transaksi</h3>
            <p class="text-sm text-gray-400">Transaksi dari pelanggan akan muncul di sini</p>
        </div>
        @endif
    </div>
</div>

@push('styles')
<style>
    .table-row-hover:hover { background: #FAFAF9; }
    .gradient-primary { background: linear-gradient(135deg, #1F4E79 0%, #0A9396 100%); }
</style>
@endpush

@endsection

