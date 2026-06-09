@extends('layouts.app')

@section('title', 'Edit Produk - Admin HP Market')

@section('content')
<div class="container mx-auto px-4 md:px-6 py-10">

    {{-- Breadcrumb --}}
    <div class="flex items-center gap-2 text-sm text-gray-500 mb-8 fade-in-up">
        <a href="{{ route('admin.dashboard') }}" class="hover:text-purple-600 transition">Dashboard</a>
        <i class="fas fa-chevron-right text-xs text-gray-300"></i>
        <a href="{{ route('admin.products') }}" class="hover:text-purple-600 transition">Produk</a>
        <i class="fas fa-chevron-right text-xs text-gray-300"></i>
        <span class="text-gray-900 font-semibold">Edit Produk</span>
    </div>

    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden fade-in-up">

            {{-- Header --}}
            <div class="gradient-primary px-6 py-5 flex items-center gap-3">
                <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                    <i class="fas fa-pen-to-square text-white"></i>
                </div>
                <div>
                    <h1 class="text-lg font-extrabold text-white">Edit Produk</h1>
                    <p class="text-white/70 text-xs">ID #{{ $product['id'] ?? '-' }}</p>
                </div>
            </div>

            <form action="{{ route('admin.products.update', $product['id']) }}" method="POST" class="p-6 space-y-5">
                @csrf
                @method('PUT')

                {{-- Flash Messages --}}
                @if(session('error'))
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl flex items-center gap-2 text-sm">
                    <i class="fas fa-circle-xmark text-red-500"></i> {{ session('error') }}
                </div>
                @endif
                @if(session('success'))
                <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl flex items-center gap-2 text-sm">
                    <i class="fas fa-circle-check text-green-500"></i> {{ session('success') }}
                </div>
                @endif

                {{-- Product Name --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                        Nama Produk <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="product_name"
                           value="{{ old('product_name', $product['product_name'] ?? $product['name'] ?? '') }}"
                           required
                           class="input-field w-full px-4 py-3 rounded-xl bg-gray-50 text-sm"
                           placeholder="Contoh: iPhone 15 Pro Max 256GB">
                    @error('product_name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- Brand --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                        Brand <span class="text-red-500">*</span>
                    </label>
                    <select name="brand" required class="input-field w-full px-4 py-3 rounded-xl bg-gray-50 text-sm">
                        <option value="">â€” Pilih Brand â€”</option>
                        @foreach(['Apple','Samsung','Xiaomi','Google','OPPO','Vivo','OnePlus','Nothing','Asus','Realme','Infinix','Tecno'] as $b)
                        <option value="{{ $b }}" {{ old('brand', $product['brand'] ?? '') == $b ? 'selected' : '' }}>
                            {{ $b }}
                        </option>
                        @endforeach
                    </select>
                    @error('brand')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- Price & Stock --}}
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                            Harga (Rp) <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-xs font-semibold text-gray-400">Rp</span>
                            <input type="number" name="price"
                                   value="{{ old('price', $product['price'] ?? 0) }}"
                                   required min="0"
                                   class="input-field w-full pl-9 pr-3 py-3 rounded-xl bg-gray-50 text-sm">
                        </div>
                        @error('price')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                            Stok <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="stock"
                               value="{{ old('stock', $product['stock'] ?? 0) }}"
                               required min="0"
                               class="input-field w-full px-4 py-3 rounded-xl bg-gray-50 text-sm">
                        @error('stock')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>

                {{-- Description --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">Deskripsi</label>
                    <textarea name="description" rows="3"
                              class="input-field w-full px-4 py-3 rounded-xl bg-gray-50 text-sm resize-none"
                              placeholder="Deskripsikan produk HP ini...">{{ old('description', $product['description'] ?? '') }}</textarea>
                    @error('description')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- Specifications --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">Spesifikasi</label>
                    <textarea name="specifications" rows="3"
                              class="input-field w-full px-4 py-3 rounded-xl bg-gray-50 text-sm resize-none"
                              placeholder="Contoh: RAM 8GB | Storage 256GB | Layar 6.7 inch | Baterai 5000mAh">{{ old('specifications', $product['specifications'] ?? '') }}</textarea>
                    @error('specifications')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- System Info --}}
                <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                    <p class="text-xs font-bold text-gray-500 mb-2 uppercase tracking-wider">Info Sistem</p>
                    <div class="grid grid-cols-2 gap-2 text-xs text-gray-600">
                        <span>ID: <strong>{{ $product['id'] ?? '-' }}</strong></span>
                        <span>Dibuat: <strong>{{ isset($product['created_at']) ? date('d/m/Y', strtotime($product['created_at'])) : '-' }}</strong></span>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex justify-between items-center pt-2 border-t border-gray-100">
                    <a href="{{ route('admin.products') }}"
                        class="text-sm text-gray-500 hover:text-gray-700 flex items-center gap-1.5 transition">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                    <button type="submit"
                        class="btn-primary text-white px-6 py-2.5 rounded-xl font-bold text-sm flex items-center gap-2">
                        <i class="fas fa-floppy-disk"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('styles')
<style>
    .input-field { border: 2px solid #E5E7EB; transition: all 0.3s ease; }
    .input-field:focus { border-color: var(--primary); box-shadow: 0 0 0 4px rgba(31,78,121,0.12); outline: none; }
    .btn-primary { background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%); transition: all 0.3s ease; }
    .btn-primary:hover { opacity: 0.9; transform: translateY(-1px); }
</style>
@endpush

@endsection

