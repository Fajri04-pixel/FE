<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TransactionController extends Controller
{
    private $apiUrl = 'http://localhost:5000/api';

    public function index()
    {
        if (!session('user')) {
            return redirect()->route('login');
        }

        if (session('user')['role'] === 'admin') {
            return redirect()->route('admin.transactions');
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . session('token')
            ])->timeout(10)->get($this->apiUrl . '/transactions');

            if ($response->successful()) {
                $data         = $response->json();
                $transactions = $data['data'] ?? [];
            } else {
                Log::warning('Transactions API returned ' . $response->status());
                $transactions = [];
            }
        } catch (\Exception $e) {
            Log::error('TransactionController@index: ' . $e->getMessage());
            $transactions = [];
        }

        return view('users.transactions', compact('transactions'));
    }

    public function checkout(Request $request)
    {
        if (!session('user')) {
            return response()->json(['success' => false, 'message' => 'Silakan login terlebih dahulu'], 401);
        }

        if (session('user')['role'] === 'admin') {
            return response()->json(['success' => false, 'message' => 'Admin tidak dapat melakukan checkout'], 403);
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . session('token')
            ])->timeout(15)->post($this->apiUrl . '/transactions/checkout');

            $data = $response->json();

            if ($response->successful() && ($data['success'] ?? false)) {
                return response()->json(['success' => true, 'message' => $data['message'] ?? 'Checkout berhasil!', 'data' => $data['data'] ?? []]);
            }

            return response()->json(['success' => false, 'message' => $data['message'] ?? 'Checkout gagal'], $response->status());

        } catch (\Exception $e) {
            Log::error('TransactionController@checkout: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Koneksi ke server gagal'], 500);
        }
    }

    public function uploadProof(Request $request, $id)
    {
        if (!session('user')) {
            return response()->json(['success' => false, 'message' => 'Silakan login'], 401);
        }

        $request->validate([
            'bukti' => 'required|image|max:5120',
        ]);

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . session('token')
            ])->timeout(30)->attach(
                'bukti',
                file_get_contents($request->file('bukti')->getRealPath()),
                $request->file('bukti')->getClientOriginalName()
            )->post($this->apiUrl . '/transactions/' . $id . '/payment-proof');

            $data = $response->json();

            if ($response->successful() && ($data['success'] ?? false)) {
                return response()->json(['success' => true, 'message' => $data['message'] ?? 'Bukti berhasil dikirim!']);
            }

            return response()->json(['success' => false, 'message' => $data['message'] ?? 'Gagal upload bukti'], $response->status());

        } catch (\Exception $e) {
            Log::error('TransactionController@uploadProof: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Koneksi ke server gagal'], 500);
        }
    }
}
