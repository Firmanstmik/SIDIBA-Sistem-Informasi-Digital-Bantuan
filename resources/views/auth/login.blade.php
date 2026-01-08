<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SIDIBA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center">
    <div class="max-w-md w-full bg-white rounded-lg shadow-lg overflow-hidden">
        <!-- Header -->
        <div class="bg-green-600 text-white p-6 text-center">
            <h1 class="text-2xl font-bold">🌾 SIDIBA</h1>
            <p class="text-green-100">Sistem Informasi Bantuan Dinas Pertanian</p>
        </div>

        <!-- Login Form -->
        <div class="p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-6 text-center">Masuk ke Sistem</h2>

            @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-4">
                    <ul class="list-disc list-inside text-sm">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if(session('success'))
                <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf
                
                <div class="mb-4">
                    <label for="username" class="block text-gray-700 text-sm font-medium mb-2">
                        <i class="fas fa-user mr-2"></i>Username
                    </label>
                    <input type="text" 
                           name="username" 
                           id="username"
                           value="{{ old('username') }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                           placeholder="Masukkan username"
                           required
                           autofocus>
                </div>

                <div class="mb-6">
                    <label for="password" class="block text-gray-700 text-sm font-medium mb-2">
                        <i class="fas fa-lock mr-2"></i>Password
                    </label>
                    <input type="password" 
                           name="password" 
                           id="password"
                           class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                           placeholder="Masukkan password"
                           required>
                </div>

                <button type="submit" 
                        class="w-full bg-green-600 hover:bg-green-700 text-white font-medium py-3 px-4 rounded-lg transition duration-200">
                    <i class="fas fa-sign-in-alt mr-2"></i>Masuk
                </button>
            </form>

            <div class="mt-6 text-center">
                <a href="{{ route('home') }}" class="text-green-600 hover:text-green-700 text-sm">
                    <i class="fas fa-arrow-left mr-1"></i>Kembali ke Halaman Utama
                </a>
            </div>
        </div>

        <!-- Footer -->
        <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
            <p class="text-gray-500 text-sm text-center">
                &copy; {{ date('Y') }} Dinas Pertanian. All rights reserved.
            </p>
        </div>
    </div>

    <script>
        // Debug info
        console.log('Login page loaded successfully');
        console.log('Form action:', '{{ route('login') }}');
        console.log('CSRF token:', '{{ csrf_token() }}');
    </script>
</body>
</html>