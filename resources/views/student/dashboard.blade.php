@extends('layouts.app')

@section('title', 'Dashboard Siswa - WILDANI')

@section('content')
<div class="min-h-screen gradient-bg">

    {{-- Header Bar --}}
    <header class="sticky top-0 z-40 bg-white/80 backdrop-blur-xl border-b border-white/40 shadow-sm">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between gap-4">
            {{-- Brand --}}
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-primary-600 flex items-center justify-center shadow-md shadow-primary-600/30">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-semibold text-primary-600 uppercase tracking-widest leading-none">WILDANI</p>
                    <p class="text-xs text-gray-400 leading-none mt-0.5">Portal Siswa</p>
                </div>
            </div>

            {{-- User info + logout --}}
            <div class="flex items-center gap-3">
                <div class="hidden sm:flex items-center gap-2 px-3 py-1.5 rounded-xl bg-primary-50 border border-primary-100">
                    <div class="w-6 h-6 rounded-full bg-primary-200 flex items-center justify-center text-primary-700 font-bold text-xs">
                        {{ strtoupper(substr($student->nama, 0, 1)) }}
                    </div>
                    <span class="text-sm font-medium text-primary-700 max-w-32 truncate">{{ $student->nama }}</span>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" id="btn-student-logout"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl border border-red-200 bg-red-50 text-red-600 text-xs font-semibold hover:bg-red-100 transition-all duration-200 cursor-pointer">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8.25 9V5.25A2.25 2.25 0 0 1 10.5 3h6a2.25 2.25 0 0 1 2.25 2.25v13.5A2.25 2.25 0 0 1 16.5 21h-6a2.25 2.25 0 0 1-2.25-2.25V15m-3 0-3-3m0 0 3-3m-3 3H15"/>
                        </svg>
                        Keluar
                    </button>
                </form>
            </div>
        </div>
    </header>

    <main class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="flex items-start gap-3 p-4 rounded-xl bg-emerald-50 border border-emerald-100 fade-in">
                <svg class="w-5 h-5 text-emerald-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                </svg>
                <p class="text-sm text-emerald-700 font-medium">{{ session('success') }}</p>
            </div>
        @endif

        @if(session('error'))
            <div class="flex items-start gap-3 p-4 rounded-xl bg-red-50 border border-red-100 fade-in">
                <svg class="w-5 h-5 text-red-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z"/>
                </svg>
                <p class="text-sm text-red-700 font-medium">{{ session('error') }}</p>
            </div>
        @endif

        {{-- Profile + Stats --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">

            {{-- Student Profile Card --}}
            <div class="glass-card p-6 fade-in stagger-1 md:col-span-1">
                <div class="flex flex-col items-center text-center gap-3">
                    <div class="w-20 h-20 rounded-2xl bg-gradient-to-br from-primary-400 to-primary-600 flex items-center justify-center text-white font-bold text-3xl shadow-lg shadow-primary-500/30">
                        {{ strtoupper(substr($student->nama, 0, 1)) }}
                    </div>
                    <div>
                        <h1 class="text-lg font-bold text-gray-900 leading-tight">{{ $student->nama }}</h1>
                        <p class="text-sm text-gray-500 mt-0.5">{{ $student->kelas }}</p>
                    </div>
                    <div class="w-full pt-2 border-t border-gray-100 space-y-2">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-400 font-medium">NISN</span>
                            <span class="font-mono font-semibold text-gray-700 text-xs bg-gray-100 px-2 py-0.5 rounded-lg">{{ $student->nisn }}</span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-400 font-medium">Kelas</span>
                            <span class="font-semibold text-gray-700 text-xs bg-primary-50 text-primary-700 px-2 py-0.5 rounded-lg">{{ $student->kelas }}</span>
                        </div>
                        @if($student->parent_phone)
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-400 font-medium">WhatsApp</span>
                                <a href="https://wa.me/{{ preg_replace('/^0/', '62', $student->parent_phone) }}"
                                   target="_blank"
                                   class="text-xs text-emerald-600 font-medium hover:underline">
                                    {{ $student->parent_phone }}
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Summary Stats --}}
            <div class="md:col-span-2 grid grid-cols-2 gap-4">
                <div class="glass-card p-5 fade-in stagger-2">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-xl bg-amber-100/80 flex items-center justify-center shrink-0">
                            <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 font-medium uppercase tracking-wider">Tagihan Pending</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $pendingBills->whereIn('status', ['pending', 'overdue', 'rejected'])->count() }}</p>
                        </div>
                    </div>
                </div>
                <div class="glass-card p-5 fade-in stagger-3">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-xl bg-red-100/80 flex items-center justify-center shrink-0">
                            <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 font-medium uppercase tracking-wider">Total Tagihan</p>
                            <p class="text-xl font-bold text-gray-900">Rp {{ number_format($totalTagihan, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>
                <div class="glass-card p-5 fade-in stagger-4">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-xl bg-emerald-100/80 flex items-center justify-center shrink-0">
                            <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 font-medium uppercase tracking-wider">Sudah Lunas</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $paidBills->count() }}</p>
                        </div>
                    </div>
                </div>
                <div class="glass-card p-5 fade-in stagger-4">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-xl bg-primary-100/80 flex items-center justify-center shrink-0">
                            <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 font-medium uppercase tracking-wider">Total Terbayar</p>
                            <p class="text-xl font-bold text-primary-700">Rp {{ number_format($totalTerbayar, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Pending Bills --}}
        <div class="glass-card overflow-hidden fade-in">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-3">
                <div class="w-3 h-3 rounded-full bg-amber-400 animate-pulse"></div>
                <h2 class="font-bold text-gray-800">Tagihan Belum Lunas</h2>
                <span class="ml-auto inline-flex items-center justify-center w-6 h-6 rounded-full bg-amber-100 text-amber-700 text-xs font-bold">
                    {{ $pendingBills->count() }}
                </span>
            </div>

            @if($pendingBills->isEmpty())
                <div class="flex flex-col items-center justify-center py-14 text-gray-400">
                    <svg class="w-14 h-14 mb-3 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                    </svg>
                    <p class="font-semibold text-sm">Semua tagihan sudah lunas! 🎉</p>
                    <p class="text-xs mt-1">Tidak ada tagihan yang perlu dibayar saat ini.</p>
                </div>
            @else
                <div class="divide-y divide-gray-50">
                    @foreach($pendingBills as $bill)
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 px-6 py-5 hover:bg-gray-50/60 transition-colors duration-150">
                            {{-- Details & Icon --}}
                            <div class="flex items-center gap-4 min-w-0">
                                <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center shrink-0">
                                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Z"/>
                                    </svg>
                                </div>
                                <div class="min-w-0">
                                    <p class="font-semibold text-gray-800 text-sm truncate">
                                        {{ $bill->paymentType->name ?? 'Tagihan' }}
                                    </p>
                                    @if($bill->due_date)
                                        <p class="text-xs text-gray-400 mt-0.5">
                                            Jatuh tempo: {{ $bill->due_date->format('d M Y') }}
                                            @if($bill->due_date->isPast())
                                                <span class="text-red-500 font-semibold">(Terlambat!)</span>
                                            @elseif($bill->due_date->diffInDays(now()) <= 7)
                                                <span class="text-amber-500 font-semibold">(Segera)</span>
                                            @endif
                                        </p>
                                    @endif
                                    @if($bill->status === 'rejected')
                                        <p class="text-xs text-red-650 font-medium mt-1">
                                            ⚠️ Penolakan Admin: <span class="italic">"{{ $bill->rejected_reason }}"</span>
                                        </p>
                                    @endif
                                </div>
                            </div>

                            {{-- Price & Actions --}}
                            <div class="flex items-center justify-between sm:justify-end gap-6 shrink-0">
                                <div class="text-left sm:text-right">
                                    <p class="font-bold text-gray-900 text-sm">
                                        Rp {{ number_format($bill->amount, 0, ',', '.') }}
                                    </p>
                                    @if($bill->status === 'overdue')
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-700 mt-1">
                                            <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>Terlambat
                                        </span>
                                    @elseif($bill->status === 'waiting_verification')
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-700 mt-1">
                                            <span class="w-1.5 h-1.5 rounded-full bg-blue-500 animate-pulse"></span>Menunggu Verifikasi
                                        </span>
                                    @elseif($bill->status === 'rejected')
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-red-50 text-red-650 mt-1">
                                            <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>Ditolak
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-amber-100 text-amber-700 mt-1">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>Pending
                                        </span>
                                    @endif
                                </div>

                                <div>
                                    @if($bill->status === 'waiting_verification')
                                        <a href="{{ asset('storage/' . $bill->payment_proof) }}" target="_blank"
                                            class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl border border-blue-200 bg-blue-50 text-blue-700 text-xs font-semibold hover:bg-blue-100 transition-all duration-200 cursor-pointer">
                                            Lihat Bukti
                                        </a>
                                    @else
                                        <button onclick="openPaymentModal({{ $bill->id }}, '{{ $bill->paymentType->name ?? 'Tagihan' }}', {{ (int)$bill->amount }}, '{{ $bill->whatsapp_number ?? $student->parent_phone ?? '' }}')"
                                            class="inline-flex items-center gap-1.5 px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold shadow-md shadow-emerald-600/20 hover:shadow-emerald-600/40 hover:-translate-y-0.5 transition-all duration-200 cursor-pointer">
                                            Bayar Sekarang
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Info footer --}}
                <div class="px-6 py-3 bg-amber-50/60 border-t border-amber-100 flex items-center justify-between">
                    <p class="text-xs text-amber-700">
                        <svg class="w-3.5 h-3.5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.25 11.25l.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z"/>
                        </svg>
                        Pilih "Bayar Sekarang" untuk menghubungi WhatsApp sekolah & unggah bukti transfer.
                    </p>
                    <p class="text-xs font-bold text-amber-800">
                        Total: Rp {{ number_format($totalTagihan, 0, ',', '.') }}
                    </p>
                </div>
            @endif
        </div>

        {{-- Paid Bills History --}}
        @if($paidBills->isNotEmpty())
        <div class="glass-card overflow-hidden fade-in">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-3">
                <div class="w-3 h-3 rounded-full bg-emerald-400"></div>
                <h2 class="font-bold text-gray-800">Riwayat Pembayaran Lunas</h2>
                <span class="ml-auto inline-flex items-center justify-center w-6 h-6 rounded-full bg-emerald-100 text-emerald-700 text-xs font-bold">
                    {{ $paidBills->count() }}
                </span>
            </div>
            <div class="divide-y divide-gray-50">
                @foreach($paidBills as $bill)
                    <div class="flex items-center gap-4 px-6 py-4 hover:bg-gray-50/40 transition-colors duration-150">
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-gray-800 text-sm truncate">
                                {{ $bill->paymentType->name ?? 'Pembayaran' }}
                            </p>
                            @if($bill->paid_at)
                                <p class="text-xs text-gray-400 mt-0.5">
                                    Dibayar: {{ $bill->paid_at->format('d M Y, H:i') }}
                                </p>
                            @endif
                        </div>
                        <div class="text-right shrink-0">
                            <p class="font-bold text-emerald-700 text-sm">
                                Rp {{ number_format($bill->amount, 0, ',', '.') }}
                            </p>
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700 mt-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>Lunas
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="px-6 py-3 bg-emerald-50/60 border-t border-emerald-100 flex justify-end">
                <p class="text-xs font-bold text-emerald-800">
                    Total Terbayar: Rp {{ number_format($totalTerbayar, 0, ',', '.') }}
                </p>
            </div>
        </div>
        @endif

        {{-- Footer note --}}
        <p class="text-center text-xs text-gray-400 pb-6">
            &copy; {{ date('Y') }} TK/PAUD Wildani · Sistem Informasi Layanan Pembayaran
        </p>

    </main>
</div>

{{-- Payment Modal --}}
<div id="payment-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm hidden">
    <div class="glass-card w-full max-w-md overflow-hidden bg-white/95 shadow-2xl relative">
        {{-- Close button --}}
        <button onclick="closePaymentModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>

        <div class="px-6 pt-6 pb-4 border-b border-gray-100">
            <h3 class="text-lg font-bold text-gray-800" id="modal-title">Bayar Tagihan</h3>
            <p class="text-xs text-gray-400 mt-1" id="modal-amount-label">Nominal: Rp 0</p>
        </div>

        {{-- Step Navigation Tab --}}
        <div class="flex border-b border-gray-100 text-xs font-semibold">
            <button onclick="switchTab('wa')" id="btn-tab-wa" class="flex-1 py-3 text-center border-b-2 border-emerald-600 text-emerald-600 bg-emerald-50/30">
                1. Minta Rekening (WA)
            </button>
            <button onclick="switchTab('proof')" id="btn-tab-proof" class="flex-1 py-3 text-center border-b-2 border-transparent text-gray-500 hover:bg-gray-50/50">
                2. Unggah Struk Bukti
            </button>
        </div>

        <div class="p-6 space-y-4">
            {{-- Tab 1: WA Request --}}
            <div id="tab-content-wa" class="space-y-4">
                <p class="text-xs text-gray-500 leading-relaxed">
                    Sistem akan mengirimkan pesan WhatsApp berisi instruksi lengkap beserta detail rekening transfer ke nomor Anda melalui layanan Fonnte. Silakan masukkan nomor WhatsApp Anda di bawah ini:
                </p>
                <form id="pay-wa-form" method="POST" class="space-y-3">
                    @csrf
                    <div>
                        <label for="whatsapp_number" class="block text-xxs font-bold text-gray-650 mb-1 uppercase">Nomor WhatsApp</label>
                        <input type="text" id="whatsapp_number" name="whatsapp_number" required placeholder="Contoh: 081234567890"
                            class="w-full px-3 py-2 rounded-lg border border-gray-200 bg-white/70 text-sm focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 transition-all">
                    </div>
                    <button type="submit" onclick="autoTransitionToUpload()"
                        class="w-full py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs shadow-md shadow-emerald-600/10 flex items-center justify-center gap-1.5 transition-all cursor-pointer">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                        Kirim Instruksi Pembayaran
                    </button>
                </form>
            </div>

            {{-- Tab 2: Proof upload --}}
            <div id="tab-content-proof" class="space-y-4 hidden">
                <p class="text-xs text-gray-500 leading-relaxed">
                    Sudah melakukan transfer? Silakan unggah foto/gambar bukti transfer Anda untuk diverifikasi oleh admin sekolah.
                </p>
                <form id="upload-proof-form" method="POST" enctype="multipart/form-data" class="space-y-3">
                    @csrf
                    <div>
                        <label for="payment_proof" class="block text-xxs font-bold text-gray-650 mb-1 uppercase">Pilih File Bukti</label>
                        <input type="file" id="payment_proof" name="payment_proof" accept="image/*" required
                            class="w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100 cursor-pointer">
                        <p class="text-gray-400 text-xxs mt-1">Hanya gambar (JPG, JPEG, PNG). Maksimal 2 MB.</p>
                    </div>
                    <button type="submit"
                        class="w-full py-2.5 rounded-xl bg-primary-600 hover:bg-primary-700 text-white font-semibold text-xs shadow-md transition-all">
                        Unggah & Ajukan Verifikasi
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function openPaymentModal(billId, billName, amount, waNumber) {
    const modal = document.getElementById('payment-modal');
    modal.classList.remove('hidden');

    document.getElementById('modal-title').textContent = 'Bayar: ' + billName;
    document.getElementById('modal-amount-label').textContent = 'Nominal: Rp ' + new Intl.NumberFormat('id-ID').format(amount);
    
    // Set form actions
    document.getElementById('pay-wa-form').action = '/student/bills/' + billId + '/pay';
    document.getElementById('upload-proof-form').action = '/student/bills/' + billId + '/upload-proof';
    
    // Set WhatsApp number input
    document.getElementById('whatsapp_number').value = waNumber;

    // Reset default view to WA tab
    switchTab('wa');
}

function closePaymentModal() {
    document.getElementById('payment-modal').classList.add('hidden');
}

function switchTab(tab) {
    const tabWa = document.getElementById('btn-tab-wa');
    const tabProof = document.getElementById('btn-tab-proof');
    const contentWa = document.getElementById('tab-content-wa');
    const contentProof = document.getElementById('tab-content-proof');

    if (tab === 'wa') {
        tabWa.className = "flex-1 py-3 text-center border-b-2 border-emerald-600 text-emerald-600 bg-emerald-50/30";
        tabProof.className = "flex-1 py-3 text-center border-b-2 border-transparent text-gray-500 hover:bg-gray-50/50";
        contentWa.classList.remove('hidden');
        contentProof.classList.add('hidden');
    } else {
        tabWa.className = "flex-1 py-3 text-center border-b-2 border-transparent text-gray-500 hover:bg-gray-50/50";
        tabProof.className = "flex-1 py-3 text-center border-b-2 border-primary-600 text-primary-600 bg-primary-50/30";
        contentWa.classList.add('hidden');
        contentProof.classList.remove('hidden');
    }
}

function autoTransitionToUpload() {
    // When user clicks the WA redirect, wait a short moment then switch tabs to step 2 so they can easily upload proof when they return
    setTimeout(() => {
        switchTab('proof');
    }, 1500);
}

// Auto-refresh: poll every 15 seconds to check for status changes (realtime verification)
let lastBillData = null;
function checkForUpdates() {
    fetch(window.location.href, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(response => response.text())
    .then(html => {
        const parser = new DOMParser();
        const doc = parser.parseFromString(html, 'text/html');
        const newBillSection = doc.querySelector('#bill-status-section');
        const currentBillSection = document.querySelector('#bill-status-section');
        
        if (newBillSection && currentBillSection) {
            if (newBillSection.innerHTML !== currentBillSection.innerHTML) {
                // Status changed! Update the section with a smooth transition
                currentBillSection.style.opacity = '0.5';
                setTimeout(() => {
                    currentBillSection.innerHTML = newBillSection.innerHTML;
                    currentBillSection.style.opacity = '1';
                }, 300);
            }
        }
    })
    .catch(() => {}); // Silently fail on network errors
}

// Start polling every 15 seconds
setInterval(checkForUpdates, 15000);
</script>
@endsection
