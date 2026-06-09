<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    private $apiUrl = 'http://localhost:5000/api';

    public function index(Request $request)
    {
        $search = $request->get('search');

        try {
            $url = $this->apiUrl . '/products';
            if ($search) {
                $url .= '?search=' . urlencode($search);
            }

            $response = Http::timeout(10)->get($url);

            if ($response->successful()) {
                $data     = $response->json();
                $products = $data['data'] ?? [];
            } else {
                Log::warning('Products API returned ' . $response->status());
                $products = [];
            }
        } catch (\Exception $e) {
            Log::error('ProductController@index: ' . $e->getMessage());
            $products = [];
        }

        return view('products.index', compact('products'));
    }

    public function show($id)
    {
        try {
            $response = Http::timeout(10)->get($this->apiUrl . '/products/' . $id);

            if ($response->successful()) {
                $data    = $response->json();
                $product = $data['data'] ?? null;
            } else {
                $product = null;
            }
        } catch (\Exception $e) {
            Log::error('ProductController@show: ' . $e->getMessage());
            $product = null;
        }

        if (!$product) {
            abort(404, 'Produk tidak ditemukan');
        }

        return view('products.show', compact('product'));
    }
}
