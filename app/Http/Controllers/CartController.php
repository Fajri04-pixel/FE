<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CartController extends Controller
{
    private $apiUrl = 'http://localhost:5000/api';

    public function index()
    {
        if (!session('user')) {
            return redirect()->route('login');
        }

        if (session('user')['role'] === 'admin') {
            return redirect()->route('home')->with('error', 'Admin tidak dapat mengakses keranjang belanja');
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . session('token')
            ])->timeout(10)->get($this->apiUrl . '/cart');

            if ($response->successful()) {
                $data = $response->json();
                $cart = $data['data'] ?? [];
            } else {
                Log::warning('Cart API returned ' . $response->status() . ': ' . $response->body());
                $cart = [];
            }
        } catch (\Exception $e) {
            Log::error('CartController@index: ' . $e->getMessage());
            $cart = [];
        }

        return view('users.cart', compact('cart'));
    }

    public function add(Request $request)
    {
        if (!session('user')) {
            return redirect()->route('login');
        }

        if (session('user')['role'] === 'admin') {
            return back()->with('error', 'Admin tidak dapat berbelanja');
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . session('token')
            ])->timeout(10)->post($this->apiUrl . '/cart', [
                'product_id' => $request->product_id,
                'quantity'   => $request->quantity ?? 1,
            ]);

            $data = $response->json();

            if ($response->successful() && ($data['success'] ?? false)) {
                return redirect()->route('cart.index')->with('success', $data['message'] ?? 'Produk ditambahkan ke keranjang');
            }

            return back()->with('error', $data['message'] ?? 'Gagal menambah ke keranjang');

        } catch (\Exception $e) {
            Log::error('CartController@add: ' . $e->getMessage());
            return back()->with('error', 'Koneksi ke server gagal. Pastikan backend berjalan.');
        }
    }

    public function update(Request $request, $id)
    {
        if (!session('user')) {
            return redirect()->route('login');
        }

        try {
            Http::withHeaders([
                'Authorization' => 'Bearer ' . session('token')
            ])->timeout(10)->put($this->apiUrl . '/cart/' . $id, [
                'quantity' => (int) $request->quantity,
            ]);

            // Kalau AJAX request, kembalikan JSON
            if ($request->expectsJson()) {
                return response()->json(['success' => true]);
            }
            return redirect()->route('cart.index');

        } catch (\Exception $e) {
            \Log::error('CartController@update: ' . $e->getMessage());
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Gagal update keranjang'], 500);
            }
            return back()->with('error', 'Gagal mengupdate keranjang');
        }
    }

    public function remove($id)
    {
        if (!session('user')) {
            return redirect()->route('login');
        }

        try {
            Http::withHeaders([
                'Authorization' => 'Bearer ' . session('token')
            ])->timeout(10)->delete($this->apiUrl . '/cart/' . $id);

            // Kalau AJAX request, kembalikan JSON
            if (request()->expectsJson()) {
                return response()->json(['success' => true]);
            }
            return redirect()->route('cart.index')->with('success', 'Item dihapus dari keranjang');

        } catch (\Exception $e) {
            \Log::error('CartController@remove: ' . $e->getMessage());
            if (request()->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Gagal menghapus item'], 500);
            }
            return back()->with('error', 'Gagal menghapus item');
        }
    }
}
