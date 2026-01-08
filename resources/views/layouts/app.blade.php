<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIDIBA - Sistem Informasi Data Bantuan</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 flex flex-col min-h-screen">
    <!-- Navbar -->
    <nav class="bg-gray-800 text-white shadow-lg">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center py-3">
                <a href="{{ auth()->check() ? route('dashboard') : route('home') }}" class="text-xl font-bold">SIDIBA</a>
                
                <div class="flex items-center space-x-6">
                    @auth
                        <a href="{{ route('dashboard') }}" class="hover:text-gray-300">🏠 Dashboard</a>
                        <a href="{{ route('beneficiaries.index') }}" class="hover:text-gray-300">📋 Data Bantuan</a>

                        @if(auth()->user()->isAdmin())
                        <a href="{{ route('users.index') }}" class="hover:text-gray-300">👥 Kelola User</a>
                        <a href="{{ route('bantuan.index') }}" class="hover:text-gray-300">🎁 Kelola Bantuan</a>
                        @endif
                        
                        <span class="text-gray-300">👤 {{ auth()->user()->nama }}</span>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="bg-gray-700 hover:bg-gray-600 px-3 py-1 rounded">Logout</button>
                        </form>
                    @else
                        <a href="{{ route('home') }}" class="hover:text-gray-300">🏠 Home</a>
                        <a href="{{ route('login') }}" class="bg-gray-700 hover:bg-gray-600 px-3 py-1 rounded">Login</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="container mx-auto px-4 py-6 flex-1">
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white text-center py-3 mt-auto">
        <small>© {{ date('Y') }} Dinas Pertanian Kabupaten Lombok Tengah - SIDIBA</small>
    </footer>
</body>
</html>