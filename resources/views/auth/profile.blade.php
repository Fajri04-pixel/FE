@extends('layouts.app')

@section('title', 'Profil Saya - HP Market')

@section('content')
<div class="container mx-auto px-4 md:px-6 py-10">
    <div class="max-w-3xl mx-auto">

        {{-- Header Breadcrumb --}}
        <div class="flex items-center gap-2 text-sm text-gray-500 mb-8 fade-in-up">
            <a href="{{ route('home') }}" class="hover:text-purple-600 transition">Beranda</a>
            <i class="fas fa-chevron-right text-xs text-gray-300"></i>
            <span class="text-gray-900 font-semibold">Profil Saya</span>
        </div>

        {{-- Profile Header Card --}}
        <div class="gradient-primary rounded-3xl p-6 mb-6 relative overflow-hidden fade-in-up">
            <div class="absolute -top-8 -right-8 w-32 h-32 bg-white/10 rounded-full"></div>
            <div class="absolute -bottom-6 -left-6 w-20 h-20 bg-white/10 rounded-full"></div>
            <div class="relative flex items-center gap-5">
                <div class="w-16 h-16 bg-white/20 backdrop-blur rounded-2xl flex items-center justify-center text-white text-3xl font-extrabold shadow-lg">
                    {{ strtoupper(substr(session('user')['name'] ?? session('user')['username'] ?? 'U', 0, 1)) }}
                </div>
                <div>
                    <h2 class="text-white text-xl font-extrabold">
                        {{ session('user')['name'] ?? session('user')['username'] }}
                    </h2>
                    <p class="text-white/75 text-sm">{{ session('user')['email'] }}</p>
                    @if(session('user')['role'] == 'admin')
                        <span class="mt-1 inline-block bg-white/20 text-white text-xs font-bold px-3 py-0.5 rounded-full">
                            <i class="fas fa-user-shield mr-1"></i> Administrator
                        </span>
                    @else
                        <span class="mt-1 inline-block bg-white/20 text-white text-xs font-bold px-3 py-0.5 rounded-full">
                            <i class="fas fa-user-check mr-1"></i> Member
                        </span>
                    @endif
                </div>
            </div>
        </div>

        {{-- Flash Messages --}}
        @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-5 flex items-center gap-2 text-sm fade-in-up">
            <i class="fas fa-circle-check text-green-500"></i> {{ session('success') }}
        </div>
        @endif
        @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-5 flex items-center gap-2 text-sm fade-in-up">
            <i class="fas fa-circle-xmark text-red-500"></i> {{ session('error') }}
        </div>
        @endif

        <div class="grid md:grid-cols-3 gap-6">

            {{-- Info Column --}}
            <div class="space-y-4">
                {{-- Account Info --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 fade-in-up">
                    <h3 class="font-bold text-gray-700 text-sm mb-4 flex items-center gap-2">
                        <i class="fas fa-circle-info text-purple-400"></i> Info Akun
                    </h3>
                    <div class="space-y-3">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-purple-50 rounded-lg flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-user text-purple-500 text-xs"></i>
                            </div>
                            <div class="min-w-0">
                                <p class="text-xs text-gray-400">Nama</p>
                                <p class="text-sm font-semibold text-gray-800 truncate">{{ session('user')['name'] ?? session('user')['username'] }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-blue-50 rounded-lg flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-envelope text-blue-500 text-xs"></i>
                            </div>
                            <div class="min-w-0">
                                <p class="text-xs text-gray-400">Email</p>
                                <p class="text-sm font-semibold text-gray-800 truncate">{{ session('user')['email'] }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-amber-50 rounded-lg flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-calendar text-amber-500 text-xs"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400">Bergabung</p>
                                <p class="text-sm font-semibold text-gray-800">{{ date('d M Y') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Quick Links --}}
                @if(session('user')['role'] == 'user')
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 fade-in-up">
                    <h3 class="font-bold text-gray-700 text-sm mb-3 flex items-center gap-2">
                        <i class="fas fa-link text-purple-400"></i> Pintasan
                    </h3>
                    <div class="space-y-2">
                        <a href="{{ route('cart.index') }}" class="flex items-center gap-2 text-sm text-gray-600 hover:text-purple-600 hover:bg-purple-50 px-2 py-2 rounded-lg transition">
                            <i class="fas fa-bag-shopping text-purple-400 w-4"></i> Keranjang
                        </a>
                        <a href="{{ route('transactions.index') }}" class="flex items-center gap-2 text-sm text-gray-600 hover:text-purple-600 hover:bg-purple-50 px-2 py-2 rounded-lg transition">
                            <i class="fas fa-box text-purple-400 w-4"></i> Pesanan
                        </a>
                    </div>
                </div>
                @endif
            </div>

            {{-- Edit Form --}}
            <div class="md:col-span-2">
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm fade-in-up">
                    <div class="px-6 py-4 border-b border-gray-100">
                        <h3 class="font-bold text-gray-800 flex items-center gap-2">
                            <i class="fas fa-pen-to-square text-purple-400"></i> Edit Informasi
                        </h3>
                        <p class="text-xs text-gray-500 mt-0.5">Nama dan email tidak dapat diubah</p>
                    </div>
                    <form action="{{ route('profile.update') }}" method="POST" class="p-6 space-y-5">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-2 gap-4">
                            {{-- Nama (readonly) --}}
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1.5">Nama Lengkap</label>
                                <input type="text"
                                       value="{{ session('user')['name'] ?? session('user')['username'] ?? '' }}"
                                       class="w-full px-3 py-2.5 bg-gray-100 border-2 border-gray-200 rounded-xl text-sm text-gray-500 cursor-not-allowed"
                                       readonly disabled>
                            </div>
                            {{-- Email (readonly) --}}
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1.5">Email</label>
                                <input type="email"
                                       value="{{ session('user')['email'] ?? '' }}"
                                       class="w-full px-3 py-2.5 bg-gray-100 border-2 border-gray-200 rounded-xl text-sm text-gray-500 cursor-not-allowed"
                                       readonly disabled>
                            </div>
                        </div>

                        {{-- Phone --}}
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                                <i class="fas fa-phone text-purple-400 mr-1"></i> Nomor Telepon
                            </label>
                            <input type="text" name="phone" value="{{ session('user')['phone'] ?? '' }}"
                                   class="input-field w-full px-4 py-2.5 rounded-xl bg-gray-50 text-sm"
                                   placeholder="08123456789">
                        </div>

                        {{-- Address --}}
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                                <i class="fas fa-map-location-dot text-purple-400 mr-1"></i> Alamat Pengiriman
                            </label>
                            <textarea name="address" rows="3"
                                      class="input-field w-full px-4 py-2.5 rounded-xl bg-gray-50 text-sm resize-none"
                                      placeholder="Masukkan alamat lengkap...">{{ session('user')['address'] ?? '' }}</textarea>
                        </div>

                        <div class="flex justify-between items-center pt-2">
                            <a href="{{ route('home') }}" class="text-sm text-gray-500 hover:text-gray-700 flex items-center gap-1.5 transition">
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

