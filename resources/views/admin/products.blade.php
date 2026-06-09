@extends('layouts.app')

@section('title', 'Kelola Produk - Admin HP Market')

@section('content')
<div class="container mx-auto px-4 md:px-6 py-10">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8 fade-in-up">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 gradient-primary rounded-xl flex items-center justify-center shadow">
                <i class="fas fa-box-archive text-white"></i>
            </div>
            <div>
                <h1 class="text-2xl font-extrabold text-gray-900">Kelola Produk</h1>
                <p class="text-sm text-gray-500">{{ count($products) }} produk terdaftar</p>
            </div>
        </div>
        <button onclick="openModal()"
            class="btn-primary text-white px-5 py-2.5 rounded-xl font-bold flex items-center gap-2 text-sm self-start sm:self-auto">
            <i class="fas fa-plus"></i> Tambah Produk
        </button>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden fade-in-up">
        @if(count($products) > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="px-5 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Produk</th>
                        <th class="px-5 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Brand</th>
                        <th class="px-5 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Harga</th>
                        <th class="px-5 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Stok</th>
                        <th class="px-5 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($products as $product)
                    <tr class="table-row-hover transition">
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-3">
                                {{-- Thumbnail --}}
                                @if(!empty($product['image_url']))
                                    <img src="{{ $product['image_url'] }}"
                                         alt="{{ $product['product_name'] ?? '' }}"
                                         class="w-10 h-10 rounded-xl object-cover flex-shrink-0 border border-gray-100"
                                         onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
                                    <div class="w-10 h-10 product-img-bg rounded-xl items-center justify-center text-xl flex-shrink-0 hidden">📱</div>
                                @else
                                    <div class="w-10 h-10 product-img-bg rounded-xl flex items-center justify-center text-xl flex-shrink-0">📱</div>
                                @endif
                                <div class="min-w-0">
                                    <p class="font-semibold text-gray-800 text-sm truncate max-w-[180px]">{{ $product['product_name'] ?? $product['name'] ?? '-' }}</p>
                                    <p class="text-xs text-gray-400">#{{ $product['id'] ?? '-' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-4">
                            <span class="badge badge-purple">{{ $product['brand'] ?? '-' }}</span>
                        </td>
                        <td class="px-5 py-4">
                            <span class="font-bold text-purple-700 text-sm">Rp {{ number_format($product['price'] ?? 0, 0, ',', '.') }}</span>
                        </td>
                        <td class="px-5 py-4">
                            @if(($product['stock'] ?? 0) > 5)
                                <span class="badge badge-success">{{ $product['stock'] }}</span>
                            @elseif(($product['stock'] ?? 0) > 0)
                                <span class="badge badge-warning">Sisa {{ $product['stock'] }}</span>
                            @else
                                <span class="badge badge-danger">Habis</span>
                            @endif
                        </td>
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-2">
                                <button onclick='openModal(@json($product))'
                                    class="w-8 h-8 bg-blue-50 text-blue-600 hover:bg-blue-100 rounded-lg flex items-center justify-center transition"
                                    title="Edit">
                                    <i class="fas fa-pen-to-square text-xs"></i>
                                </button>
                                <button onclick="deleteProduct({{ $product['id'] ?? 0 }})"
                                    class="w-8 h-8 bg-red-50 text-red-500 hover:bg-red-100 rounded-lg flex items-center justify-center transition"
                                    title="Hapus">
                                    <i class="fas fa-trash text-xs"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="flex flex-col items-center justify-center py-20 text-center">
            <div class="w-20 h-20 bg-purple-50 rounded-3xl flex items-center justify-center mb-4">
                <i class="fas fa-box-open text-3xl text-purple-300"></i>
            </div>
            <h3 class="text-lg font-bold text-gray-600 mb-1">Belum Ada Produk</h3>
            <p class="text-sm text-gray-400 mb-5">Klik tombol "Tambah Produk" untuk memulai</p>
            <button onclick="openModal()" class="btn-primary text-white px-5 py-2.5 rounded-xl font-bold text-sm flex items-center gap-2">
                <i class="fas fa-plus"></i> Tambah Produk
            </button>
        </div>
        @endif
    </div>
</div>

{{-- ═══════════════════════════════════════════════════════
     MODAL TAMBAH / EDIT PRODUK
═══════════════════════════════════════════════════════ --}}
<div id="productModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm hidden items-center justify-center z-50 px-4 py-6">
    <div class="bg-white rounded-3xl w-full max-w-xl shadow-2xl overflow-hidden flex flex-col max-h-[92vh]">

        {{-- Header --}}
        <div class="gradient-primary px-6 py-5 flex items-center justify-between flex-shrink-0">
            <h3 class="text-lg font-extrabold text-white flex items-center gap-2">
                <i class="fas fa-box-archive"></i>
                <span id="modalTitle">Tambah Produk</span>
            </h3>
            <button onclick="closeModal()" class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center text-white hover:bg-white/30 transition">
                <i class="fas fa-xmark"></i>
            </button>
        </div>

        {{-- Form (scrollable) --}}
        <form id="productForm" class="overflow-y-auto flex-1 p-6 space-y-4" onsubmit="saveProduct(event)">
            <input type="hidden" id="productId">
            <input type="hidden" id="existingImageUrl">

            {{-- ── UPLOAD FOTO ── --}}
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                    <i class="fas fa-image text-purple-400 mr-1"></i> Foto Produk
                    <span class="text-gray-400 font-normal">(jpg, png, webp — maks 5 MB)</span>
                </label>

                {{-- Drop Zone --}}
                <div id="dropZone"
                    class="relative border-2 border-dashed border-gray-300 rounded-xl bg-gray-50 hover:border-purple-400 hover:bg-purple-50 transition cursor-pointer overflow-hidden"
                    onclick="document.getElementById('imageFile').click()"
                    ondragover="handleDragOver(event)"
                    ondragleave="handleDragLeave(event)"
                    ondrop="handleDrop(event)">

                    {{-- Preview (hidden by default) --}}
                    <div id="imagePreviewWrap" class="hidden relative">
                        <img id="imagePreview" src="" alt="Preview"
                             class="w-full h-48 object-contain bg-gray-100">
                        <button type="button" onclick="removeImage(event)"
                            class="absolute top-2 right-2 w-7 h-7 bg-red-500 text-white rounded-lg flex items-center justify-center hover:bg-red-600 transition shadow">
                            <i class="fas fa-xmark text-xs"></i>
                        </button>
                        <div class="absolute bottom-2 left-2 bg-black/50 text-white text-xs px-2 py-1 rounded-lg">
                            <i class="fas fa-check-circle mr-1 text-green-400"></i>
                            <span id="imageFileName">foto.jpg</span>
                        </div>
                    </div>

                    {{-- Placeholder --}}
                    <div id="dropPlaceholder" class="flex flex-col items-center justify-center py-8 px-4 text-center">
                        <div class="w-12 h-12 bg-purple-100 rounded-2xl flex items-center justify-center mb-3">
                            <i class="fas fa-cloud-arrow-up text-purple-500 text-xl"></i>
                        </div>
                        <p class="text-sm font-semibold text-gray-700">Klik atau seret foto ke sini</p>
                        <p class="text-xs text-gray-400 mt-1">JPG, PNG, WEBP, GIF — maks 5 MB</p>
                    </div>
                </div>

                {{-- File input (hidden) --}}
                <input type="file" id="imageFile" accept="image/jpeg,image/png,image/webp,image/gif"
                    class="hidden" onchange="handleFileSelect(this)">
            </div>

            {{-- ── NAMA PRODUK ── --}}
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1.5">Nama Produk <span class="text-red-500">*</span></label>
                <input type="text" id="product_name" required placeholder="Contoh: iPhone 15 Pro Max 256GB"
                    class="input-field w-full px-3 py-2.5 rounded-xl bg-gray-50 text-sm">
            </div>

            {{-- ── BRAND + STOK ── --}}
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">Brand <span class="text-red-500">*</span></label>
                    <select id="brand" required class="input-field w-full px-3 py-2.5 rounded-xl bg-gray-50 text-sm">
                        <option value="">Pilih Brand</option>
                        @foreach(['Apple','Samsung','Xiaomi','Google','OPPO','Vivo','OnePlus','Nothing','Asus','Realme','Infinix','Tecno'] as $b)
                        <option value="{{ $b }}">{{ $b }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">Stok <span class="text-red-500">*</span></label>
                    <input type="number" id="stock" required min="0" placeholder="0"
                        class="input-field w-full px-3 py-2.5 rounded-xl bg-gray-50 text-sm">
                </div>
            </div>

            {{-- ── HARGA ── --}}
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1.5">Harga (Rp) <span class="text-red-500">*</span></label>
                <div class="relative">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-xs font-semibold text-gray-400">Rp</span>
                    <input type="number" id="price" required min="0" placeholder="0"
                        class="input-field w-full pl-9 pr-3 py-2.5 rounded-xl bg-gray-50 text-sm">
                </div>
            </div>

            {{-- ── DESKRIPSI ── --}}
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1.5">Deskripsi</label>
                <textarea id="description" rows="2" placeholder="Deskripsi singkat produk..."
                    class="input-field w-full px-3 py-2.5 rounded-xl bg-gray-50 text-sm resize-none"></textarea>
            </div>

            {{-- ── SPESIFIKASI ── --}}
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1.5">Spesifikasi</label>
                <textarea id="specifications" rows="2" placeholder="RAM 8GB | Storage 256GB | Layar 6.1&quot; | ..."
                    class="input-field w-full px-3 py-2.5 rounded-xl bg-gray-50 text-sm resize-none"></textarea>
            </div>

            {{-- ── ACTIONS ── --}}
            <div class="flex justify-end gap-3 pt-2 border-t border-gray-100">
                <button type="button" onclick="closeModal()"
                    class="px-4 py-2 border-2 border-gray-200 text-gray-600 rounded-xl text-sm font-semibold hover:bg-gray-50 transition">
                    Batal
                </button>
                <button type="submit" id="submitBtn"
                    class="btn-primary text-white px-5 py-2 rounded-xl text-sm font-bold flex items-center gap-1.5">
                    <i class="fas fa-floppy-disk"></i> Simpan
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    const API_URL   = 'http://localhost:5000/api';
    const ADM_URL   = '{{ url('admin/products') }}';
    const CSRF      = '{{ csrf_token() }}';
    // Ambil dari variabel global `token` yang sudah di-set di layouts/app.blade.php
    // Fallback ke session langsung jika belum ter-set
    const JWT_TOKEN = (typeof token !== 'undefined' && token) ? token : '{{ session('token') }}';

    // ─── MODAL open/close ─────────────────────────────────────────────────────
    function openModal(product = null) {
        const isEdit = !!product;
        document.getElementById('modalTitle').textContent = isEdit ? 'Edit Produk' : 'Tambah Produk';
        document.getElementById('productForm').reset();
        document.getElementById('productId').value         = isEdit ? product.id : '';
        document.getElementById('existingImageUrl').value  = isEdit ? (product.image_url || '') : '';
        resetImagePreview();

        if (isEdit) {
            document.getElementById('product_name').value   = product.product_name || product.name || '';
            document.getElementById('brand').value          = product.brand || '';
            document.getElementById('price').value          = product.price || 0;
            document.getElementById('stock').value          = product.stock || 0;
            document.getElementById('description').value    = product.description || '';
            document.getElementById('specifications').value = product.specifications || '';

            // Tampilkan foto yang sudah ada
            if (product.image_url) {
                showPreviewFromUrl(product.image_url);
            }
        }

        const modal = document.getElementById('productModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeModal() {
        document.getElementById('productModal').classList.add('hidden');
        document.getElementById('productModal').classList.remove('flex');
        resetImagePreview();
    }

    // ─── IMAGE PREVIEW ────────────────────────────────────────────────────────
    function handleFileSelect(input) {
        const file = input.files[0];
        if (!file) return;
        if (file.size > 5 * 1024 * 1024) {
            Swal.fire({ title: 'File Terlalu Besar', text: 'Maksimal ukuran foto 5 MB.', icon: 'warning', confirmButtonColor: '#7C3AED' });
            input.value = '';
            return;
        }
        showPreviewFromFile(file);
    }

    function showPreviewFromFile(file) {
        const reader = new FileReader();
        reader.onload = e => {
            document.getElementById('imagePreview').src    = e.target.result;
            document.getElementById('imageFileName').textContent = file.name;
            document.getElementById('imagePreviewWrap').classList.remove('hidden');
            document.getElementById('dropPlaceholder').classList.add('hidden');
        };
        reader.readAsDataURL(file);
    }

    function showPreviewFromUrl(url) {
        document.getElementById('imagePreview').src         = url;
        document.getElementById('imageFileName').textContent = 'Foto saat ini';
        document.getElementById('imagePreviewWrap').classList.remove('hidden');
        document.getElementById('dropPlaceholder').classList.add('hidden');
    }

    function removeImage(e) {
        e.stopPropagation();
        document.getElementById('imageFile').value = '';
        document.getElementById('existingImageUrl').value = '';
        resetImagePreview();
    }

    function resetImagePreview() {
        document.getElementById('imageFile').value          = '';
        document.getElementById('imagePreview').src         = '';
        document.getElementById('imagePreviewWrap').classList.add('hidden');
        document.getElementById('dropPlaceholder').classList.remove('hidden');
    }

    // ─── DRAG & DROP ─────────────────────────────────────────────────────────
    function handleDragOver(e) {
        e.preventDefault();
        document.getElementById('dropZone').classList.add('border-purple-500', 'bg-purple-50');
    }

    function handleDragLeave(e) {
        document.getElementById('dropZone').classList.remove('border-purple-500', 'bg-purple-50');
    }

    function handleDrop(e) {
        e.preventDefault();
        document.getElementById('dropZone').classList.remove('border-purple-500', 'bg-purple-50');
        const file = e.dataTransfer.files[0];
        if (!file || !file.type.startsWith('image/')) {
            Swal.fire({ title: 'File Tidak Valid', text: 'Hanya file gambar yang diterima.', icon: 'warning', confirmButtonColor: '#7C3AED' });
            return;
        }
        // Inject ke input file
        const dt = new DataTransfer();
        dt.items.add(file);
        document.getElementById('imageFile').files = dt.files;
        handleFileSelect(document.getElementById('imageFile'));
    }

    // ─── SAVE (POST / PUT) ────────────────────────────────────────────────────
    async function saveProduct(e) {
        e.preventDefault();

        // Cek token dulu
        if (!JWT_TOKEN) {
            Swal.fire({ title: 'Sesi Habis', text: 'Silakan login ulang.', icon: 'warning', confirmButtonColor: '#7C3AED' })
                .then(() => window.location.href = '{{ route('login') }}');
            return;
        }

        const id         = document.getElementById('productId').value;
        const fileInput  = document.getElementById('imageFile');
        const hasNewFile = fileInput.files.length > 0;

        // Gunakan FormData agar bisa kirim file sekaligus
        const fd = new FormData();
        fd.append('product_name',   document.getElementById('product_name').value);
        fd.append('brand',          document.getElementById('brand').value);
        fd.append('price',          document.getElementById('price').value);
        fd.append('stock',          document.getElementById('stock').value);
        fd.append('description',    document.getElementById('description').value);
        fd.append('specifications', document.getElementById('specifications').value);

        if (hasNewFile) {
            fd.append('image', fileInput.files[0]); // field name 'image'
        }

        Swal.fire({ title: 'Menyimpan...', allowOutsideClick: false, showConfirmButton: false, didOpen: () => Swal.showLoading() });

        try {
            // Kirim langsung ke Node.js backend (pakai JWT token)
            const url    = id ? `${API_URL}/products/${id}` : `${API_URL}/products`;
            const method = id ? 'PUT' : 'POST';

            const res    = await fetch(url, {
                method,
                headers: { 'Authorization': 'Bearer ' + JWT_TOKEN },
                // JANGAN set Content-Type — browser akan set multipart/form-data + boundary otomatis
                body: fd,
            });

            const result = await res.json();

            if (res.ok && result.success) {
                Swal.fire({
                    title: 'Berhasil!',
                    text:  id ? 'Produk berhasil diupdate.' : 'Produk berhasil ditambahkan.',
                    icon:  'success',
                    timer: 1500,
                    showConfirmButton: false,
                    timerProgressBar: true,
                }).then(() => location.reload());
            } else {
                Swal.fire({ title: 'Gagal', text: result.message || 'Terjadi kesalahan', icon: 'error', confirmButtonColor: '#7C3AED' });
            }
        } catch (err) {
            Swal.fire({ title: 'Error', text: 'Koneksi ke server gagal', icon: 'error', confirmButtonColor: '#7C3AED' });
        }
    }

    // ─── DELETE ───────────────────────────────────────────────────────────────
    async function deleteProduct(id) {
        const { isConfirmed } = await Swal.fire({
            title: 'Hapus Produk?',
            text:  'Data yang dihapus tidak dapat dikembalikan.',
            icon:  'warning',
            showCancelButton:   true,
            confirmButtonText:  '<i class="fas fa-trash mr-1"></i> Hapus',
            cancelButtonText:   'Batal',
            confirmButtonColor: '#EF4444',
            cancelButtonColor:  '#9CA3AF',
        });
        if (!isConfirmed) return;

        try {
            const res    = await fetch(`${API_URL}/products/${id}`, {
                method:  'DELETE',
                headers: { 'Authorization': 'Bearer ' + JWT_TOKEN },
            });
            const result = await res.json();

            if (res.ok && result.success) {
                Swal.fire({ title: 'Dihapus!', icon: 'success', timer: 1500, showConfirmButton: false, timerProgressBar: true })
                    .then(() => location.reload());
            } else {
                Swal.fire({ title: 'Gagal', text: result.message, icon: 'error', confirmButtonColor: '#7C3AED' });
            }
        } catch (err) {
            Swal.fire({ title: 'Error', text: 'Koneksi gagal', icon: 'error', confirmButtonColor: '#7C3AED' });
        }
    }

    // Close modal saat klik backdrop
    document.getElementById('productModal').addEventListener('click', function(e) {
        if (e.target === this) closeModal();
    });
</script>
@endpush

@push('styles')
<style>
    .input-field { border: 2px solid #E5E7EB; transition: all 0.3s ease; }
    .input-field:focus { border-color: #7C3AED; box-shadow: 0 0 0 4px rgba(124,58,237,0.1); outline: none; }
    .btn-primary { background: linear-gradient(135deg, #7C3AED 0%, #EC4899 100%); transition: all 0.3s ease; }
    .btn-primary:hover { opacity: 0.9; transform: translateY(-1px); }
    .product-img-bg { background: linear-gradient(135deg, #EDE9FE 0%, #FCE7F3 100%); }
    .table-row-hover:hover { background: #FAFAF9; }
    #dropZone { min-height: 120px; }
</style>
@endpush

@endsection
