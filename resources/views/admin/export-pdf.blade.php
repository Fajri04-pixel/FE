<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan HP Market - {{ date('d/m/Y') }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            color: #1a1a1a;
            background: #f5f5f5;
            font-size: 12px;
        }

        /* Toolbar tidak relevan untuk PDF download */
        .toolbar { display: none; }

        /* ── Halaman ── */
        .page-wrapper {
            max-width: 900px;
            margin: 20px auto;
            padding: 20px;
        }

        .report-paper {
            background: white;
            padding: 40px;
            box-shadow: 0 2px 16px rgba(0,0,0,0.1);
            border-radius: 8px;
        }

        /* ── Header ── */
        .report-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 3px solid #7C3AED;
            padding-bottom: 20px;
            margin-bottom: 24px;
        }
        .report-header-left h1 {
            font-size: 26px;
            font-weight: 900;
            color: #7C3AED;
            letter-spacing: 1px;
        }
        .report-header-left p {
            color: #666;
            font-size: 12px;
            margin-top: 2px;
        }
        .report-header-right {
            text-align: right;
            font-size: 11px;
            color: #888;
        }
        .report-header-right strong { color: #333; }

        /* ── Stats ── */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 12px;
            margin-bottom: 32px;
        }
        .stat-box {
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 14px 12px;
            text-align: center;
            background: #fafafa;
        }
        .stat-box .val {
            font-size: 20px;
            font-weight: 800;
            color: #7C3AED;
            line-height: 1.2;
        }
        .stat-box .lbl {
            font-size: 10px;
            color: #888;
            margin-top: 4px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* ── Section ── */
        .section { margin-bottom: 36px; }
        .section-title {
            background: linear-gradient(135deg, #7C3AED, #EC4899);
            color: white;
            padding: 10px 16px;
            font-size: 13px;
            font-weight: 700;
            margin-bottom: 12px;
            border-radius: 6px;
            letter-spacing: 0.3px;
        }

        /* ── Table ── */
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
        }
        thead th {
            background: #f3f0ff;
            color: #5b21b6;
            padding: 9px 10px;
            text-align: left;
            font-weight: 700;
            border-bottom: 2px solid #7C3AED;
            white-space: nowrap;
        }
        tbody td {
            padding: 8px 10px;
            border-bottom: 1px solid #f0f0f0;
            vertical-align: top;
        }
        tbody tr:nth-child(even) { background: #faf9ff; }
        tbody tr:hover { background: #f3f0ff; }

        /* Status badges */
        .badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 99px;
            font-size: 10px;
            font-weight: 700;
        }
        .badge-pending   { background: #FEF3C7; color: #92400E; }
        .badge-paid      { background: #DBEAFE; color: #1E40AF; }
        .badge-shipped   { background: #EDE9FE; color: #5B21B6; }
        .badge-completed { background: #D1FAE5; color: #065F46; }
        .badge-cancelled { background: #FEE2E2; color: #991B1B; }

        .empty-row td {
            text-align: center;
            color: #aaa;
            padding: 20px;
            font-style: italic;
        }

        /* ── Footer ── */
        .report-footer {
            margin-top: 40px;
            padding-top: 16px;
            border-top: 1px solid #e5e7eb;
            text-align: center;
            font-size: 10px;
            color: #bbb;
        }

        /* ── Print styles ── */
        @media print {
            body { background: white; }
            .toolbar { display: none !important; }
            .page-wrapper { margin: 0; padding: 0; max-width: 100%; }
            .report-paper { box-shadow: none; border-radius: 0; padding: 20px; }
            tbody tr:hover { background: inherit; }
            .section { page-break-inside: avoid; }
        }
    </style>
</head>
<body>

    {{-- Toolbar dihapus karena file langsung di-download --}}

    <div class="page-wrapper">
        <div class="report-paper">

            {{-- Header --}}
            <div class="report-header">
                <div class="report-header-left">
                    <h1>HP MARKET</h1>
                    <p>Laporan Data Toko Smartphone</p>
                </div>
                <div class="report-header-right">
                    <strong>Tanggal Cetak</strong><br>
                    {{ date('d F Y') }}<br>
                    {{ date('H:i:s') }} WIB
                </div>
            </div>

            {{-- Statistik --}}
            @php
                $totalRevenue = array_sum(array_column($transactions, 'total_amount'));
                $completed    = count(array_filter($transactions, fn($t) => ($t['status'] ?? '') === 'completed'));
            @endphp
            <div class="stats-grid">
                <div class="stat-box">
                    <div class="val">{{ count($users) }}</div>
                    <div class="lbl">Total Pengguna</div>
                </div>
                <div class="stat-box">
                    <div class="val">{{ count($transactions) }}</div>
                    <div class="lbl">Total Transaksi</div>
                </div>
                <div class="stat-box">
                    <div class="val">{{ $stats['totalProducts'] ?? 0 }}</div>
                    <div class="lbl">Total Produk</div>
                </div>
                <div class="stat-box">
                    <div class="val" style="font-size:14px">
                        Rp {{ number_format($totalRevenue / 1000000, 1) }}jt
                    </div>
                    <div class="lbl">Total Pendapatan</div>
                </div>
            </div>

            {{-- Data Pengguna --}}
            <div class="section">
                <div class="section-title">👥 Data Pengguna ({{ count($users) }})</div>
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Telepon</th>
                            <th>Alamat</th>
                            <th>Role</th>
                            <th>Terdaftar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $i => $user)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td><strong>{{ $user['username'] ?? '-' }}</strong></td>
                            <td>{{ $user['email'] ?? '-' }}</td>
                            <td>{{ $user['phone'] ?? '-' }}</td>
                            <td style="max-width:160px">{{ $user['address'] ?? '-' }}</td>
                            <td>
                                @if(($user['role'] ?? '') === 'admin')
                                    <span class="badge badge-shipped">Admin</span>
                                @else
                                    <span class="badge badge-paid">Member</span>
                                @endif
                            </td>
                            <td>{{ substr($user['created_at'] ?? '', 0, 10) }}</td>
                        </tr>
                        @empty
                        <tr class="empty-row"><td colspan="7">Tidak ada data pengguna</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Data Transaksi --}}
            <div class="section">
                <div class="section-title">💳 Data Transaksi ({{ count($transactions) }})</div>
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Invoice</th>
                            <th>Pembeli</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transactions as $i => $t)
                        @php
                            $st = $t['status'] ?? 'pending';
                            $badgeClass = [
                                'pending'   => 'badge-pending',
                                'paid'      => 'badge-paid',
                                'shipped'   => 'badge-shipped',
                                'completed' => 'badge-completed',
                                'cancelled' => 'badge-cancelled',
                            ][$st] ?? 'badge-pending';
                            $stLabel = [
                                'pending'   => 'Pending',
                                'paid'      => 'Dibayar',
                                'shipped'   => 'Dikirim',
                                'completed' => 'Selesai',
                                'cancelled' => 'Batal',
                            ][$st] ?? ucfirst($st);
                        @endphp
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td><strong>{{ $t['invoice_number'] ?? '-' }}</strong></td>
                            <td>
                                {{ $t['user_name'] ?? '-' }}<br>
                                <span style="color:#888;font-size:10px">{{ $t['user_email'] ?? '' }}</span>
                            </td>
                            <td><strong>Rp {{ number_format($t['total_amount'] ?? 0, 0, ',', '.') }}</strong></td>
                            <td><span class="badge {{ $badgeClass }}">{{ $stLabel }}</span></td>
                            <td>{{ substr($t['created_at'] ?? '', 0, 10) }}</td>
                        </tr>
                        @empty
                        <tr class="empty-row"><td colspan="6">Tidak ada data transaksi</td></tr>
                        @endforelse
                    </tbody>
                </table>

                @if(count($transactions) > 0)
                <div style="margin-top:12px; text-align:right; font-size:12px; color:#555">
                    <strong>Total Pendapatan:</strong>
                    <span style="color:#7C3AED; font-size:15px; font-weight:800; margin-left:8px">
                        Rp {{ number_format($totalRevenue, 0, ',', '.') }}
                    </span>
                </div>
                @endif
            </div>

            {{-- Footer --}}
            <div class="report-footer">
                Dokumen ini dibuat otomatis oleh sistem HP Market pada {{ date('d/m/Y H:i:s') }}.<br>
                Untuk menyimpan sebagai PDF: klik Print → pilih "Save as PDF" pada printer.
            </div>

        </div>
    </div>

</body>
</html>
