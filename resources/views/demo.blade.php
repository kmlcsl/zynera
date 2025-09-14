<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Demo Info Akun - GampongStore</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .gampong-primary {
            background-color: #2D5016;
        }

        .text-gampong-primary {
            color: #2D5016;
        }

        .user-card {
            transition: all 0.3s ease;
        }

        .user-card:hover {
            transform: translateY(-8px);
        }
    </style>
</head>

<body class="bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="w-10 h-10 mr-3">
                        <div class="w-full h-full bg-green-600 rounded-lg flex items-center justify-center">
                            <i class="fas fa-store text-white"></i>
                        </div>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold text-gampong-primary">GampongStore</h1>
                        <p class="text-sm text-gray-600">Demo Info Akun</p>
                    </div>
                </div>
                <a href="http://127.0.0.1:8000"
                    class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Kembali ke Beranda
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Hero Section -->
        <div class="text-center mb-12">
            <div class="inline-block bg-blue-100 text-blue-800 px-4 py-2 rounded-full text-sm font-semibold mb-4">
                🔐 Akun Demo untuk Testing
            </div>
            <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">
                Info Akun Demo <span class="text-green-600">GampongStore</span>
            </h1>
            <p class="text-lg text-gray-600 max-w-3xl mx-auto leading-relaxed">
                Gunakan akun demo berikut untuk mencoba fitur-fitur berbeda berdasarkan tipe pengguna.
                <span class="block mt-2 text-green-600 font-medium">Pilih sesuai role yang ingin Anda coba!</span>
            </p>
        </div>

        <!-- Demo Accounts Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
            <!-- Admin Account -->
            <div
                class="user-card bg-white rounded-2xl shadow-lg p-6 border border-red-100 hover:border-red-200 hover:shadow-2xl">
                <div class="text-center mb-6">
                    <div
                        class="w-20 h-20 mx-auto mb-4 bg-gradient-to-br from-red-500 to-red-600 rounded-2xl flex items-center justify-center shadow-xl">
                        <i class="fas fa-user-shield text-2xl text-white"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">👑 Admin</h3>
                    <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-sm font-medium">Super User</span>
                </div>

                <div class="space-y-3 mb-6">
                    <div class="bg-gray-50 p-3 rounded-lg">
                        <p class="text-sm text-gray-600 mb-1"><i class="fas fa-user mr-2"></i>Nama</p>
                        <p class="font-semibold text-gray-900">Admin GampongStore</p>
                    </div>
                    <div class="bg-gray-50 p-3 rounded-lg">
                        <p class="text-sm text-gray-600 mb-1"><i class="fas fa-envelope mr-2"></i>Email</p>
                        <p class="font-semibold text-gray-900">admin@gampongstore.com</p>
                    </div>
                    <div class="bg-gray-50 p-3 rounded-lg">
                        <p class="text-sm text-gray-600 mb-1"><i class="fas fa-key mr-2"></i>Password</p>
                        <p class="font-semibold text-gray-900">admin123</p>
                    </div>
                </div>

                {{-- <div class="mb-4">
                    <h4 class="font-semibold text-gray-900 mb-2">🎯 Akses Fitur:</h4>
                    <ul class="text-sm text-gray-600 space-y-1">
                        <li>• Dashboard Admin Lengkap</li>
                        <li>• Kelola Semua User & Produk</li>
                        <li>• Laporan & Analytics</li>
                        <li>• System Settings</li>
                    </ul>
                </div> --}}

                <button onclick="copyCredentials('admin@gampongstore.com', 'admin123')"
                    class="w-full bg-gradient-to-r from-red-600 to-red-700 text-white px-4 py-3 rounded-xl font-semibold hover:from-red-700 hover:to-red-800 transition-all duration-300 shadow-lg hover:shadow-xl">
                    <i class="fas fa-copy mr-2"></i>Copy Kredensial
                </button>
            </div>

            <!-- Produsen Account -->
            <div
                class="user-card bg-white rounded-2xl shadow-lg p-6 border border-green-100 hover:border-green-200 hover:shadow-2xl">
                <div class="text-center mb-6">
                    <div
                        class="w-20 h-20 mx-auto mb-4 bg-gradient-to-br from-green-500 to-green-600 rounded-2xl flex items-center justify-center shadow-xl">
                        <i class="fas fa-store text-2xl text-white"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">🌾 Produsen</h3>
                    <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm font-medium">Penjual</span>
                </div>

                <div class="space-y-3 mb-6">
                    <div class="bg-gray-50 p-3 rounded-lg">
                        <p class="text-sm text-gray-600 mb-1"><i class="fas fa-user mr-2"></i>Nama</p>
                        <p class="font-semibold text-gray-900">Ahmad Petani Aceh</p>
                    </div>
                    <div class="bg-gray-50 p-3 rounded-lg">
                        <p class="text-sm text-gray-600 mb-1"><i class="fas fa-envelope mr-2"></i>Email</p>
                        <p class="font-semibold text-gray-900">produsen@gampongstore.com</p>
                    </div>
                    <div class="bg-gray-50 p-3 rounded-lg">
                        <p class="text-sm text-gray-600 mb-1"><i class="fas fa-key mr-2"></i>Password</p>
                        <p class="font-semibold text-gray-900">produsen123</p>
                    </div>
                </div>

                {{-- <div class="mb-4">
                    <h4 class="font-semibold text-gray-900 mb-2">🎯 Akses Fitur:</h4>
                    <ul class="text-sm text-gray-600 space-y-1">
                        <li>• Dashboard Produsen</li>
                        <li>• Kelola Produk & Stok</li>
                        <li>• Kelola Pesanan Masuk</li>
                        <li>• Laporan Penjualan</li>
                    </ul>
                </div> --}}

                <button onclick="copyCredentials('produsen@gampongstore.com', 'produsen123')"
                    class="w-full bg-gradient-to-r from-green-600 to-green-700 text-white px-4 py-3 rounded-xl font-semibold hover:from-green-700 hover:to-green-800 transition-all duration-300 shadow-lg hover:shadow-xl">
                    <i class="fas fa-copy mr-2"></i>Copy Kredensial
                </button>
            </div>

            <!-- Konsumen Account -->
            <div
                class="user-card bg-white rounded-2xl shadow-lg p-6 border border-blue-100 hover:border-blue-200 hover:shadow-2xl">
                <div class="text-center mb-6">
                    <div
                        class="w-20 h-20 mx-auto mb-4 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center shadow-xl">
                        <i class="fas fa-shopping-cart text-2xl text-white"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">🛒 Konsumen</h3>
                    <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-sm font-medium">Pembeli</span>
                </div>

                <div class="space-y-3 mb-6">
                    <div class="bg-gray-50 p-3 rounded-lg">
                        <p class="text-sm text-gray-600 mb-1"><i class="fas fa-user mr-2"></i>Nama</p>
                        <p class="font-semibold text-gray-900">Siti Pembeli</p>
                    </div>
                    <div class="bg-gray-50 p-3 rounded-lg">
                        <p class="text-sm text-gray-600 mb-1"><i class="fas fa-envelope mr-2"></i>Email</p>
                        <p class="font-semibold text-gray-900">konsumen@gampongstore.com</p>
                    </div>
                    <div class="bg-gray-50 p-3 rounded-lg">
                        <p class="text-sm text-gray-600 mb-1"><i class="fas fa-key mr-2"></i>Password</p>
                        <p class="font-semibold text-gray-900">konsumen123</p>
                    </div>
                </div>

                {{-- <div class="mb-4">
                    <h4 class="font-semibold text-gray-900 mb-2">🎯 Akses Fitur:</h4>
                    <ul class="text-sm text-gray-600 space-y-1">
                        <li>• Browse & Beli Produk</li>
                        <li>• Kelola Keranjang</li>
                        <li>• Riwayat Pesanan</li>
                        <li>• Review & Rating</li>
                    </ul>
                </div> --}}

                <button onclick="copyCredentials('konsumen@gampongstore.com', 'konsumen123')"
                    class="w-full bg-gradient-to-r from-blue-600 to-blue-700 text-white px-4 py-3 rounded-xl font-semibold hover:from-blue-700 hover:to-blue-800 transition-all duration-300 shadow-lg hover:shadow-xl">
                    <i class="fas fa-copy mr-2"></i>Copy Kredensial
                </button>
            </div>

            <!-- Kurir Account -->
            <div
                class="user-card bg-white rounded-2xl shadow-lg p-6 border border-yellow-100 hover:border-yellow-200 hover:shadow-2xl">
                <div class="text-center mb-6">
                    <div
                        class="w-20 h-20 mx-auto mb-4 bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-2xl flex items-center justify-center shadow-xl">
                        <i class="fas fa-truck text-2xl text-white"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">🚚 Kurir</h3>
                    <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-sm font-medium">Driver</span>
                </div>

                <div class="space-y-3 mb-6">
                    <div class="bg-gray-50 p-3 rounded-lg">
                        <p class="text-sm text-gray-600 mb-1"><i class="fas fa-user mr-2"></i>Nama</p>
                        <p class="font-semibold text-gray-900">Budi Kurir Gampong</p>
                    </div>
                    <div class="bg-gray-50 p-3 rounded-lg">
                        <p class="text-sm text-gray-600 mb-1"><i class="fas fa-envelope mr-2"></i>Email</p>
                        <p class="font-semibold text-gray-900">kurir@gampongstore.com</p>
                    </div>
                    <div class="bg-gray-50 p-3 rounded-lg">
                        <p class="text-sm text-gray-600 mb-1"><i class="fas fa-key mr-2"></i>Password</p>
                        <p class="font-semibold text-gray-900">kurir123</p>
                    </div>
                </div>

                {{-- <div class="mb-4">
                    <h4 class="font-semibold text-gray-900 mb-2">🎯 Akses Fitur:</h4>
                    <ul class="text-sm text-gray-600 space-y-1">
                        <li>• Dashboard Kurir</li>
                        <li>• Kelola Pengiriman</li>
                        <li>• Update Status Delivery</li>
                        <li>• Riwayat Pengiriman</li>
                    </ul>
                </div> --}}

                <button onclick="copyCredentials('kurir@gampongstore.com', 'kurir123')"
                    class="w-full bg-gradient-to-r from-yellow-600 to-yellow-700 text-white px-4 py-3 rounded-xl font-semibold hover:from-yellow-700 hover:to-yellow-800 transition-all duration-300 shadow-lg hover:shadow-xl">
                    <i class="fas fa-copy mr-2"></i>Copy Kredensial
                </button>
            </div>

            <!-- Ahli Gizi Account -->
            <div
                class="user-card bg-white rounded-2xl shadow-lg p-6 border border-purple-100 hover:border-purple-200 hover:shadow-2xl lg:col-span-1 md:col-span-2 lg:col-start-2">
                <div class="text-center mb-6">
                    <div
                        class="w-20 h-20 mx-auto mb-4 bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl flex items-center justify-center shadow-xl">
                        <i class="fas fa-heartbeat text-2xl text-white"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">💊 Ahli Gizi</h3>
                    <span
                        class="bg-purple-100 text-purple-700 px-3 py-1 rounded-full text-sm font-medium">Expert</span>
                </div>

                <div class="space-y-3 mb-6">
                    <div class="bg-gray-50 p-3 rounded-lg">
                        <p class="text-sm text-gray-600 mb-1"><i class="fas fa-user mr-2"></i>Nama</p>
                        <p class="font-semibold text-gray-900">Dr. Sari Nutrisi</p>
                    </div>
                    <div class="bg-gray-50 p-3 rounded-lg">
                        <p class="text-sm text-gray-600 mb-1"><i class="fas fa-envelope mr-2"></i>Email</p>
                        <p class="font-semibold text-gray-900">ahligizi@gampongstore.com</p>
                    </div>
                    <div class="bg-gray-50 p-3 rounded-lg">
                        <p class="text-sm text-gray-600 mb-1"><i class="fas fa-key mr-2"></i>Password</p>
                        <p class="font-semibold text-gray-900">ahligizi123</p>
                    </div>
                </div>

                {{-- <div class="mb-4">
                    <h4 class="font-semibold text-gray-900 mb-2">🎯 Akses Fitur:</h4>
                    <ul class="text-sm text-gray-600 space-y-1">
                        <li>• Dashboard Ahli Gizi</li>
                        <li>• Validasi Info Nutrisi</li>
                        <li>• Buat Artikel Kesehatan</li>
                        <li>• Konsultasi Gizi</li>
                    </ul>
                </div> --}}

                <button onclick="copyCredentials('ahligizi@gampongstore.com', 'ahligizi123')"
                    class="w-full bg-gradient-to-r from-purple-600 to-purple-700 text-white px-4 py-3 rounded-xl font-semibold hover:from-purple-700 hover:to-purple-800 transition-all duration-300 shadow-lg hover:shadow-xl">
                    <i class="fas fa-copy mr-2"></i>Copy Kredensial
                </button>
            </div>
        </div>

        <!-- Info Section -->
        <div class="mt-12 bg-green-50 border border-green-200 rounded-2xl p-6 lg:p-8">
            <div class="text-center">
                <div class="w-16 h-16 mx-auto mb-4 bg-green-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-info-circle text-2xl text-green-600"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-4">📝 Petunjuk Penggunaan</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-left">
                    <div>
                        <h4 class="font-semibold text-gray-900 mb-2">🔐 Cara Login:</h4>
                        <ol class="text-sm text-gray-600 space-y-1">
                            <li>1. Klik button "Copy Kredensial" pada akun yang diinginkan</li>
                            <li>2. Pergi ke halaman login GampongStore</li>
                            <li>3. Paste email dan password yang sudah dicopy</li>
                            <li>4. Login dan jelajahi fitur sesuai role!</li>
                        </ol>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-900 mb-2">⚠️ Catatan Penting:</h4>
                        <ul class="text-sm text-gray-600 space-y-1">
                            <li>• Ini adalah akun demo untuk testing saja</li>
                            <li>• Data dapat berubah sewaktu-waktu</li>
                            <li>• Jangan gunakan untuk transaksi nyata</li>
                            <li>• Setiap role memiliki akses fitur berbeda</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-8 mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="flex items-center justify-center mb-4">
                <div class="w-8 h-8 bg-green-600 rounded-lg flex items-center justify-center mr-3">
                    <i class="fas fa-store text-white"></i>
                </div>
                <span class="text-lg font-semibold">GampongStore Demo</span>
            </div>
            <p class="text-gray-400 text-sm">Platform e-commerce produk pangan lokal Aceh Barat</p>
        </div>
    </footer>

    <!-- Toast Notification -->
    <div id="toast"
        class="fixed bottom-4 right-4 bg-green-600 text-white px-6 py-3 rounded-lg shadow-lg transform translate-x-full transition-transform duration-300 z-50">
        <i class="fas fa-check mr-2"></i>
        <span>Kredensial berhasil dicopy!</span>
    </div>

    <script>
        function copyCredentials(email, password) {
            // Copy ke clipboard
            const credentials = `Email: ${email}\nPassword: ${password}`;
            navigator.clipboard.writeText(credentials).then(function() {
                showToast();
            }).catch(function() {
                // Fallback untuk browser yang tidak support clipboard API
                const textArea = document.createElement('textarea');
                textArea.value = credentials;
                document.body.appendChild(textArea);
                textArea.select();
                document.execCommand('copy');
                document.body.removeChild(textArea);
                showToast();
            });
        }

        function showToast() {
            const toast = document.getElementById('toast');
            toast.classList.remove('translate-x-full');
            setTimeout(() => {
                toast.classList.add('translate-x-full');
            }, 3000);
        }
    </script>
</body>

</html>
