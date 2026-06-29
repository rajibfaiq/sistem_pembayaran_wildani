@extends('layouts.app')

@section('title', 'Dashboard - WILDANI Sistem Pembayaran TK/PAUD')

@section('content')
<div class="flex min-h-screen">
    {{-- Sidebar --}}
    <x-sidebar :paymentTypes="$paymentTypes" activeSlug="" />

    {{-- Main Content --}}
    <main class="flex-1 lg:ml-72 gradient-bg min-h-screen">
        {{-- Header --}}
        <header class="px-6 pt-8 pb-2 lg:px-10">
            <div class="max-w-6xl mx-auto">
                <div class="flex flex-col gap-1 fade-in">
                    <div class="flex items-center gap-2 mb-1">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-primary-100 text-primary-700 tracking-wide">
                            Tahun Ajaran {{ date('Y') }}/{{ date('Y') + 1 }}
                        </span>
                    </div>
                    <h1 class="text-2xl lg:text-3xl font-bold text-gray-900 tracking-tight">
                        Selamat Datang di <span class="text-primary-600">WILDANI</span>
                    </h1>
                    <p class="text-gray-500 text-sm lg:text-base max-w-2xl">
                        Sistem Informasi Layanan Pembayaran TK/PAUD — Kelola pembayaran pendidikan anak dengan mudah, cepat, dan transparan.
                    </p>
                </div>
            </div>
        </header>

        {{-- Stats Row --}}
        <section class="px-6 py-4 lg:px-10">
            <div class="max-w-6xl mx-auto">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="glass-card p-5 fade-in stagger-1">
                        <div class="flex items-center gap-4">
                            <div class="flex items-center justify-center w-12 h-12 rounded-2xl bg-primary-100/80">
                                <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400 font-medium uppercase tracking-wider">Total Siswa</p>
                                <p class="text-2xl font-bold text-gray-900">{{ \App\Models\Student::count() }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="glass-card p-5 fade-in stagger-2">
                        <div class="flex items-center gap-4">
                            <div class="flex items-center justify-center w-12 h-12 rounded-2xl bg-accent-100/80">
                                <svg class="w-6 h-6 text-accent-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400 font-medium uppercase tracking-wider">Tagihan Pending</p>
                                <p class="text-2xl font-bold text-gray-900">{{ \App\Models\Bill::where('status', 'pending')->count() }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="glass-card p-5 fade-in stagger-3">
                        <div class="flex items-center gap-4">
                            <div class="flex items-center justify-center w-12 h-12 rounded-2xl bg-secondary-100/80">
                                <svg class="w-6 h-6 text-secondary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400 font-medium uppercase tracking-wider">Sudah Lunas</p>
                                <p class="text-2xl font-bold text-gray-900">{{ \App\Models\Bill::where('status', 'paid')->count() }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- Service Cards --}}
        <section class="px-6 py-4 pb-12 lg:px-10">
            <div class="max-w-6xl mx-auto">
                <h2 class="text-lg font-semibold text-gray-800 mb-5 fade-in">Layanan Pembayaran</h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                    @foreach($paymentTypes as $index => $type)
                        <a href="{{ route('payment.form', $type->slug) }}"
                            id="card-{{ $type->slug }}"
                            class="glass-card group p-6 cursor-pointer fade-in stagger-{{ $index + 1 }}">

                            {{-- Icon --}}
                            <div class="flex items-center justify-center w-14 h-14 rounded-2xl mb-4 transition-all duration-300 group-hover:scale-110
                                @switch($type->icon)
                                    @case('calendar') bg-primary-100 text-primary-600 @break
                                    @case('building') bg-secondary-100 text-secondary-600 @break
                                    @case('shirt') bg-accent-100 text-accent-500 @break
                                    @case('sparkles') bg-purple-100 text-purple-600 @break
                                    @case('book') bg-amber-100 text-amber-600 @break
                                    @case('graduation-cap') bg-rose-100 text-rose-500 @break
                                    @default bg-gray-100 text-gray-600
                                @endswitch
                                icon-float">

                                @switch($type->icon)
                                    @case('calendar')
                                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5"/></svg>
                                        @break
                                    @case('building')
                                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.25 21h19.5M3.75 3v18h6V12h4.5v9h6V3H3.75zm3 3h1.5v1.5h-1.5V6zm0 3h1.5v1.5h-1.5V9zm0 3h1.5v1.5h-1.5v-1.5zm4.5-6h1.5v1.5h-1.5V6zm0 3h1.5v1.5h-1.5V9zm4.5-3h1.5v1.5h-1.5V6zm0 3h1.5v1.5h-1.5V9z"/></svg>
                                        @break
                                    @case('shirt')
                                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z"/></svg>
                                        @break
                                    @case('sparkles')
                                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.813 15.904 9 18.75l-.813-2.846a4.5 4.5 0 0 0-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 0 0 3.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 0 0 3.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 0 0-3.09 3.09ZM18.259 8.715 18 9.75l-.259-1.035a3.375 3.375 0 0 0-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 0 0 2.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 0 0 2.455 2.456L21.75 6l-1.036.259a3.375 3.375 0 0 0-2.455 2.456ZM16.894 20.567 16.5 21.75l-.394-1.183a2.25 2.25 0 0 0-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 0 0 1.423-1.423l.394-1.183.394 1.183a2.25 2.25 0 0 0 1.423 1.423l1.183.394-1.183.394a2.25 2.25 0 0 0-1.423 1.423Z"/></svg>
                                        @break
                                    @case('book')
                                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25"/></svg>
                                        @break
                                    @case('graduation-cap')
                                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5"/></svg>
                                        @break
                                    @default
                                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Z"/></svg>
                                @endswitch
                            </div>

                            {{-- Content --}}
                            <h3 class="text-base font-semibold text-gray-800 mb-1 group-hover:text-primary-700 transition-colors duration-200">
                                {{ $type->name }}
                            </h3>
                            <p class="text-sm text-gray-400 mb-3 line-clamp-2">{{ $type->description }}</p>

                            {{-- Amount --}}
                            <div class="flex items-center justify-between">
                                <span class="text-lg font-bold text-primary-600">
                                    Rp {{ number_format($type->amount, 0, ',', '.') }}
                                </span>
                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-primary-50 text-primary-600 group-hover:bg-primary-500 group-hover:text-white transition-all duration-200">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/>
                                    </svg>
                                </span>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- Quick Info Cards --}}
        <section class="px-6 pb-12 lg:px-10">
            <div class="max-w-6xl mx-auto">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    {{-- Info Card --}}
                    <div class="glass-card p-6 fade-in">
                        <div class="flex items-start gap-4">
                            <div class="flex items-center justify-center w-10 h-10 rounded-xl bg-secondary-100 text-secondary-600 shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-800 mb-1">Informasi Penting</h3>
                                <p class="text-sm text-gray-500 leading-relaxed">
                                    Pembayaran SPP jatuh tempo setiap tanggal <strong>10</strong> setiap bulannya.
                                    Pastikan nomor WhatsApp aktif untuk menerima notifikasi pembayaran.
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Contact Card --}}
                    <div class="glass-card p-6 fade-in">
                        <div class="flex items-start gap-4">
                            <div class="flex items-center justify-center w-10 h-10 rounded-xl bg-accent-100 text-accent-500 shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-800 mb-1">Butuh Bantuan?</h3>
                                <p class="text-sm text-gray-500 leading-relaxed">
                                    Hubungi admin sekolah di <strong>0812-3456-7890</strong> atau datang langsung ke kantor TU pada jam kerja (08:00 - 14:00 WIB).
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
</div>
@endsection
