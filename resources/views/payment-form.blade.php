@extends('layouts.app')

@section('title', $paymentType->name . ' - WILDANI Sistem Pembayaran')

@section('content')
<div class="flex min-h-screen">
    {{-- Sidebar --}}
    <x-sidebar :paymentTypes="$paymentTypes" :activeSlug="$paymentType->slug" />

    {{-- Main Content --}}
    <main class="flex-1 min-w-0 lg:ml-72 gradient-bg min-h-screen">
        <div class="pl-16 pr-6 py-8 lg:px-10">
            <div class="max-w-3xl mx-auto">

                {{-- Breadcrumb --}}
                <nav class="flex items-center gap-2 text-sm text-gray-400 mb-6 fade-in">
                    <a href="{{ route('dashboard') }}" class="hover:text-primary-600 transition-colors">Dashboard</a>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m8.25 4.5 7.5 7.5-7.5 7.5"/>
                    </svg>
                    <span class="text-gray-600 font-medium">{{ $paymentType->name }}</span>
                </nav>

                {{-- Form Card --}}
                <div class="bg-white rounded-2xl shadow-lg shadow-black/5 border border-gray-100 overflow-hidden fade-in stagger-1">

                    {{-- Card Header --}}
                    <div class="px-8 py-6 border-b border-gray-100 bg-gradient-to-r from-primary-50/50 to-secondary-50/50">
                        <div class="flex items-center gap-4">
                            <div class="flex items-center justify-center w-12 h-12 rounded-2xl
                                @switch($paymentType->icon)
                                    @case('calendar') bg-primary-100 text-primary-600 @break
                                    @case('building') bg-secondary-100 text-secondary-600 @break
                                    @case('shirt') bg-accent-100 text-accent-500 @break
                                    @case('sparkles') bg-purple-100 text-purple-600 @break
                                    @case('book') bg-amber-100 text-amber-600 @break
                                    @case('graduation-cap') bg-rose-100 text-rose-500 @break
                                    @default bg-gray-100 text-gray-600
                                @endswitch">
                                @switch($paymentType->icon)
                                    @case('calendar')
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5"/></svg>
                                        @break
                                    @case('building')
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.25 21h19.5M3.75 3v18h6V12h4.5v9h6V3H3.75zm3 3h1.5v1.5h-1.5V6zm0 3h1.5v1.5h-1.5V9zm0 3h1.5v1.5h-1.5v-1.5zm4.5-6h1.5v1.5h-1.5V6zm0 3h1.5v1.5h-1.5V9zm4.5-3h1.5v1.5h-1.5V6zm0 3h1.5v1.5h-1.5V9z"/></svg>
                                        @break
                                    @case('shirt')
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z"/></svg>
                                        @break
                                    @case('sparkles')
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.813 15.904 9 18.75l-.813-2.846a4.5 4.5 0 0 0-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 0 0 3.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 0 0 3.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 0 0-3.09 3.09ZM18.259 8.715 18 9.75l-.259-1.035a3.375 3.375 0 0 0-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 0 0 2.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 0 0 2.455 2.456L21.75 6l-1.036.259a3.375 3.375 0 0 0-2.455 2.456ZM16.894 20.567 16.5 21.75l-.394-1.183a2.25 2.25 0 0 0-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 0 0 1.423-1.423l.394-1.183.394 1.183a2.25 2.25 0 0 0 1.423 1.423l1.183.394-1.183.394a2.25 2.25 0 0 0-1.423 1.423Z"/></svg>
                                        @break
                                    @case('book')
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25"/></svg>
                                        @break
                                    @case('graduation-cap')
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5"/></svg>
                                        @break
                                    @default
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Z"/></svg>
                                @endswitch
                            </div>
                            <div>
                                <h1 class="text-xl font-bold text-gray-900">{{ $paymentType->name }}</h1>
                                <p class="text-sm text-gray-500">{{ $paymentType->description }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Form Body --}}
                    <div class="px-8 py-7">
                        <form id="payment-form">
                            <input type="hidden" name="payment_type_id" value="{{ $paymentType->id }}">
                            <input type="hidden" id="student-id" name="student_id" value="">

                            {{-- NISN Search --}}
                            <div class="mb-6">
                                <label for="nisn-input" class="block text-sm font-semibold text-gray-700 mb-2">
                                    NISN / Nomor Induk Siswa
                                </label>
                                <div class="flex gap-3">
                                    <div class="relative flex-1">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                            <svg class="w-5 h-5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/>
                                            </svg>
                                        </div>
                                        <input type="text"
                                            id="nisn-input"
                                            placeholder="Masukkan NISN siswa..."
                                            class="w-full pl-12 pr-4 py-3 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-primary-300 focus:border-primary-400 outline-none transition-all duration-200 bg-gray-50/50 hover:bg-white placeholder:text-gray-300">
                                    </div>
                                    <button type="button"
                                        id="btn-search"
                                        class="px-6 py-3 bg-primary-500 hover:bg-primary-600 active:bg-primary-700 text-white text-sm font-semibold rounded-xl transition-all duration-200 shadow-md shadow-primary-500/20 hover:shadow-lg hover:shadow-primary-500/30 flex items-center gap-2 shrink-0">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/>
                                        </svg>
                                        Cari
                                    </button>
                                </div>
                                <p class="mt-2 text-xs text-red-500 font-medium flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 1 1-16 0 8 8 0 0 1 16 0Zm-7-4a1 1 0 1 1-2 0 1 1 0 0 1 2 0ZM9 9a.75.75 0 0 0 0 1.5h.253a.25.25 0 0 1 .244.304l-.459 2.066A1.75 1.75 0 0 0 10.747 15H11a.75.75 0 0 0 0-1.5h-.253a.25.25 0 0 1-.244-.304l.459-2.066A1.75 1.75 0 0 0 9.253 9H9Z" clip-rule="evenodd"/>
                                    </svg>
                                    *Tekan Cari untuk Memunculkan Data Siswa & Tagihan
                                </p>
                            </div>

                            {{-- Loading Indicator --}}
                            <div id="search-loading" class="hidden mb-6">
                                <div class="flex items-center gap-3 p-4 rounded-xl bg-primary-50/50 border border-primary-100">
                                    <svg class="w-5 h-5 text-primary-500 animate-spin" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    <span class="text-sm text-primary-700 font-medium">Mencari data siswa...</span>
                                </div>
                            </div>

                            {{-- Student Data Section (Hidden by default) --}}
                            <div id="student-data-section" class="hidden">

                                {{-- Student Info Cards --}}
                                <div class="mb-6 p-5 bg-gradient-to-br from-primary-50/40 to-secondary-50/40 rounded-xl border border-primary-100/60">
                                    <h3 class="text-sm font-semibold text-gray-700 mb-4 flex items-center gap-2">
                                        <svg class="w-4.5 h-4.5 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/>
                                        </svg>
                                        Data Siswa
                                    </h3>

                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        {{-- Nama Siswa --}}
                                        <div>
                                            <label class="block text-xs font-medium text-gray-400 uppercase tracking-wider mb-1">Nama Siswa</label>
                                            <div id="student-nama" class="px-4 py-2.5 bg-white rounded-lg border border-gray-100 text-sm font-medium text-gray-800">—</div>
                                        </div>

                                        {{-- Kelas --}}
                                        <div>
                                            <label class="block text-xs font-medium text-gray-400 uppercase tracking-wider mb-1">Kelas</label>
                                            <div id="student-kelas" class="px-4 py-2.5 bg-white rounded-lg border border-gray-100 text-sm font-medium text-gray-800">—</div>
                                        </div>
                                    </div>

                                    {{-- Total Tagihan --}}
                                    <div class="mt-4 p-4 bg-white rounded-xl border border-gray-100">
                                        <label class="block text-xs font-medium text-gray-400 uppercase tracking-wider mb-1">Total Tagihan</label>
                                        <div id="student-tagihan" class="text-2xl font-bold text-primary-600">—</div>
                                    </div>

                                    {{-- Bills Breakdown --}}
                                    <div id="bills-breakdown" class="mt-4 space-y-2">
                                        {{-- Bills will be injected here via JS --}}
                                    </div>
                                </div>

                                {{-- Divider --}}
                                <div class="relative mb-6">
                                    <div class="absolute inset-0 flex items-center">
                                        <div class="w-full border-t border-gray-100"></div>
                                    </div>
                                    <div class="relative flex justify-center text-xs uppercase">
                                        <span class="bg-white px-3 text-gray-300 font-semibold tracking-widest">Informasi Pembayaran</span>
                                    </div>
                                </div>

                                {{-- Phone Number Input --}}
                                <div class="mb-6">
                                    <label for="phone-input" class="block text-sm font-semibold text-gray-700 mb-2">
                                        Nomor Telepon / WhatsApp Orang Tua
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                            <svg class="w-5 h-5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.5 1.5H8.25A2.25 2.25 0 0 0 6 3.75v16.5a2.25 2.25 0 0 0 2.25 2.25h7.5A2.25 2.25 0 0 0 18 20.25V3.75a2.25 2.25 0 0 0-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3"/>
                                            </svg>
                                        </div>
                                        <input type="tel"
                                            id="phone-input"
                                            name="phone"
                                            placeholder="Contoh: 081234567xxx"
                                            class="w-full pl-12 pr-4 py-3 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-primary-300 focus:border-primary-400 outline-none transition-all duration-200 bg-gray-50/50 hover:bg-white placeholder:text-gray-300">
                                    </div>
                                    <p class="mt-1.5 text-xs text-gray-400 flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5 shrink-0 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.345 0-2.189-1.458-1.515-2.625L8.485 2.495ZM10 5a.75.75 0 0 1 .75.75v3.5a.75.75 0 0 1-1.5 0v-3.5A.75.75 0 0 1 10 5Zm0 9a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z" clip-rule="evenodd"/>
                                        </svg>
                                        *Pastikan nomor benar dan aktif untuk menerima notifikasi
                                    </p>
                                </div>

                                {{-- Payment Method Selection --}}
                                <div class="mb-8">
                                    <label class="block text-sm font-semibold text-gray-700 mb-3">
                                        Metode Pembayaran
                                    </label>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                        {{-- Bank Transfer VA --}}
                                        <label id="method-bank-transfer"
                                            class="payment-method-option relative flex items-center gap-3 p-4 rounded-xl border-2 border-gray-100 bg-gray-50/30 cursor-pointer transition-all duration-200 hover:border-primary-200 hover:bg-primary-50/20">
                                            <input type="radio" name="payment_method" value="bank_transfer" class="sr-only peer" checked>
                                            <div class="flex items-center justify-center w-10 h-10 rounded-xl bg-secondary-100 text-secondary-600 shrink-0 peer-checked:bg-primary-500 peer-checked:text-white transition-all duration-200">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.25 21h19.5M3.75 3v18h6V12h4.5v9h6V3H3.75zm3 3h1.5v1.5h-1.5V6zm0 3h1.5v1.5h-1.5V9zm0 3h1.5v1.5h-1.5v-1.5zm4.5-6h1.5v1.5h-1.5V6zm0 3h1.5v1.5h-1.5V9zm4.5-3h1.5v1.5h-1.5V6zm0 3h1.5v1.5h-1.5V9z"/>
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="text-sm font-semibold text-gray-700">Bank Transfer</p>
                                                <p class="text-xs text-gray-400">BRI 657401013488535 a.n Uswatun Hasanah</p>
                                            </div>
                                            {{-- Check indicator --}}
                                            <div class="absolute top-3 right-3 w-5 h-5 rounded-full border-2 border-gray-200 flex items-center justify-center peer-checked:border-primary-500 peer-checked:bg-primary-500 transition-all duration-200">
                                                <svg class="w-3 h-3 text-white opacity-0 peer-checked:opacity-100 transition-opacity" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 0 1 0 1.414l-8 8a1 1 0 0 1-1.414 0l-4-4a1 1 0 0 1 1.414-1.414L8 12.586l7.293-7.293a1 1 0 0 1 1.414 0Z" clip-rule="evenodd"/>
                                                </svg>
                                            </div>
                                        </label>

                                        {{-- E-Wallet --}}
                                        <label id="method-e-wallet"
                                            class="payment-method-option relative flex items-center gap-3 p-4 rounded-xl border-2 border-gray-100 bg-gray-50/30 cursor-pointer transition-all duration-200 hover:border-primary-200 hover:bg-primary-50/20">
                                            <input type="radio" name="payment_method" value="e_wallet" class="sr-only peer">
                                            <div class="flex items-center justify-center w-10 h-10 rounded-xl bg-accent-100 text-accent-500 shrink-0 peer-checked:bg-primary-500 peer-checked:text-white transition-all duration-200">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 12a2.25 2.25 0 0 0-2.25-2.25H15a3 3 0 1 1-6 0H5.25A2.25 2.25 0 0 0 3 12m18 0v6a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 18v-6m18 0V9M3 12V9m18 0a2.25 2.25 0 0 0-2.25-2.25H5.25A2.25 2.25 0 0 0 3 9m18 0V6a2.25 2.25 0 0 0-2.25-2.25H5.25A2.25 2.25 0 0 0 3 6v3"/>
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="text-sm font-semibold text-gray-700">E-Wallet</p>
                                                <p class="text-xs text-gray-400">GoPay, OVO, DANA</p>
                                            </div>
                                            {{-- Check indicator --}}
                                            <div class="absolute top-3 right-3 w-5 h-5 rounded-full border-2 border-gray-200 flex items-center justify-center peer-checked:border-primary-500 peer-checked:bg-primary-500 transition-all duration-200">
                                                <svg class="w-3 h-3 text-white opacity-0 peer-checked:opacity-100 transition-opacity" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 0 1 0 1.414l-8 8a1 1 0 0 1-1.414 0l-4-4a1 1 0 0 1 1.414-1.414L8 12.586l7.293-7.293a1 1 0 0 1 1.414 0Z" clip-rule="evenodd"/>
                                                </svg>
                                            </div>
                                        </label>
                                    </div>
                                </div>

                                {{-- Submit Button --}}
                                <div class="flex justify-end">
                                    <button type="submit"
                                        id="btn-submit"
                                        class="px-8 py-3.5 bg-secondary-500 hover:bg-secondary-600 active:bg-secondary-700 text-white text-sm font-bold rounded-xl transition-all duration-200 shadow-lg shadow-secondary-500/25 hover:shadow-xl hover:shadow-secondary-500/30 flex items-center gap-2.5 disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:shadow-lg">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                        </svg>
                                        Buat Tagihan
                                    </button>
                                </div>

                            </div>{{-- End student-data-section --}}

                        </form>
                    </div>
                </div>

                {{-- Sample NISN hint --}}
                <div class="mt-5 p-4 glass-card fade-in stagger-2">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-primary-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 18v-5.25m0 0a6.01 6.01 0 0 0 1.5-.189m-1.5.189a6.01 6.01 0 0 1-1.5-.189m3.75 7.478a12.06 12.06 0 0 1-4.5 0m3.75 2.383a14.406 14.406 0 0 1-3 0M14.25 18v-.192c0-.983.658-1.823 1.508-2.316a7.5 7.5 0 1 0-7.517 0c.85.493 1.509 1.333 1.509 2.316V18"/>
                        </svg>
                        <div>
                            <p class="text-sm font-medium text-gray-600">Contoh NISN untuk Demo</p>
                            <p class="text-xs text-gray-400 mt-1">0051234001, 0051234002, 0051234003, 0051234004, 0051234005</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
@endsection
