@extends('layouts.app')

@section('title', 'Kelola Pengguna - Admin HP Market')

@section('content')
<div class="container mx-auto px-4 md:px-6 py-10">

    {{-- Header --}}
    <div class="flex items-center gap-3 mb-8 fade-in-up">
        <div class="w-10 h-10 gradient-primary rounded-xl flex items-center justify-center shadow">
            <i class="fas fa-users text-white"></i>
        </div>
        <div>
            <h1 class="text-2xl font-extrabold text-gray-900">Kelola Pengguna</h1>
            <p class="text-sm text-gray-500">{{ count($users) }} pengguna terdaftar</p>
        </div>
    </div>

    {{-- Stats --}}
    @php
        $adminCount = count(array_filter($users, fn($u) => ($u['role'] ?? '') === 'admin'));
        $userCount  = count($users) - $adminCount;
    @endphp
    <div class="grid grid-cols-3 gap-4 mb-8 fade-in-up">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 bg-gray-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-users text-gray-600"></i>
                </div>
                <div>
                    <p class="text-2xl font-extrabold text-gray-800">{{ count($users) }}</p>
                    <p class="text-xs text-gray-500">Total</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl border border-purple-100 shadow-sm p-4">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 bg-purple-50 rounded-xl flex items-center justify-center">
                    <i class="fas fa-user-shield text-purple-600"></i>
                </div>
                <div>
                    <p class="text-2xl font-extrabold text-purple-700">{{ $adminCount }}</p>
                    <p class="text-xs text-gray-500">Admin</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl border border-blue-100 shadow-sm p-4">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 bg-blue-50 rounded-xl flex items-center justify-center">
                    <i class="fas fa-user text-blue-600"></i>
                </div>
                <div>
                    <p class="text-2xl font-extrabold text-blue-700">{{ $userCount }}</p>
                    <p class="text-xs text-gray-500">Member</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden fade-in-up">
        @if(count($users) > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="px-5 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Pengguna</th>
                        <th class="px-5 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Kontak</th>
                        <th class="px-5 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Role</th>
                        <th class="px-5 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Bergabung</th>
                        <th class="px-5 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($users as $user)
                    <tr class="table-row-hover transition">
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 gradient-primary rounded-xl flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
                                    {{ strtoupper(substr($user['username'] ?? $user['name'] ?? 'U', 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-800 text-sm">{{ $user['username'] ?? $user['name'] ?? '-' }}</p>
                                    <p class="text-xs text-gray-400">{{ $user['email'] ?? '-' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-4">
                            <p class="text-sm text-gray-600">{{ $user['phone'] ?? '-' }}</p>
                            @if(!empty($user['address']))
                            <p class="text-xs text-gray-400 truncate max-w-[160px]">{{ $user['address'] }}</p>
                            @endif
                        </td>
                        <td class="px-5 py-4">
                            @if(($user['role'] ?? '') === 'admin')
                                <span class="badge badge-purple">
                                    <i class="fas fa-shield-halved text-[9px] mr-0.5"></i> Admin
                                </span>
                            @else
                                <span class="badge badge-info">
                                    <i class="fas fa-user text-[9px] mr-0.5"></i> Member
                                </span>
                            @endif
                        </td>
                        <td class="px-5 py-4">
                            <p class="text-sm text-gray-600">
                                {{ isset($user['created_at']) ? date('d M Y', strtotime($user['created_at'])) : '-' }}
                            </p>
                        </td>
                        <td class="px-5 py-4">
                            @if(($user['role'] ?? '') !== 'admin')
                            <button onclick="deleteUser({{ $user['id'] ?? 0 }}, '{{ addslashes($user['username'] ?? $user['name'] ?? '') }}')"
                                class="w-8 h-8 bg-red-50 text-red-400 hover:bg-red-100 hover:text-red-600 rounded-lg flex items-center justify-center transition">
                                <i class="fas fa-trash text-xs"></i>
                            </button>
                            @else
                            <div class="w-8 h-8 bg-gray-50 rounded-lg flex items-center justify-center" title="Admin tidak bisa dihapus">
                                <i class="fas fa-lock text-gray-300 text-xs"></i>
                            </div>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="flex flex-col items-center justify-center py-20 text-center">
            <div class="w-20 h-20 bg-purple-50 rounded-3xl flex items-center justify-center mb-4">
                <i class="fas fa-users text-3xl text-purple-300"></i>
            </div>
            <h3 class="text-lg font-bold text-gray-600 mb-1">Belum Ada Pengguna</h3>
            <p class="text-sm text-gray-400">Pengguna yang mendaftar akan muncul di sini</p>
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    const adminUsersUrl = '{{ url('admin/users') }}';
    const csrfToken = '{{ csrf_token() }}';

    async function deleteUser(id, name) {
        const { isConfirmed } = await Swal.fire({
            title: 'Hapus Pengguna?',
            html: `Akun <strong>${name}</strong> akan dihapus permanen.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: '<i class="fas fa-trash mr-1"></i> Hapus',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#EF4444',
            cancelButtonColor: '#9CA3AF',
        });
        if (!isConfirmed) return;

        try {
            const res = await fetch(`${adminUsersUrl}/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                },
            });
            const data = await res.json();
            if (res.ok && (data.success === undefined || data.success === true)) {
                Swal.fire({ title: 'Dihapus!', icon: 'success', timer: 1500, showConfirmButton: false })
                    .then(() => location.reload());
            } else {
                Swal.fire({ title: 'Gagal', text: data.message || 'Terjadi kesalahan', icon: 'error', confirmButtonColor: '#1F4E79' });
            }
        } catch (err) {
            Swal.fire({ title: 'Error', text: 'Koneksi gagal', icon: 'error', confirmButtonColor: '#1F4E79' });
        }
    }
</script>
@endpush

@push('styles')
<style>
    .table-row-hover:hover { background: #FAFAF9; }
</style>
@endpush

@endsection

