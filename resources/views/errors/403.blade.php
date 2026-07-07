@extends('layouts.app')

@section('title', '403 - Akses Ditolak')

@section('content')
<div class="min-h-screen gradient-bg flex items-center justify-center p-4">

    {{-- Background decorations --}}
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-40 -right-40 w-96 h-96 rounded-full bg-red-400/10 blur-3xl"></div>
        <div class="absolute -bottom-40 -left-40 w-96 h-96 rounded-full bg-accent-400/10 blur-3xl"></div>
    </div>

    <div class="relative w-full max-w-md fade-in text-center">

        {{-- Icon --}}
        <div class="inline-flex items-center justify-center w-20 h-20 rounded-2xl bg-red-100 shadow-xl shadow-red-500/20 mb-6">
            <svg class="w-10 h-10 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M18.364 18.364A9 9 0 0 0 5.636 5.636m12.728 12.728A9 9 0 0 1 5.636 5.636m12.728 12.728L5.636 5.636"/>
            </svg>
        </div>

        {{-- Error Code --}}
        <h1 class="text-7xl font-extrabold text-gray-900/10 tracking-tight mb-2">403</h1>

        {{-- Card --}}
        <div class="glass-card p-8 -mt-6">
            <h2 class="text-xl font-bold text-gray-900 mb-2">Akses Ditolak</h2>
            <p class="text-gray-500 text-sm leading-relaxed mb-6">
                {{ $exception->getMessage() ?: 'Anda tidak memiliki izin untuk mengakses halaman ini. Silakan kembali ke halaman yang sesuai dengan akun Anda.' }}
            </p>

            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                @auth
                    @if(Auth::user()->role === 'admin')
                        <a href="{{ route('admin.payment-report') }}"
                            class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl bg-primary-600 hover:bg-primary-700 text-white font-semibold text-sm shadow-lg shadow-primary-600/30 transition-all duration-200 hover:shadow-primary-600/50 hover:-translate-y-0.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z"/>
                            </svg>
                            Dashboard Admin
                        </a>
                    @else
                        <a href="{{ route('student.dashboard') }}"
                            class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl bg-primary-600 hover:bg-primary-700 text-white font-semibold text-sm shadow-lg shadow-primary-600/30 transition-all duration-200 hover:shadow-primary-600/50 hover:-translate-y-0.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/>
                            </svg>
                            Dashboard Siswa
                        </a>
                    @endif
                @endauth

                <a href="{{ route('dashboard') }}"
                    class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl border border-gray-200 bg-white/70 hover:bg-white text-gray-700 font-semibold text-sm transition-all duration-200 hover:-translate-y-0.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/>
                    </svg>
                    Halaman Utama
                </a>
            </div>
        </div>

        <p class="text-center text-xs text-gray-400 mt-6">&copy; {{ date('Y') }} Wildani TK/PAUD. Hak cipta dilindungi.</p>
    </div>
</div>
@endsection
