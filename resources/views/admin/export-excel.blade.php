@php
// Blade tidak akan parse tag XML di dalam @php block
// Kita render manual supaya tidak ada konflik dengan Blade component parser
$totalRevenue = array_sum(array_column($transactions, 'total_amount'));

$statusLabel = [
    'pending'   => 'Pending',
    'paid'      => 'Dibayar',
    'shipped'   => 'Dikirim',
    'completed' => 'Selesai',
    'cancelled' => 'Batal',
];
@endphp
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<style>
    body  { font-family: Arial, sans-serif; font-size: 11pt; }
    table { border-collapse: collapse; width: 100%; margin-bottom: 20px; }
    th {
        background-color: #7C3AED;
        color: white;
        font-weight: bold;
        padding: 8px 12px;
        border: 1px solid #6D28D9;
        text-align: left;
    }
    td { padding: 6px 12px; border: 1px solid #D1D5DB; }
    tr:nth-child(even) td { background-color: #F5F3FF; }

    .title-row td   { background-color: #1E1B4B; color: white; font-size: 14pt; font-weight: bold; padding: 14px 12px; }
    .sub-row td     { background-color: #6B7280; color: white; font-size: 10pt; padding: 5px 12px; }
    .section-row td { background-color: #EDE9FE; font-weight: bold; font-size: 11pt; color: #5B21B6; }
    .sum-label      { font-weight: bold; background-color: #F9FAFB !important; }
    .sum-value      { font-weight: bold; color: #7C3AED; }
    .total-row td   { font-weight: bold; background-color: #EDE9FE; color: #5B21B6; }

    .badge-admin     { color: #5B21B6; background-color: #EDE9FE; font-weight: bold; text-align: center; }
    .badge-user      { color: #1E40AF; background-color: #DBEAFE; font-weight: bold; text-align: center; }
    .badge-pending   { color: #92400E; background-color: #FEF3C7; font-weight: bold; text-align: center; }
    .badge-paid      { color: #1E40AF; background-color: #DBEAFE; font-weight: bold; text-align: center; }
    .badge-shipped   { color: #5B21B6; background-color: #EDE9FE; font-weight: bold; text-align: center; }
    .badge-completed { color: #065F46; background-color: #D1FAE5; font-weight: bold; text-align: center; }
    .badge-cancelled { color: #991B1B; background-color: #FEE2E2; font-weight: bold; text-align: center; }

    .center  { text-align: center; }
    .right   { text-align: right; }
    .empty   { text-align: center; color: #9CA3AF; font-style: italic; }
    .gap-row td { padding: 4px; border: none; }
</style>
</head>
<body>

{{-- ══ JUDUL ══ --}}
<table>
    <tr class="title-row">
        <td colspan="7">HP MARKET — Laporan Data</td>
    </tr>
    <tr class="sub-row">
        <td colspan="7">Dibuat: {{ date('d/m/Y H:i:s') }}</td>
    </tr>
</table>

{{-- ══ RINGKASAN ══ --}}
<table>
    <tr class="section-row"><td colspan="4">RINGKASAN</td></tr>
    <tr>
        <td class="sum-label">Total Produk</td>
        <td class="sum-value">{{ $stats['totalProducts'] ?? 0 }}</td>
        <td class="sum-label">Total Pengguna</td>
        <td class="sum-value">{{ count($users) }}</td>
    </tr>
    <tr>
        <td class="sum-label">Total Transaksi</td>
        <td class="sum-value">{{ count($transactions) }}</td>
        <td class="sum-label">Total Pendapatan</td>
        <td class="sum-value">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</td>
    </tr>
</table>

{{-- ══ DATA PENGGUNA ══ --}}
<table>
    <tr class="section-row">
        <td colspan="7">DATA PENGGUNA ({{ count($users) }} akun)</td>
    </tr>
    <tr>
        <th>No</th>
        <th>Username</th>
        <th>Email</th>
        <th>Telepon</th>
        <th>Alamat</th>
        <th>Role</th>
        <th>Terdaftar</th>
    </tr>
    @forelse($users as $i => $u)
    @php $isAdmin = ($u['role'] ?? '') === 'admin'; @endphp
    <tr>
        <td class="center">{{ $i + 1 }}</td>
        <td style="font-weight:bold">{{ $u['username'] ?? '-' }}</td>
        <td>{{ $u['email'] ?? '-' }}</td>
        <td>{{ $u['phone'] ?? '-' }}</td>
        <td>{{ $u['address'] ?? '-' }}</td>
        <td class="{{ $isAdmin ? 'badge-admin' : 'badge-user' }}">
            {{ $isAdmin ? 'Admin' : 'Member' }}
        </td>
        <td>{{ substr($u['created_at'] ?? '', 0, 10) }}</td>
    </tr>
    @empty
    <tr><td colspan="7" class="empty">Tidak ada data pengguna</td></tr>
    @endforelse
</table>

{{-- ══ DATA TRANSAKSI ══ --}}
<table>
    <tr class="section-row">
        <td colspan="7">DATA TRANSAKSI ({{ count($transactions) }} transaksi)</td>
    </tr>
    <tr>
        <th>No</th>
        <th>Invoice</th>
        <th>Pembeli</th>
        <th>Email Pembeli</th>
        <th>Total (Rp)</th>
        <th>Status</th>
        <th>Tanggal</th>
    </tr>
    @forelse($transactions as $i => $t)
    @php
        $st      = $t['status'] ?? 'pending';
        $stLabel = $statusLabel[$st] ?? ucfirst($st);
    @endphp
    <tr>
        <td class="center">{{ $i + 1 }}</td>
        <td style="font-weight:bold">{{ $t['invoice_number'] ?? '-' }}</td>
        <td>{{ $t['user_name'] ?? '-' }}</td>
        <td>{{ $t['user_email'] ?? '-' }}</td>
        <td class="right" style="font-weight:bold">{{ number_format($t['total_amount'] ?? 0, 0, ',', '.') }}</td>
        <td class="badge-{{ $st }}">{{ $stLabel }}</td>
        <td>{{ substr($t['created_at'] ?? '', 0, 10) }}</td>
    </tr>
    @empty
    <tr><td colspan="7" class="empty">Tidak ada data transaksi</td></tr>
    @endforelse

    @if(count($transactions) > 0)
    <tr class="total-row">
        <td colspan="4" class="right">TOTAL PENDAPATAN</td>
        <td class="right">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</td>
        <td colspan="2"></td>
    </tr>
    @endif
</table>

</body>
</html>
