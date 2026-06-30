@extends('layouts.app')

@section('title', 'Dashboard Admin - WILDANI')

@section('content')
<div class="flex min-h-screen">
    {{-- Sidebar --}}
    <x-sidebar :paymentTypes="$paymentTypes" activeSlug="admin" />

    {{-- Main Content --}}
    <main class="flex-1 min-w-0 lg:ml-72 gradient-bg min-h-screen">

        {{-- Header --}}
        <header class="pl-16 pr-6 pt-8 pb-4 lg:px-10">
            <div class="max-w-7xl mx-auto">
                <div class="flex flex-col gap-1 fade-in">
                    <div class="flex items-center gap-2 mb-1">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-700 tracking-wide">
                            Panel Admin
                        </span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-primary-100 text-primary-700 tracking-wide">
                            Tahun Ajaran {{ date('Y') }}/{{ date('Y') + 1 }}
                        </span>
                    </div>
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div>
                            <h1 class="text-2xl lg:text-3xl font-bold text-gray-900 tracking-tight">
                                Dashboard <span class="text-primary-600">Admin</span>
                            </h1>
                            <p class="text-gray-500 text-sm lg:text-base max-w-2xl">
                                Kelola layanan pembayaran publik, tambah data siswa, terbitkan tagihan baru, dan pantau status pembayaran.
                            </p>
                        </div>
                        <form method="POST" action="{{ route('logout') }}" class="shrink-0">
                            @csrf
                            <button type="submit" id="btn-logout" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl border border-red-200 bg-red-50 text-red-600 text-sm font-medium hover:bg-red-100 transition-all duration-200 cursor-pointer">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8.25 9V5.25A2.25 2.25 0 0 1 10.5 3h6a2.25 2.25 0 0 1 2.25 2.25v13.5A2.25 2.25 0 0 1 16.5 21h-6a2.25 2.25 0 0 1-2.25-2.25V15m-3 0-3-3m0 0 3-3m-3 3H15"/>
                                </svg>
                                Keluar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        {{-- Success/Error Message --}}
        <section class="px-6 py-2 lg:px-10">
            <div class="max-w-7xl mx-auto">
                @if (session('success'))
                    <div class="mb-4 flex items-start gap-3 p-4 rounded-xl bg-emerald-50 border border-emerald-100 fade-in">
                        <svg class="w-5 h-5 text-emerald-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                        </svg>
                        <p class="text-sm text-emerald-700 font-medium">{{ session('success') }}</p>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-4 flex items-start gap-3 p-4 rounded-xl bg-red-50 border border-red-100 fade-in">
                        <svg class="w-5 h-5 text-red-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z"/>
                        </svg>
                        <div>
                            @foreach ($errors->all() as $error)
                                <p class="text-sm text-red-700 font-medium">{{ $error }}</p>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </section>

        {{-- Main Dashboard Navigation Tabs --}}
        <section class="px-6 py-2 lg:px-10">
            <div class="max-w-7xl mx-auto">
                <div class="flex overflow-x-auto gap-2 border-b border-gray-200 pb-2 scrollbar-none whitespace-nowrap">
                    <button onclick="switchMainTab('tab-laporan-content', this)"
                        class="main-tab-btn shrink-0 flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 bg-white shadow-sm border border-gray-200 text-primary-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z"/>
                        </svg>
                        Laporan Pembayaran
                    </button>
                    <button onclick="switchMainTab('tab-layanan-content', this)"
                        class="main-tab-btn shrink-0 flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 text-gray-500 hover:text-gray-700 hover:bg-white/60">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v12m-9-3h18m-18-6h18m-18-6h18M3 9a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V9Z"/>
                        </svg>
                        Kelola Layanan (Dashboard Publik)
                    </button>
                    <button onclick="switchMainTab('tab-siswa-content', this)"
                        class="main-tab-btn shrink-0 flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 text-gray-500 hover:text-gray-700 hover:bg-white/60">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z"/>
                        </svg>
                        Kelola Siswa
                    </button>
                    <button onclick="switchMainTab('tab-tagihan-content', this)"
                        class="main-tab-btn shrink-0 flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 text-gray-500 hover:text-gray-700 hover:bg-white/60">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                        </svg>
                        Terbitkan Tagihan
                    </button>
                </div>
            </div>
        </section>

        {{-- ================================= TAB 1: LAPORAN PEMBAYARAN ================================= --}}
        <div id="tab-laporan-content" class="main-tab-content">
            {{-- Summary Stats --}}
            <section class="px-6 py-4 lg:px-10">
                <div class="max-w-7xl mx-auto">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div class="glass-card p-4 fade-in stagger-1">
                            <div class="flex items-center gap-3">
                                <div class="flex items-center justify-center w-10 h-10 rounded-xl bg-secondary-100/80 shrink-0">
                                    <svg class="w-5 h-5 text-secondary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                    </svg>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-xs text-gray-400 font-medium uppercase tracking-wider truncate">Sudah Lunas</p>
                                    <p class="text-xl font-bold text-gray-900">{{ $paidBills }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="glass-card p-4 fade-in stagger-2">
                            <div class="flex items-center gap-3">
                                <div class="flex items-center justify-center w-10 h-10 rounded-xl bg-accent-100/80 shrink-0">
                                    <svg class="w-5 h-5 text-accent-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                    </svg>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-xs text-gray-400 font-medium uppercase tracking-wider truncate">Belum Lunas</p>
                                    <p class="text-xl font-bold text-gray-900">{{ $totalBills - $paidBills }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="glass-card p-4 fade-in stagger-3">
                            <div class="flex items-center gap-3">
                                <div class="flex items-center justify-center w-10 h-10 rounded-xl bg-primary-100/80 shrink-0">
                                    <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                    </svg>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-xs text-gray-400 font-medium uppercase tracking-wider truncate">Total Terkumpul</p>
                                    <p class="text-base font-bold text-gray-900">Rp {{ number_format($totalPaid, 0, ',', '.') }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="glass-card p-4 fade-in stagger-4">
                            <div class="flex items-center gap-3">
                                <div class="flex items-center justify-center w-10 h-10 rounded-xl bg-red-100/80 shrink-0">
                                    <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z"/>
                                    </svg>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-xs text-gray-400 font-medium uppercase tracking-wider truncate">Tagihan Pending</p>
                                    <p class="text-base font-bold text-gray-900">Rp {{ number_format($totalPending, 0, ',', '.') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {{-- Filter Bar --}}
            <section class="px-6 pb-4 lg:px-10">
                <div class="max-w-7xl mx-auto">
                    <div class="glass-card p-4 fade-in">
                        <form id="filter-form" method="GET" action="{{ route('admin.payment-report') }}" class="flex flex-wrap gap-3 items-end">
                            <div class="flex-1 min-w-48">
                                <label for="search" class="block text-xs font-semibold text-gray-600 mb-1.5 uppercase tracking-wide">Cari Siswa</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/>
                                        </svg>
                                    </div>
                                    <input
                                        type="text"
                                        id="search"
                                        name="search"
                                        value="{{ $search }}"
                                        placeholder="Nama atau NISN..."
                                        class="w-full pl-9 pr-4 py-2 rounded-lg border border-gray-200 bg-white/70 text-gray-800 text-sm placeholder-gray-400 focus:outline-none focus:border-primary-400 focus:ring-2 focus:ring-primary-100 transition-all duration-200"
                                    >
                                </div>
                            </div>

                            <div class="min-w-36">
                                <label for="kelas" class="block text-xs font-semibold text-gray-600 mb-1.5 uppercase tracking-wide">Kelas</label>
                                <select id="kelas" name="kelas" class="w-full px-3 py-2 rounded-lg border border-gray-200 bg-white/70 text-gray-800 text-sm focus:outline-none focus:border-primary-400 focus:ring-2 focus:ring-primary-100 transition-all duration-200">
                                    <option value="">Semua Kelas</option>
                                    @foreach($kelasList as $kelas)
                                        <option value="{{ $kelas }}" {{ $filterKelas === $kelas ? 'selected' : '' }}>{{ $kelas }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="min-w-44">
                                <label for="payment_type_id" class="block text-xs font-semibold text-gray-600 mb-1.5 uppercase tracking-wide">Jenis Pembayaran</label>
                                <select id="payment_type_id" name="payment_type_id" class="w-full px-3 py-2 rounded-lg border border-gray-200 bg-white/70 text-gray-800 text-sm focus:outline-none focus:border-primary-400 focus:ring-2 focus:ring-primary-100 transition-all duration-200">
                                    <option value="">Semua Jenis</option>
                                    @foreach($paymentTypes as $type)
                                        <option value="{{ $type->id }}" {{ $filterPaymentType == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <button type="submit" class="px-5 py-2 rounded-lg bg-primary-600 text-white text-sm font-semibold hover:bg-primary-700 transition-colors duration-200 cursor-pointer">
                                Filter
                            </button>

                            @if($search || $filterKelas || $filterPaymentType)
                                <a href="{{ route('admin.payment-report') }}" class="px-4 py-2 rounded-lg border border-gray-200 text-gray-600 text-sm font-medium hover:bg-gray-50 transition-colors duration-200">
                                    Reset
                                </a>
                            @endif
                        </form>
                    </div>
                </div>
            </section>

            {{-- Table Nav Tab (Paid / Unpaid) --}}
            <section class="px-6 pb-2 lg:px-10">
                <div class="max-w-7xl mx-auto">
                    <div class="flex gap-2">
                        <button id="tab-unpaid" onclick="switchReportTab('unpaid')"
                            class="report-tab-btn flex items-center gap-2 px-5 py-2 rounded-xl text-sm font-semibold transition-all duration-200 bg-white shadow-sm border border-gray-200 text-gray-700 cursor-pointer">
                            <span class="w-2 h-2 rounded-full bg-amber-400 inline-block"></span>
                            Belum Bayar
                            <span class="ml-1 inline-flex items-center justify-center w-5 h-5 rounded-full bg-amber-100 text-amber-700 text-xs font-bold">
                                {{ $unpaidStudents->count() }}
                            </span>
                        </button>
                        <button id="tab-paid" onclick="switchReportTab('paid')"
                            class="report-tab-btn flex items-center gap-2 px-5 py-2 rounded-xl text-sm font-semibold transition-all duration-200 text-gray-500 hover:text-gray-700 hover:bg-white/60 cursor-pointer">
                            <span class="w-2 h-2 rounded-full bg-emerald-400 inline-block"></span>
                            Sudah Bayar
                            <span class="ml-1 inline-flex items-center justify-center w-5 h-5 rounded-full bg-emerald-100 text-emerald-700 text-xs font-bold">
                                {{ $paidStudents->count() }}
                            </span>
                        </button>
                    </div>
                </div>
            </section>

            {{-- Tables --}}
            <section class="px-6 pb-12 lg:px-10">
                <div class="max-w-7xl mx-auto">
                    {{-- Belum Bayar Table --}}
                    <div id="table-unpaid" class="fade-in">
                        <div class="glass-card overflow-hidden">
                            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-3 h-3 rounded-full bg-amber-400"></div>
                                    <h2 class="font-semibold text-gray-800">Siswa Belum Bayar</h2>
                                </div>
                                <span class="text-sm text-gray-400">{{ $unpaidStudents->count() }} siswa</span>
                            </div>

                            @if($unpaidStudents->isEmpty())
                                <div class="flex flex-col items-center justify-center py-16 text-gray-400">
                                    <svg class="w-12 h-12 mb-3 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                    </svg>
                                    <p class="text-sm font-medium">Semua siswa sudah membayar! 🎉</p>
                                </div>
                            @else
                                <div class="overflow-x-auto">
                                    <table class="w-full text-sm">
                                        <thead>
                                            <tr class="bg-gray-50/80">
                                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">#</th>
                                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Siswa</th>
                                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">NISN</th>
                                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Kelas</th>
                                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tagihan</th>
                                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Total</th>
                                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">WhatsApp</th>
                                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-100">
                                            @foreach($unpaidStudents as $index => $student)
                                                @php
                                                    $pendingBills = $student->bills->where('status', '!=', 'paid');
                                                    $totalAmount = $pendingBills->sum('amount');
                                                @endphp
                                                <tr class="hover:bg-gray-50/60 transition-colors duration-150">
                                                    <td class="px-6 py-4 text-gray-400 font-mono text-xs">{{ $index + 1 }}</td>
                                                    <td class="px-6 py-4">
                                                        <div class="flex items-center gap-3">
                                                            <div class="w-8 h-8 rounded-full bg-amber-100 flex items-center justify-center text-amber-700 font-bold text-sm shrink-0">
                                                                {{ strtoupper(substr($student->nama, 0, 1)) }}
                                                            </div>
                                                            <p class="font-semibold text-gray-800">{{ $student->nama }}</p>
                                                        </div>
                                                    </td>
                                                    <td class="px-6 py-4 text-gray-600 font-mono text-xs">{{ $student->nisn }}</td>
                                                    <td class="px-6 py-4">
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-lg text-xs font-medium bg-gray-100 text-gray-700">
                                                            {{ $student->kelas }}
                                                        </span>
                                                    </td>
                                                    <td class="px-6 py-4">
                                                        <div class="flex flex-wrap gap-1">
                                                            @forelse($pendingBills as $bill)
                                                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium
                                                                    @if($bill->status === 'overdue') bg-red-100 text-red-700
                                                                    @else bg-amber-100 text-amber-700
                                                                    @endif">
                                                                    {{ $bill->paymentType->name ?? '-' }}
                                                                </span>
                                                            @empty
                                                                <span class="text-gray-400 text-xs">Belum ada tagihan</span>
                                                            @endforelse
                                                        </div>
                                                    </td>
                                                    <td class="px-6 py-4 font-semibold text-gray-800">
                                                        @if($totalAmount > 0)
                                                            Rp {{ number_format($totalAmount, 0, ',', '.') }}
                                                        @else
                                                            <span class="text-gray-400">-</span>
                                                        @endif
                                                    </td>
                                                    <td class="px-6 py-4 text-xs text-gray-500">
                                                        @if($student->parent_phone)
                                                            <a href="https://wa.me/{{ preg_replace('/^0/', '62', $student->parent_phone) }}"
                                                               target="_blank"
                                                               class="inline-flex items-center gap-1 text-emerald-600 hover:text-emerald-700 font-medium hover:underline">
                                                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                                                                {{ $student->parent_phone }}
                                                            </a>
                                                        @else
                                                            <span class="text-gray-300">-</span>
                                                        @endif
                                                    </td>
                                                    <td class="px-6 py-4">
                                                        @if($pendingBills->where('status', 'overdue')->isNotEmpty())
                                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">
                                                                <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                                                                Terlambat
                                                            </span>
                                                        @elseif($pendingBills->isNotEmpty())
                                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-700">
                                                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                                                Pending
                                                            </span>
                                                        @else
                                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-600">
                                                                <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span>
                                                                Belum Ada Tagihan
                                                            </span>
                                                        @endif
                                                    </td>
                                                    <td class="px-6 py-4">
                                                        <div class="flex flex-col gap-1">
                                                            @forelse($pendingBills as $bill)
                                                                <form method="POST" action="{{ route('admin.bills.mark-paid', $bill) }}">
                                                                    @csrf
                                                                    @method('PATCH')
                                                                    <button type="submit" id="btn-mark-paid-{{ $bill->id }}"
                                                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200 hover:bg-emerald-100 transition-all duration-200 cursor-pointer whitespace-nowrap"
                                                                        onclick="return confirm('Tandai {{ $bill->paymentType->name ?? 'tagihan' }} sebagai lunas?')">
                                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                                                                        Lunas
                                                                    </button>
                                                                </form>
                                                            @empty
                                                                <span class="text-gray-300 text-xs">-</span>
                                                            @endforelse
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>

                                    </table>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Sudah Bayar Table --}}
                    <div id="table-paid" class="fade-in hidden">
                        <div class="glass-card overflow-hidden">
                            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-3 h-3 rounded-full bg-emerald-400"></div>
                                    <h2 class="font-semibold text-gray-800">Siswa Sudah Bayar</h2>
                                </div>
                                <span class="text-sm text-gray-400">{{ $paidStudents->count() }} siswa</span>
                            </div>

                            @if($paidStudents->isEmpty())
                                <div class="flex flex-col items-center justify-center py-16 text-gray-400">
                                    <svg class="w-12 h-12 mb-3 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Z"/>
                                    </svg>
                                    <p class="text-sm font-medium">Belum ada pembayaran yang tercatat.</p>
                                </div>
                            @else
                                <div class="overflow-x-auto">
                                    <table class="w-full text-sm">
                                        <thead>
                                            <tr class="bg-gray-50/80">
                                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">#</th>
                                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Siswa</th>
                                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">NISN</th>
                                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Kelas</th>
                                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Pembayaran</th>
                                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Dibayar</th>
                                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tanggal Bayar</th>
                                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-100">
                                            @foreach($paidStudents as $index => $student)
                                                @php
                                                    $paidBillsData = $student->bills->where('status', 'paid');
                                                    $totalPaidAmount = $paidBillsData->sum('amount');
                                                    $latestPayment = $paidBillsData->sortByDesc('paid_at')->first();
                                                @endphp
                                                <tr class="hover:bg-gray-50/60 transition-colors duration-150">
                                                    <td class="px-6 py-4 text-gray-400 font-mono text-xs">{{ $index + 1 }}</td>
                                                    <td class="px-6 py-4">
                                                        <div class="flex items-center gap-3">
                                                            <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-700 font-bold text-sm shrink-0">
                                                                {{ strtoupper(substr($student->nama, 0, 1)) }}
                                                            </div>
                                                            <div>
                                                                <p class="font-semibold text-gray-800">{{ $student->nama }}</p>
                                                                @if($student->parent_phone)
                                                                    <p class="text-xs text-gray-400">{{ $student->parent_phone }}</p>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="px-6 py-4 text-gray-600 font-mono text-xs">{{ $student->nisn }}</td>
                                                    <td class="px-6 py-4">
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-lg text-xs font-medium bg-gray-100 text-gray-700">
                                                            {{ $student->kelas }}
                                                        </span>
                                                    </td>
                                                    <td class="px-6 py-4">
                                                        <div class="flex flex-wrap gap-1">
                                                            @foreach($paidBillsData as $bill)
                                                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium bg-emerald-100 text-emerald-700">
                                                                    {{ $bill->paymentType->name ?? '-' }}
                                                                </span>
                                                            @endforeach
                                                        </div>
                                                    </td>
                                                    <td class="px-6 py-4 font-semibold text-emerald-700">
                                                        Rp {{ number_format($totalPaidAmount, 0, ',', '.') }}
                                                    </td>
                                                    <td class="px-6 py-4 text-gray-600 text-xs">
                                                        {{ $latestPayment?->paid_at?->format('d M Y') ?? '-' }}
                                                    </td>
                                                    <td class="px-6 py-4">
                                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700">
                                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                            Lunas
                                                        </span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </section>
        </div>

        {{-- ================================= TAB 2: KELOLA LAYANAN ================================= --}}
        <div id="tab-layanan-content" class="main-tab-content hidden">
            <section class="px-6 py-4 lg:px-10">
                <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-3 gap-6">

                    {{-- Form: Tambah Layanan --}}
                    <div class="glass-card p-6">
                        <h2 class="text-lg font-bold text-gray-800 mb-4">Tambah Layanan Baru</h2>
                        <form method="POST" action="{{ route('admin.payment-types.store') }}" class="space-y-4">
                            @csrf
                            <div>
                                <label for="name" class="block text-xs font-bold text-gray-600 uppercase mb-1">Nama Layanan</label>
                                <input type="text" id="name" name="name" required placeholder="Contoh: SPP Juli, Uang Buku"
                                    class="w-full px-3.5 py-2 border border-gray-200 rounded-lg text-sm bg-white/50 focus:outline-none focus:border-primary-500 focus:ring-1 focus:ring-primary-500 text-gray-800">
                            </div>
                            <div>
                                <label for="description" class="block text-xs font-bold text-gray-600 uppercase mb-1">Deskripsi</label>
                                <textarea id="description" name="description" required rows="3" placeholder="Deskripsi singkat mengenai layanan pembayaran ini..."
                                    class="w-full px-3.5 py-2 border border-gray-200 rounded-lg text-sm bg-white/50 focus:outline-none focus:border-primary-500 focus:ring-1 focus:ring-primary-500 text-gray-800"></textarea>
                            </div>
                            <div>
                                <label for="amount" class="block text-xs font-bold text-gray-600 uppercase mb-1">Nominal (Rp)</label>
                                <input type="number" id="amount" name="amount" required min="0" placeholder="Nominal biaya, misal: 250000"
                                    class="w-full px-3.5 py-2 border border-gray-200 rounded-lg text-sm bg-white/50 focus:outline-none focus:border-primary-500 focus:ring-1 focus:ring-primary-500 text-gray-800">
                            </div>
                            <div>
                                <label for="icon" class="block text-xs font-bold text-gray-600 uppercase mb-1">Pilih Ikon</label>
                                <select id="icon" name="icon" required class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm bg-white/50 text-gray-800">
                                    <option value="calendar">Kalender (Pembayaran Rutin)</option>
                                    <option value="building">Gedung (Fasilitas/Gedung)</option>
                                    <option value="shirt">Kaos (Seragam/Pakaian)</option>
                                    <option value="sparkles">Bintang (Uang Kegiatan/Wisuda)</option>
                                    <option value="book">Buku (Pelajaran/LKS)</option>
                                    <option value="graduation-cap">Topi Toga (Pendaftaran/Kelulusan)</option>
                                </select>
                            </div>
                            <button type="submit" class="w-full py-2.5 px-4 bg-primary-600 hover:bg-primary-700 text-white rounded-lg text-sm font-semibold transition-all duration-200 shadow-md hover:shadow-lg cursor-pointer">
                                Tayangkan di Dashboard Publik
                            </button>
                        </form>
                    </div>

                    {{-- List Layanan Aktif --}}
                    <div class="lg:col-span-2 glass-card p-6">
                        <h2 class="text-lg font-bold text-gray-800 mb-4">Daftar Layanan di Dashboard Publik</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($paymentTypes as $type)
                                <div class="border border-gray-100 rounded-xl p-4 bg-white/40 flex items-start gap-4">
                                    <div class="w-10 h-10 rounded-xl bg-primary-50 flex items-center justify-center text-primary-600 shrink-0">
                                        @switch($type->icon)
                                            @case('calendar')
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5"/></svg>
                                                @break
                                            @case('building')
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.25 21h19.5M3.75 3v18h6V12h4.5v9h6V3H3.75zm3 3h1.5v1.5h-1.5V6zm0 3h1.5v1.5h-1.5V9zm0 3h1.5v1.5h-1.5v-1.5zm4.5-6h1.5v1.5h-1.5V6zm0 3h1.5v1.5h-1.5V9zm4.5-3h1.5v1.5h-1.5V6zm0 3h1.5v1.5h-1.5V9z"/></svg>
                                                @break
                                            @case('shirt')
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z"/></svg>
                                                @break
                                            @case('sparkles')
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.813 15.904 9 18.75l-.813-2.846a4.5 4.5 0 0 0-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 0 0 3.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 0 0 3.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 0 0-3.09 3.09ZM18.259 8.715 18 9.75l-.259-1.035a3.375 3.375 0 0 0-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 0 0 2.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 0 0 2.455 2.456L21.75 6l-1.036.259a3.375 3.375 0 0 0-2.455 2.456ZM16.894 20.567 16.5 21.75l-.394-1.183a2.25 2.25 0 0 0-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 0 0 1.423-1.423l.394-1.183.394 1.183a2.25 2.25 0 0 0 1.423 1.423l1.183.394-1.183.394a2.25 2.25 0 0 0-1.423 1.423Z"/></svg>
                                                @break
                                            @case('book')
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25"/></svg>
                                                @break
                                            @case('graduation-cap')
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5"/></svg>
                                                @break
                                        @endswitch
                                    </div>
                                    <div>
                                        <h3 class="font-bold text-gray-800 text-sm">{{ $type->name }}</h3>
                                        <p class="text-xs text-gray-400 line-clamp-1 mb-2">{{ $type->description }}</p>
                                        <span class="text-sm font-bold text-primary-600">Rp {{ number_format($type->amount, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                </div>
            </section>
        </div>

        {{-- ================================= TAB 3: KELOLA SISWA ================================= --}}
        <div id="tab-siswa-content" class="main-tab-content hidden">
            <section class="px-6 py-4 lg:px-10">
                <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-3 gap-6">

                    {{-- Form: Tambah Siswa --}}
                    <div class="glass-card p-6">
                        <h2 class="text-lg font-bold text-gray-800 mb-4">Registrasi Siswa Baru</h2>
                        <form method="POST" action="{{ route('admin.students.store') }}" class="space-y-4">
                            @csrf
                            <div>
                                <label for="nisn_input" class="block text-xs font-bold text-gray-600 uppercase mb-1">NISN</label>
                                <input type="text" id="nisn_input" name="nisn" required placeholder="10 Digit NISN..." maxlength="10"
                                    class="w-full px-3.5 py-2 border border-gray-200 rounded-lg text-sm bg-white/50 focus:outline-none focus:border-primary-500 focus:ring-1 focus:ring-primary-500 text-gray-800">
                            </div>
                            <div>
                                <label for="nama_input" class="block text-xs font-bold text-gray-600 uppercase mb-1">Nama Lengkap</label>
                                <input type="text" id="nama_input" name="nama" required placeholder="Nama lengkap siswa..."
                                    class="w-full px-3.5 py-2 border border-gray-200 rounded-lg text-sm bg-white/50 focus:outline-none focus:border-primary-500 focus:ring-1 focus:ring-primary-500 text-gray-800">
                            </div>
                            <div>
                                <label for="kelas_input" class="block text-xs font-bold text-gray-600 uppercase mb-1">Kelas</label>
                                <select id="kelas_input" name="kelas" required class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm bg-white/50 text-gray-800">
                                    <option value="TK A">TK A</option>
                                    <option value="TK B">TK B</option>
                                    <option value="PAUD">PAUD</option>
                                </select>
                            </div>
                            <div>
                                <label for="parent_phone_input" class="block text-xs font-bold text-gray-600 uppercase mb-1">No WhatsApp Orang Tua</label>
                                <input type="text" id="parent_phone_input" name="parent_phone" required placeholder="Format: 0812xxxx..."
                                    class="w-full px-3.5 py-2 border border-gray-200 rounded-lg text-sm bg-white/50 focus:outline-none focus:border-primary-500 focus:ring-1 focus:ring-primary-500 text-gray-800">
                            </div>
                            <button type="submit" class="w-full py-2.5 px-4 bg-primary-600 hover:bg-primary-700 text-white rounded-lg text-sm font-semibold transition-all duration-200 shadow-md hover:shadow-lg cursor-pointer">
                                Daftarkan Siswa & Buat Akun
                            </button>
                        </form>
                    </div>

                    {{-- List Siswa --}}
                    <div class="lg:col-span-2 glass-card p-6 overflow-hidden">
                        <h2 class="text-lg font-bold text-gray-800 mb-4">Daftar Siswa Terdaftar</h2>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="bg-gray-50/80">
                                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Siswa</th>
                                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">NISN / ID Login</th>
                                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Kelas</th>
                                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Wali Murid (WA)</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @foreach($allStudents as $student)
                                        <tr class="hover:bg-gray-50/40 transition-colors duration-150">
                                            <td class="px-4 py-3">
                                                <div class="flex items-center gap-2">
                                                    <div class="w-7 h-7 rounded-full bg-primary-100 flex items-center justify-center text-primary-700 font-bold text-xs">
                                                        {{ strtoupper(substr($student->nama, 0, 1)) }}
                                                    </div>
                                                    <span class="font-medium text-gray-800">{{ $student->nama }}</span>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3 text-xs font-mono text-gray-600">{{ $student->nisn }}</td>
                                            <td class="px-4 py-3">
                                                <span class="px-2 py-0.5 rounded bg-gray-100 text-gray-700 text-xs font-medium">{{ $student->kelas }}</span>
                                            </td>
                                            <td class="px-4 py-3 text-xs text-gray-500">{{ $student->parent_phone }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </section>
        </div>

        {{-- ================================= TAB 4: KELOLA TAGIHAN ================================= --}}
        <div id="tab-tagihan-content" class="main-tab-content hidden">
            <section class="px-6 py-4 lg:px-10">
                <div class="max-w-4xl mx-auto">
                    <div class="glass-card p-6">
                        <h2 class="text-lg font-bold text-gray-800 mb-4 text-center">Terbitkan Tagihan Baru</h2>
                        <form method="POST" action="{{ route('admin.bills.store') }}" class="space-y-5 max-w-xl mx-auto">
                            @csrf
                            <div>
                                <label for="student_id_select" class="block text-xs font-bold text-gray-600 uppercase mb-1">Pilih Siswa</label>
                                <select id="student_id_select" name="student_id" required class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm bg-white/50 text-gray-800">
                                    <option value="" disabled selected>-- Pilih Siswa --</option>
                                    @foreach($allStudents as $student)
                                        <option value="{{ $student->id }}">{{ $student->nama }} ({{ $student->kelas }} - NISN: {{ $student->nisn }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="payment_type_id_form" class="block text-xs font-bold text-gray-600 uppercase mb-1">Pilih Jenis Pembayaran</label>
                                <select id="payment_type_id_form" name="payment_type_id" required class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm bg-white/50 text-gray-800">
                                    <option value="" disabled selected>-- Pilih Pembayaran --</option>
                                    @foreach($paymentTypes as $type)
                                        <option value="{{ $type->id }}">{{ $type->name }} (Rp {{ number_format($type->amount, 0, ',', '.') }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="due_date" class="block text-xs font-bold text-gray-600 uppercase mb-1">Tanggal Jatuh Tempo</label>
                                <input type="date" id="due_date" name="due_date" required
                                    class="w-full px-3.5 py-2 border border-gray-200 rounded-lg text-sm bg-white/50 focus:outline-none focus:border-primary-500 focus:ring-1 focus:ring-primary-500 text-gray-800">
                            </div>
                            <button type="submit" class="w-full py-3 px-4 bg-primary-600 hover:bg-primary-700 text-white rounded-lg text-sm font-semibold transition-all duration-200 shadow-md hover:shadow-lg cursor-pointer">
                                Terbitkan Tagihan Resmi
                            </button>
                        </form>
                    </div>
                </div>
            </section>
        </div>

    </main>
</div>

<script>
// Main Dashboard Tab Switching
function switchMainTab(contentId, button) {
    // Hide all main tab contents
    document.querySelectorAll('.main-tab-content').forEach(function(content) {
        content.classList.add('hidden');
    });

    // Remove active styles from all buttons
    document.querySelectorAll('.main-tab-btn').forEach(function(btn) {
        btn.classList.remove('bg-white', 'shadow-sm', 'border', 'border-gray-200', 'text-primary-600');
        btn.classList.add('text-gray-500', 'hover:text-gray-700', 'hover:bg-white/60');
    });

    // Show selected content
    document.getElementById(contentId).classList.remove('hidden');

    // Add active styles to clicked button
    button.classList.add('bg-white', 'shadow-sm', 'border', 'border-gray-200', 'text-primary-600');
    button.classList.remove('text-gray-500', 'hover:text-gray-700', 'hover:bg-white/60');
}

// Sub Tab Switching (Paid/Unpaid inside Laporan)
function switchReportTab(tab) {
    const unpaidTable = document.getElementById('table-unpaid');
    const paidTable = document.getElementById('table-paid');
    const unpaidBtn = document.getElementById('tab-unpaid');
    const paidBtn = document.getElementById('tab-paid');

    if (tab === 'unpaid') {
        unpaidTable.classList.remove('hidden');
        paidTable.classList.add('hidden');
        unpaidBtn.classList.add('bg-white', 'shadow-sm', 'border', 'border-gray-200', 'text-gray-700');
        unpaidBtn.classList.remove('text-gray-500', 'hover:text-gray-700', 'hover:bg-white/60');
        paidBtn.classList.remove('bg-white', 'shadow-sm', 'border', 'border-gray-200', 'text-gray-700');
        paidBtn.classList.add('text-gray-500', 'hover:text-gray-700', 'hover:bg-white/60');
    } else {
        paidTable.classList.remove('hidden');
        unpaidTable.classList.add('hidden');
        paidBtn.classList.add('bg-white', 'shadow-sm', 'border', 'border-gray-200', 'text-gray-700');
        paidBtn.classList.remove('text-gray-500', 'hover:text-gray-700', 'hover:bg-white/60');
        unpaidBtn.classList.remove('bg-white', 'shadow-sm', 'border', 'border-gray-200', 'text-gray-700');
        unpaidBtn.classList.add('text-gray-500', 'hover:text-gray-700', 'hover:bg-white/60');
    }
}
</script>
@endsection
