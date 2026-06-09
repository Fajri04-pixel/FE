<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    private $apiUrl = 'http://localhost:5000/api';

    private function api()
    {
        return Http::withHeaders([
            'Authorization' => 'Bearer ' . session('token')
        ])->timeout(10);
    }

    // ═══════════════════════════════════════════════════════════════════
    // DASHBOARD
    // ═══════════════════════════════════════════════════════════════════

    public function dashboard()
    {
        try {
            $response = $this->api()->get($this->apiUrl . '/admin/dashboard');
            $stats    = $response->successful() ? ($response->json()['data'] ?? []) : [];
        } catch (\Exception $e) {
            Log::error('Admin dashboard: ' . $e->getMessage());
            $stats = [];
        }

        return view('admin.dashboard', compact('stats'));
    }

    // ═══════════════════════════════════════════════════════════════════
    // PRODUCT MANAGEMENT
    // ═══════════════════════════════════════════════════════════════════

    public function products()
    {
        try {
            $response = Http::timeout(10)->get($this->apiUrl . '/products');
            $products = $response->successful() ? ($response->json()['data'] ?? []) : [];
        } catch (\Exception $e) {
            Log::error('Admin products list: ' . $e->getMessage());
            $products = [];
        }

        return view('admin.products', compact('products'));
    }

    public function createProduct()
    {
        return view('admin.products-create');
    }

    public function storeProduct(Request $request)
    {
        $request->validate([
            'product_name'   => 'required|string|max:200',
            'brand'          => 'required|string|max:100',
            'price'          => 'required|numeric|min:0',
            'stock'          => 'required|integer|min:0',
            'description'    => 'nullable|string',
            'specifications' => 'nullable|string',
            'image'          => 'nullable|image|max:5120',
        ]);

        try {
            $http = $this->api();

            if ($request->hasFile('image')) {
                $response = $http->attach(
                    'image',
                    file_get_contents($request->file('image')->getRealPath()),
                    $request->file('image')->getClientOriginalName()
                )->post($this->apiUrl . '/products', [
                    'product_name'   => $request->product_name,
                    'brand'          => $request->brand,
                    'price'          => (int) $request->price,
                    'stock'          => (int) $request->stock,
                    'description'    => $request->description ?? '',
                    'specifications' => $request->specifications ?? '',
                ]);
            } else {
                $response = $http->post($this->apiUrl . '/products', [
                    'product_name'   => $request->product_name,
                    'brand'          => $request->brand,
                    'price'          => (int) $request->price,
                    'stock'          => (int) $request->stock,
                    'description'    => $request->description ?? '',
                    'specifications' => $request->specifications ?? '',
                ]);
            }

            $data = $response->json();

            if ($response->successful() && ($data['success'] ?? false)) {
                return redirect()->route('admin.products')->with('success', 'Produk berhasil ditambahkan!');
            }

            return back()->with('error', $data['message'] ?? 'Gagal menambahkan produk')->withInput();

        } catch (\Exception $e) {
            Log::error('Admin storeProduct: ' . $e->getMessage());
            return back()->with('error', 'Koneksi ke server gagal.')->withInput();
        }
    }

    public function editProduct($id)
    {
        try {
            $response = $this->api()->get($this->apiUrl . '/products/' . $id);
            $product  = $response->successful() ? ($response->json()['data'] ?? null) : null;
        } catch (\Exception $e) {
            Log::error('Admin editProduct: ' . $e->getMessage());
            $product = null;
        }

        if (!$product) {
            return redirect()->route('admin.products')->with('error', 'Produk tidak ditemukan');
        }

        return view('admin.products-edit', compact('product'));
    }

    public function updateProduct(Request $request, $id)
    {
        $request->validate([
            'product_name'   => 'required|string|max:200',
            'brand'          => 'required|string|max:100',
            'price'          => 'required|numeric|min:0',
            'stock'          => 'required|integer|min:0',
            'description'    => 'nullable|string',
            'specifications' => 'nullable|string',
            'image'          => 'nullable|image|max:5120',
        ]);

        try {
            $http = $this->api();

            if ($request->hasFile('image')) {
                $response = $http->attach(
                    'image',
                    file_get_contents($request->file('image')->getRealPath()),
                    $request->file('image')->getClientOriginalName()
                )->put($this->apiUrl . '/products/' . $id, [
                    'product_name'   => $request->product_name,
                    'brand'          => $request->brand,
                    'price'          => (int) $request->price,
                    'stock'          => (int) $request->stock,
                    'description'    => $request->description ?? '',
                    'specifications' => $request->specifications ?? '',
                ]);
            } else {
                $response = $http->put($this->apiUrl . '/products/' . $id, [
                    'product_name'   => $request->product_name,
                    'brand'          => $request->brand,
                    'price'          => (int) $request->price,
                    'stock'          => (int) $request->stock,
                    'description'    => $request->description ?? '',
                    'specifications' => $request->specifications ?? '',
                ]);
            }

            $data = $response->json();

            if ($response->successful() && ($data['success'] ?? false)) {
                return redirect()->route('admin.products')->with('success', 'Produk berhasil diupdate!');
            }

            return back()->with('error', $data['message'] ?? 'Gagal mengupdate produk')->withInput();

        } catch (\Exception $e) {
            Log::error('Admin updateProduct: ' . $e->getMessage());
            return back()->with('error', 'Koneksi ke server gagal.')->withInput();
        }
    }

    public function destroyProduct($id)
    {
        try {
            $response = $this->api()->delete($this->apiUrl . '/products/' . $id);
            $data     = $response->json();

            if ($response->successful() && ($data['success'] ?? false)) {
                return redirect()->route('admin.products')->with('success', 'Produk berhasil dihapus!');
            }

            return redirect()->route('admin.products')->with('error', $data['message'] ?? 'Gagal menghapus produk');

        } catch (\Exception $e) {
            Log::error('Admin destroyProduct: ' . $e->getMessage());
            return redirect()->route('admin.products')->with('error', 'Koneksi ke server gagal.');
        }
    }

    // ═══════════════════════════════════════════════════════════════════
    // USER MANAGEMENT
    // ═══════════════════════════════════════════════════════════════════

    public function users()
    {
        try {
            $response = $this->api()->get($this->apiUrl . '/users');
            $users    = $response->successful() ? ($response->json()['data'] ?? []) : [];
        } catch (\Exception $e) {
            Log::error('Admin users: ' . $e->getMessage());
            $users = [];
        }

        return view('admin.users', compact('users'));
    }

    public function destroyUser($id)
    {
        try {
            $response = $this->api()->delete($this->apiUrl . '/admin/users/' . $id);
            $data     = $response->json();

            if ($response->successful() && ($data['success'] ?? false)) {
                return redirect()->route('admin.users')->with('success', 'Pengguna berhasil dihapus!');
            }

            return redirect()->route('admin.users')->with('error', $data['message'] ?? 'Gagal menghapus pengguna');

        } catch (\Exception $e) {
            Log::error('Admin destroyUser: ' . $e->getMessage());
            return redirect()->route('admin.users')->with('error', 'Koneksi ke server gagal.');
        }
    }

    // ═══════════════════════════════════════════════════════════════════
    // TRANSACTION MANAGEMENT
    // ═══════════════════════════════════════════════════════════════════

    public function transactions()
    {
        try {
            $response     = $this->api()->get($this->apiUrl . '/admin/transactions');
            $transactions = $response->successful() ? ($response->json()['data'] ?? []) : [];
        } catch (\Exception $e) {
            Log::error('Admin transactions: ' . $e->getMessage());
            $transactions = [];
        }

        $totalTransactions = count($transactions);
        $totalRevenue      = array_sum(array_column($transactions, 'total_amount'));
        $pendingCount      = count(array_filter($transactions, fn($t) => ($t['status'] ?? '') === 'pending'));
        $paidCount         = count(array_filter($transactions, fn($t) => ($t['status'] ?? '') === 'paid'));
        $shippedCount      = count(array_filter($transactions, fn($t) => ($t['status'] ?? '') === 'shipped'));
        $completedCount    = count(array_filter($transactions, fn($t) => ($t['status'] ?? '') === 'completed'));
        $cancelledCount    = count(array_filter($transactions, fn($t) => ($t['status'] ?? '') === 'cancelled'));

        return view('admin.transactions', compact(
            'transactions', 'totalTransactions', 'totalRevenue',
            'pendingCount', 'paidCount', 'shippedCount', 'completedCount', 'cancelledCount'
        ));
    }

    public function showTransaction($id)
    {
        try {
            $response     = $this->api()->get($this->apiUrl . '/admin/transactions');
            $transactions = $response->successful() ? ($response->json()['data'] ?? []) : [];

            $transaction = null;
            foreach ($transactions as $t) {
                if ((string) ($t['id'] ?? '') === (string) $id) {
                    $transaction = $t;
                    break;
                }
            }
        } catch (\Exception $e) {
            Log::error('Admin showTransaction: ' . $e->getMessage());
            $transaction = null;
        }

        if (!$transaction) {
            return redirect()->route('admin.transactions')->with('error', 'Transaksi tidak ditemukan');
        }

        return view('admin.transactions-show', compact('transaction'));
    }

    public function updateTransactionStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,paid,shipped,completed,cancelled'
        ]);

        try {
            $response = $this->api()->put($this->apiUrl . '/admin/transactions/' . $id . '/status', [
                'status' => $request->status
            ]);

            $data = $response->json();

            if ($response->successful() && ($data['success'] ?? false)) {
                if ($request->expectsJson()) {
                    return response()->json(['success' => true, 'message' => 'Status diperbarui']);
                }
                return redirect()->route('admin.transactions')->with('success', 'Status transaksi diperbarui');
            }

            $msg = $data['message'] ?? 'Gagal mengupdate status';
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => $msg], 400);
            }
            return redirect()->route('admin.transactions')->with('error', $msg);

        } catch (\Exception $e) {
            Log::error('Admin updateTransactionStatus: ' . $e->getMessage());
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Koneksi gagal'], 500);
            }
            return redirect()->route('admin.transactions')->with('error', 'Koneksi ke server gagal.');
        }
    }

    // ═══════════════════════════════════════════════════════════════════
    // EXPORT PDF & EXCEL
    // ═══════════════════════════════════════════════════════════════════

    public function exportPdf()
    {
        $data = $this->getExportData();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.export-pdf', $data)
            ->setPaper('a4', 'portrait')
            ->setOptions([
                'defaultFont'     => 'sans-serif',
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => false,
            ]);

        return $pdf->download('laporan-hp-market-' . date('Y-m-d') . '.pdf');
    }

    public function exportExcel()
    {
        $data         = $this->getExportData();
        $users        = $data['users'];
        $transactions = $data['transactions'];
        $stats        = $data['stats'];

        // Render HTML table — Excel bisa baca format ini langsung sebagai .xls
        $html  = view('admin.export-excel', compact('users', 'transactions', 'stats'))->render();

        $filename = 'laporan-hp-market-' . date('Y-m-d') . '.xls';

        return response($html, 200, [
            'Content-Type'        => 'application/vnd.ms-excel',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Expires'             => '0',
        ]);
    }

    // ─── Helper: ambil semua data untuk export ──────────────────────────
    private function getExportData(): array
    {
        try {
            $res   = $this->api()->get($this->apiUrl . '/users');
            $users = $res->successful() ? ($res->json()['data'] ?? []) : [];
        } catch (\Exception $e) {
            $users = [];
        }

        try {
            $res          = $this->api()->get($this->apiUrl . '/admin/transactions');
            $transactions = $res->successful() ? ($res->json()['data'] ?? []) : [];
        } catch (\Exception $e) {
            $transactions = [];
        }

        try {
            $res   = $this->api()->get($this->apiUrl . '/admin/dashboard');
            $stats = $res->successful() ? ($res->json()['data'] ?? []) : [];
        } catch (\Exception $e) {
            $stats = [];
        }

        return compact('users', 'transactions', 'stats');
    }
}
