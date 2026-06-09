<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - HP Market</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; }
        .gradient-primary { background: linear-gradient(135deg, #7C3AED 0%, #EC4899 100%); }
        .gradient-header  { background: linear-gradient(160deg, #0f2042 0%, #1a3a6b 50%, #1e4d8c 100%); }
        .input-field { border: 2px solid #E5E7EB; transition: all 0.3s ease; }
        .input-field:focus { border-color: #7C3AED; box-shadow: 0 0 0 4px rgba(124,58,237,0.1); outline: none; }
        .btn-primary { background: linear-gradient(135deg, #7C3AED 0%, #EC4899 100%); transition: all 0.3s ease; }
        .btn-primary:hover { opacity: 0.9; transform: translateY(-1px); box-shadow: 0 8px 25px rgba(124,58,237,0.4); }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(24px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        @keyframes floatIcon {
            0%,100% { transform: translateY(0px) rotate(-2deg); }
            50%     { transform: translateY(-8px) rotate(2deg); }
        }
        .fade-in    { animation: fadeInUp 0.5s ease-out forwards; }
        .float-icon { animation: floatIcon 3s ease-in-out infinite; }
        .bg-pattern {
            background-color: #EEF2FF;
            background-image: radial-gradient(#C7D2FE 1px, transparent 1px);
            background-size: 24px 24px;
        }
        .text-gradient {
            background: linear-gradient(135deg, #7C3AED 0%, #EC4899 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .icon-ring {
            box-shadow: 0 0 0 8px rgba(255,255,255,0.08),
                        0 0 0 16px rgba(255,255,255,0.05),
                        0 8px 32px rgba(0,0,0,0.3);
        }
    </style>
</head>
<body class="min-h-screen bg-pattern flex items-center justify-center px-4 py-10">

    <div class="w-full max-w-lg fade-in">

        {{-- Logo --}}
        <div class="text-center mb-6">
            <a href="/" class="inline-flex items-center space-x-2.5">
                <div class="w-9 h-9 gradient-primary rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-mobile-screen-button text-white text-sm"></i>
                </div>
                <span class="text-xl font-bold text-gradient">HP Market</span>
            </a>
        </div>

        {{-- Card --}}
        <div class="bg-white rounded-3xl shadow-2xl overflow-hidden border border-gray-100">

            {{-- Header dengan lingkaran ikon HP --}}
            <div class="gradient-header px-8 pt-10 pb-8 relative overflow-hidden flex flex-col items-center">
                <div class="absolute -top-16 -right-16 w-52 h-52 rounded-full" style="background:rgba(255,255,255,0.04)"></div>
                <div class="absolute -bottom-10 -left-10 w-36 h-36 rounded-full" style="background:rgba(255,255,255,0.04)"></div>
                <div class="absolute top-6 left-8 w-3 h-3 rounded-full" style="background:rgba(255,255,255,0.2)"></div>
                <div class="absolute top-16 right-12 w-2 h-2 rounded-full" style="background:rgba(255,255,255,0.15)"></div>

                <div class="relative mb-5">
                    <div class="w-32 h-32 rounded-full flex items-center justify-center icon-ring"
                         style="background: rgba(20, 60, 120, 0.6);">
                        <div class="w-24 h-24 rounded-full flex items-center justify-center float-icon"
                             style="background: linear-gradient(145deg, #1a3a6b, #0f2042);">
                            <i class="fas fa-mobile-screen-button text-white" style="font-size: 42px; filter: drop-shadow(0 3px 10px rgba(0,0,0,0.5));"></i>
                        </div>
                    </div>
                    <div class="absolute bottom-0 right-0 w-8 h-8 bg-purple-500 rounded-full flex items-center justify-center border-2 border-white shadow-lg">
                        <i class="fas fa-plus text-white text-xs"></i>
                    </div>
                </div>

                <h1 class="text-2xl font-extrabold text-white mb-1">Buat Akun Baru</h1>
                <p class="text-white/70 text-sm">Bergabung dan mulai belanja HP impianmu</p>
            </div>

            <div class="p-8">
                {{-- Flash Error --}}
                @if(session('error'))
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-5 flex items-center gap-2 text-sm">
                    <i class="fas fa-circle-xmark text-red-500"></i> {{ session('error') }}
                </div>
                @endif

                <form method="POST" action="{{ route('register.post') }}" class="space-y-4">
                    @csrf

                    {{-- Nama Lengkap --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                            <i class="fas fa-user text-purple-400 mr-1"></i> Nama Lengkap <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="username" value="{{ old('username') }}" required
                               class="input-field w-full px-4 py-3 rounded-xl bg-gray-50 text-sm"
                               placeholder="Masukkan nama lengkap">
                        @error('username')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                            <i class="fas fa-envelope text-purple-400 mr-1"></i> Email <span class="text-red-500">*</span>
                        </label>
                        <input type="email" name="email" value="{{ old('email') }}" required
                               class="input-field w-full px-4 py-3 rounded-xl bg-gray-50 text-sm"
                               placeholder="contoh@email.com">
                        @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                            <i class="fas fa-lock text-purple-400 mr-1"></i> Password <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="password" name="password" id="passwordInput" required
                                   class="input-field w-full px-4 py-3 rounded-xl bg-gray-50 text-sm pr-11"
                                   placeholder="Minimal 6 karakter"
                                   oninput="checkPasswordStrength(this.value)">
                            <button type="button" onclick="togglePassword()"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition">
                                <i class="fas fa-eye" id="eyeIcon"></i>
                            </button>
                        </div>
                        <div id="strengthBar" class="mt-2 h-1.5 rounded-full bg-gray-200 overflow-hidden hidden">
                            <div id="strengthFill" class="h-full rounded-full transition-all duration-300" style="width:0%"></div>
                        </div>
                        <p id="strengthText" class="text-xs mt-1 hidden"></p>
                        @error('password')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Telepon --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                            <i class="fas fa-phone text-purple-400 mr-1"></i> Nomor Telepon
                        </label>
                        <input type="text" name="phone" value="{{ old('phone') }}"
                               class="input-field w-full px-4 py-3 rounded-xl bg-gray-50 text-sm"
                               placeholder="08123456789">
                    </div>

                    {{-- Alamat --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                            <i class="fas fa-map-location-dot text-purple-400 mr-1"></i> Alamat
                        </label>
                        <textarea name="address" rows="2"
                                  class="input-field w-full px-4 py-3 rounded-xl bg-gray-50 text-sm resize-none"
                                  placeholder="Alamat pengiriman default">{{ old('address') }}</textarea>
                    </div>

                    {{-- Syarat --}}
                    <div class="flex items-start gap-3">
                        <input type="checkbox" id="terms" required class="mt-0.5 accent-purple-600 w-4 h-4 cursor-pointer">
                        <label for="terms" class="text-xs text-gray-500 leading-relaxed cursor-pointer">
                            Saya menyetujui
                            <a href="#" class="text-purple-600 font-semibold hover:underline">Syarat & Ketentuan</a>
                            serta <a href="#" class="text-purple-600 font-semibold hover:underline">Kebijakan Privasi</a> HP Market.
                        </label>
                    </div>

                    <button type="submit"
                        class="btn-primary w-full text-white py-3 rounded-xl font-bold text-sm flex items-center justify-center gap-2">
                        <i class="fas fa-user-plus"></i> Daftar Sekarang
                    </button>
                </form>

                <p class="text-center text-sm text-gray-500 mt-5">
                    Sudah punya akun?
                    <a href="{{ route('login') }}" class="text-purple-600 hover:text-purple-800 font-bold">Masuk di sini</a>
                </p>
            </div>
        </div>

        <p class="text-center text-xs text-gray-400 mt-5">
            &copy; {{ date('Y') }} HP Market. All rights reserved.
        </p>
    </div>

<script>
    function togglePassword() {
        const input = document.getElementById('passwordInput');
        const icon  = document.getElementById('eyeIcon');
        input.type = input.type === 'password' ? 'text' : 'password';
        icon.classList.toggle('fa-eye');
        icon.classList.toggle('fa-eye-slash');
    }

    function checkPasswordStrength(val) {
        const bar  = document.getElementById('strengthBar');
        const fill = document.getElementById('strengthFill');
        const text = document.getElementById('strengthText');
        if (!val) { bar.classList.add('hidden'); text.classList.add('hidden'); return; }
        bar.classList.remove('hidden');
        text.classList.remove('hidden');
        let score = 0;
        if (val.length >= 6)  score++;
        if (val.length >= 10) score++;
        if (/[A-Z]/.test(val)) score++;
        if (/[0-9]/.test(val)) score++;
        if (/[^A-Za-z0-9]/.test(val)) score++;
        const levels = [
            { pct:'20%', color:'bg-red-400',   label:'Sangat Lemah', cls:'text-red-500' },
            { pct:'40%', color:'bg-orange-400', label:'Lemah',        cls:'text-orange-500' },
            { pct:'60%', color:'bg-yellow-400', label:'Sedang',       cls:'text-yellow-600' },
            { pct:'80%', color:'bg-blue-400',   label:'Kuat',         cls:'text-blue-600' },
            { pct:'100%',color:'bg-green-500',  label:'Sangat Kuat',  cls:'text-green-600' },
        ];
        const lv = levels[Math.max(0, score - 1)];
        fill.style.width = lv.pct;
        fill.className   = 'h-full rounded-full transition-all duration-300 ' + lv.color;
        text.textContent = lv.label;
        text.className   = 'text-xs mt-1 font-semibold ' + lv.cls;
    }
</script>
</body>
</html>
