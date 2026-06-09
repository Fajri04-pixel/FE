<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Halaman Tidak Ditemukan | HP Market</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; }
        .gradient-primary { background: linear-gradient(135deg, #1F4E79 0%, #0A9396 100%); }
        .text-gradient {
            background: linear-gradient(135deg, #1F4E79 0%, #0A9396 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .bg-pattern {
            background-color: #F5F3FF;
            background-image: radial-gradient(#DDD6FE 1px, transparent 1px);
            background-size: 24px 24px;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50%       { transform: translateY(-20px); }
        }
        .float-anim { animation: float 3s ease-in-out infinite; }
    </style>
</head>
<body class="min-h-screen bg-pattern flex items-center justify-center px-4">
    <div class="text-center max-w-md">
        <div class="float-anim text-8xl mb-6">ðŸ˜µ</div>
        <h1 class="text-7xl font-extrabold text-gradient mb-2">404</h1>
        <h2 class="text-2xl font-bold text-gray-800 mb-3">Halaman Tidak Ditemukan</h2>
        <p class="text-gray-500 mb-8">Sepertinya halaman yang kamu cari tidak ada atau sudah dipindahkan.</p>
        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            <a href="/"
                class="gradient-primary text-white px-6 py-3 rounded-xl font-bold inline-flex items-center justify-center gap-2 hover:opacity-90 transition">
                <i class="fas fa-house"></i> Ke Beranda
            </a>
            <button onclick="history.back()"
                class="border-2 border-gray-300 text-gray-700 px-6 py-3 rounded-xl font-bold inline-flex items-center justify-center gap-2 hover:bg-gray-50 transition">
                <i class="fas fa-arrow-left"></i> Kembali
            </button>
        </div>
    </div>
</body>
</html>

