@extends('layouts.app')

@section('content')
<div class="mb-6">
    <h3 class="text-2xl font-bold text-gray-800">📊 Dashboard SIDIBA</h3>
    <p class="text-gray-600">
        @if(auth()->user()->role === 'admin')
            Dashboard Administrator - Seluruh Data
        @else
            Dashboard {{ auth()->user()->bidang }} - {{ auth()->user()->nama }}
        @endif
    </p>
</div>

<!-- User Info -->
<div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
    <div class="flex items-center">
        <div class="bg-blue-100 p-3 rounded-full mr-4">
            <i class="fas fa-user text-blue-600 text-xl"></i>
        </div>
        <div>
            <h4 class="font-semibold text-blue-800">{{ auth()->user()->nama }}</h4>
            <p class="text-blue-700 text-sm">
                {{ auth()->user()->role === 'admin' ? 'Administrator' : auth()->user()->bidang }} 
                | {{ auth()->user()->username }}
            </p>
        </div>
    </div>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
    <!-- Total Penerima -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-blue-100 text-blue-600 mr-4">
                👥
            </div>
            <div>
                <h4 class="text-2xl font-bold text-gray-800">{{ $total_penerima }}</h4>
                <p class="text-gray-600">Total Penerima</p>
                @if(auth()->user()->role === 'user')
                <p class="text-xs text-blue-600 mt-1">Bidang {{ auth()->user()->bidang }}</p>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Kelompok Tani -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-green-100 text-green-600 mr-4">
                🌾
            </div>
            <div>
                <h4 class="text-2xl font-bold text-gray-800">{{ $total_kelompok }}</h4>
                <p class="text-gray-600">Kelompok Tani</p>
            </div>
        </div>
    </div>
    
    <!-- Jenis Bantuan -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-purple-100 text-purple-600 mr-4">
                🎁
            </div>
            <div>
                <h4 class="text-2xl font-bold text-gray-800">{{ $total_bantuan }}</h4>
                <p class="text-gray-600">Jenis Bantuan</p>
                @if(auth()->user()->role === 'user')
                <p class="text-xs text-purple-600 mt-1">Bidang {{ auth()->user()->bidang }}</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Progress Monev -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-orange-100 text-orange-600 mr-4">
                📋
            </div>
            <div>
                <h4 class="text-2xl font-bold text-gray-800">{{ $persentase_monev }}%</h4>
                <p class="text-gray-600">Progress Monev</p>
                <p class="text-xs text-orange-600 mt-1">{{ $sudah_dimonev }}/{{ $total_penerima }} penerima</p>
            </div>
        </div>
        <div class="mt-3 w-full bg-gray-200 rounded-full h-2">
            <div class="bg-orange-600 h-2 rounded-full" style="width: {{ $persentase_monev }}%"></div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
    <!-- Kelola User (Admin Only) -->
    @if(auth()->user()->isAdmin())
    <a href="{{ route('users.index') }}" class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-200 block">
        <div class="text-center">
            <div class="text-3xl mb-3">👥</div>
            <h4 class="font-semibold text-gray-800 mb-2">Kelola User</h4>
            <p class="text-gray-600 text-sm">Management user sistem</p>
        </div>
    </a>
    @endif

    <!-- Kelola Bantuan (Admin Only) -->
    @if(auth()->user()->isAdmin())
    <a href="{{ route('bantuan.index') }}" class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-200 block">
        <div class="text-center">
            <div class="text-3xl mb-3">🎁</div>
            <h4 class="font-semibold text-gray-800 mb-2">Kelola Bantuan</h4>
            <p class="text-gray-600 text-sm">Jenis-jenis bantuan</p>
        </div>
    </a>
    @endif

    <!-- Monev Bantuan -->
    <a href="{{ route('monev.index') }}" class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-200 block">
        <div class="text-center">
            <div class="text-3xl mb-3">📋</div>
            <h4 class="font-semibold text-gray-800 mb-2">Monev Bantuan</h4>
            <p class="text-gray-600 text-sm">Monitoring & Evaluasi</p>
        </div>
    </a>

    <!-- Data Penerima -->
    <a href="{{ route('beneficiaries.index') }}" class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-200 block">
        <div class="text-center">
            <div class="text-3xl mb-3">📊</div>
            <h4 class="font-semibold text-gray-800 mb-2">Data Penerima</h4>
            <p class="text-gray-600 text-sm">
                @if(auth()->user()->role === 'admin')
                    Semua Penerima
                @else
                    Penerima {{ auth()->user()->bidang }}
                @endif
            </p>
        </div>
    </a>
</div>

<!-- Statistics & Charts -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Quick Stats -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h4 class="font-semibold text-gray-800 mb-4">📈 Statistik Detail</h4>
        <div class="space-y-3">
            <div class="flex justify-between items-center">
                <span class="text-gray-600">Penerima Tahun Ini ({{ date('Y') }}):</span>
                <span class="font-semibold">{{ $total_penerima_tahun_ini }}</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-gray-600">Sudah Dimonev:</span>
                <span class="font-semibold text-green-600">{{ $sudah_dimonev }}</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-gray-600">Belum Dimonev:</span>
                <span class="font-semibold text-red-600">{{ $belum_dimonev }}</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-gray-600">Progress Monev:</span>
                <span class="font-semibold text-orange-600">{{ $persentase_monev }}%</span>
            </div>
        </div>
    </div>

    <!-- Filter Tahun -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h4 class="font-semibold text-gray-800 mb-4">📅 Filter Tahun</h4>
        <form method="GET" action="{{ route('dashboard') }}">
            <select name="tahun" onchange="this.form.submit()" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                <option value="">Semua Tahun</option>
                @foreach($tahun_list as $tahun_item)
                    <option value="{{ $tahun_item }}" {{ request('tahun') == $tahun_item ? 'selected' : '' }}>
                        {{ $tahun_item }}
                    </option>
                @endforeach
            </select>
        </form>
        
        <!-- Distribusi Tahun -->
        @if($distribusi_tahun->count() > 0)
        <div class="mt-4">
            <h5 class="font-medium text-gray-700 mb-2">📊 Distribusi per Tahun</h5>
            <div class="space-y-2">
                @foreach($distribusi_tahun as $item)
                <div class="flex justify-between items-center text-sm">
                    <span>{{ $item->tahun }}</span>
                    <span class="font-semibold">{{ $item->total }} penerima</span>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Distribusi Bidang (Admin Only) -->
@if(auth()->user()->isAdmin() && $distribusi_bidang->count() > 0)
<div class="mt-6 bg-white rounded-lg shadow-sm border border-gray-200 p-6">
    <h4 class="font-semibold text-gray-800 mb-4">🏢 Distribusi per Bidang</h4>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach($distribusi_bidang as $item)
        <div class="bg-gray-50 p-4 rounded-lg">
            <div class="flex justify-between items-center">
                <span class="font-medium text-gray-700">{{ $item->bidang }}</span>
                <span class="bg-blue-100 text-blue-800 text-sm font-medium px-2.5 py-0.5 rounded">
                    {{ $item->total }}
                </span>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

<!-- Role Specific Info -->
@if(auth()->user()->isAdmin())
<div class="mt-6 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
    <div class="flex items-center">
        <div class="text-yellow-600 mr-3">⚙️</div>
        <div>
            <h4 class="font-semibold text-yellow-800">Admin Tools</h4>
            <p class="text-yellow-700 text-sm">Anda memiliki akses penuh sebagai Administrator</p>
        </div>
    </div>
</div>
@else
<div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
    <div class="flex items-center">
        <div class="text-blue-600 mr-3">👤</div>
        <div>
            <h4 class="font-semibold text-blue-800">User {{ auth()->user()->bidang }}</h4>
            <p class="text-blue-700 text-sm">Anda hanya dapat mengakses data bidang {{ auth()->user()->bidang }}</p>
        </div>
    </div>
</div>
@endif
@endsection