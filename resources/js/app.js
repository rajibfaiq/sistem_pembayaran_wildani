import './bootstrap';

document.addEventListener('DOMContentLoaded', function () {
    // ─── NISN Search Handler ────────────────────────────────────────
    const btnSearch = document.getElementById('btn-search');
    const nisnInput = document.getElementById('nisn-input');
    const loadingEl = document.getElementById('search-loading');
    const studentSection = document.getElementById('student-data-section');
    const studentIdField = document.getElementById('student-id');

    if (btnSearch && nisnInput) {
        btnSearch.addEventListener('click', handleSearch);
        nisnInput.addEventListener('keydown', function (e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                handleSearch();
            }
        });
    }

    async function handleSearch() {
        const nisn = nisnInput.value.trim();
        if (!nisn) {
            showToast('Silakan masukkan NISN terlebih dahulu.', 'error');
            nisnInput.focus();
            return;
        }

        // Show loading
        if (loadingEl) loadingEl.classList.remove('hidden');
        if (studentSection) studentSection.classList.add('hidden');
        btnSearch.disabled = true;
        btnSearch.innerHTML = `
            <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Mencari...
        `;

        try {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const response = await fetch('/api/search-student', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ nisn: nisn }),
            });

            const result = await response.json();

            if (response.ok && result.success) {
                populateStudentData(result.data);
                if (studentSection) {
                    studentSection.classList.remove('hidden');
                    studentSection.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                }
                showToast('Data siswa berhasil ditemukan!', 'success');
            } else {
                showToast(result.message || 'Data siswa tidak ditemukan.', 'error');
                if (studentSection) studentSection.classList.add('hidden');
            }
        } catch (error) {
            showToast('Terjadi kesalahan saat mencari data. Silakan coba lagi.', 'error');
            console.error('Search error:', error);
        } finally {
            if (loadingEl) loadingEl.classList.add('hidden');
            btnSearch.disabled = false;
            btnSearch.innerHTML = `
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/>
                </svg>
                Cari
            `;
        }
    }

    function populateStudentData(data) {
        // Set hidden student ID
        if (studentIdField) studentIdField.value = data.id;

        // Populate read-only fields
        const namaEl = document.getElementById('student-nama');
        const kelasEl = document.getElementById('student-kelas');
        const tagihanEl = document.getElementById('student-tagihan');

        if (namaEl) namaEl.textContent = data.nama;
        if (kelasEl) kelasEl.textContent = data.kelas;
        if (tagihanEl) tagihanEl.textContent = data.total_tagihan_formatted;

        // Pre-fill phone if available
        const phoneInput = document.getElementById('phone-input');
        if (phoneInput && data.parent_phone) {
            phoneInput.value = data.parent_phone;
        }

        // Populate bills breakdown
        const billsContainer = document.getElementById('bills-breakdown');
        if (billsContainer && data.bills && data.bills.length > 0) {
            billsContainer.innerHTML = data.bills.map(function (bill) {
                const statusColor = bill.status === 'paid'
                    ? 'bg-green-50 text-green-700 border-green-100'
                    : bill.status === 'overdue'
                        ? 'bg-red-50 text-red-700 border-red-100'
                        : 'bg-amber-50 text-amber-700 border-amber-100';

                const statusLabel = bill.status === 'paid'
                    ? 'Lunas'
                    : bill.status === 'overdue'
                        ? 'Terlambat'
                        : 'Belum Bayar';

                return `
                    <div class="flex items-center justify-between p-3 bg-white rounded-lg border border-gray-100">
                        <div>
                            <p class="text-sm font-medium text-gray-700">${bill.type}</p>
                            <p class="text-xs text-gray-400">Jatuh tempo: ${bill.due_date || '-'}</p>
                        </div>
                        <div class="text-right flex items-center gap-3">
                            <span class="text-sm font-bold text-gray-800">${bill.amount_formatted}</span>
                            <span class="text-xs font-medium px-2 py-0.5 rounded-full border ${statusColor}">${statusLabel}</span>
                        </div>
                    </div>
                `;
            }).join('');
        } else if (billsContainer) {
            billsContainer.innerHTML = '<p class="text-sm text-gray-400 text-center py-2">Tidak ada tagihan pending.</p>';
        }
    }

    // ─── Payment Method Selection Visual ─────────────────────────────
    const methodOptions = document.querySelectorAll('.payment-method-option');
    methodOptions.forEach(function (option) {
        const radio = option.querySelector('input[type="radio"]');
        if (radio) {
            // Initial state
            updateMethodStyle(option, radio.checked);

            radio.addEventListener('change', function () {
                // Reset all
                methodOptions.forEach(function (opt) {
                    updateMethodStyle(opt, false);
                });
                // Activate selected
                updateMethodStyle(option, true);
            });
        }
    });

    function updateMethodStyle(element, isActive) {
        if (isActive) {
            element.classList.remove('border-gray-100', 'bg-gray-50/30');
            element.classList.add('border-primary-300', 'bg-primary-50/30', 'shadow-sm', 'shadow-primary-500/10');
        } else {
            element.classList.remove('border-primary-300', 'bg-primary-50/30', 'shadow-sm', 'shadow-primary-500/10');
            element.classList.add('border-gray-100', 'bg-gray-50/30');
        }

        // Update check indicator
        const indicator = element.querySelector('.rounded-full');
        const checkIcon = indicator ? indicator.querySelector('svg') : null;
        if (indicator) {
            if (isActive) {
                indicator.classList.remove('border-gray-200');
                indicator.classList.add('border-primary-500', 'bg-primary-500');
            } else {
                indicator.classList.remove('border-primary-500', 'bg-primary-500');
                indicator.classList.add('border-gray-200');
            }
        }
        if (checkIcon) {
            checkIcon.style.opacity = isActive ? '1' : '0';
        }

        // Update icon container
        const iconContainer = element.querySelector('.w-10');
        if (iconContainer) {
            if (isActive) {
                iconContainer.classList.remove('bg-secondary-100', 'text-secondary-600', 'bg-accent-100', 'text-accent-500');
                iconContainer.classList.add('bg-primary-500', 'text-white');
            } else {
                iconContainer.classList.remove('bg-primary-500', 'text-white');
                // Restore original colors based on position
                const isFirst = element === methodOptions[0];
                if (isFirst) {
                    iconContainer.classList.add('bg-secondary-100', 'text-secondary-600');
                } else {
                    iconContainer.classList.add('bg-accent-100', 'text-accent-500');
                }
            }
        }
    }

    // ─── Form Submit Handler ────────────────────────────────────────
    const paymentForm = document.getElementById('payment-form');
    if (paymentForm) {
        paymentForm.addEventListener('submit', async function (e) {
            e.preventDefault();

            const studentId = document.getElementById('student-id').value;
            const phone = document.getElementById('phone-input').value.trim();
            const paymentMethod = document.querySelector('input[name="payment_method"]:checked');
            const paymentTypeId = document.querySelector('input[name="payment_type_id"]').value;

            // Validation
            if (!studentId) {
                showToast('Silakan cari data siswa terlebih dahulu.', 'error');
                return;
            }
            if (!phone) {
                showToast('Silakan masukkan nomor telepon/WhatsApp.', 'error');
                document.getElementById('phone-input').focus();
                return;
            }
            if (!paymentMethod) {
                showToast('Silakan pilih metode pembayaran.', 'error');
                return;
            }

            const btnSubmit = document.getElementById('btn-submit');
            btnSubmit.disabled = true;
            btnSubmit.innerHTML = `
                <svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Memproses...
            `;

            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                const response = await fetch('/api/create-bill', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        student_id: studentId,
                        payment_type_id: paymentTypeId,
                        phone: phone,
                        payment_method: paymentMethod.value,
                    }),
                });

                const result = await response.json();

                if (response.ok && result.success) {
                    showToast(result.message, 'success');
                } else {
                    const errorMsg = result.message || (result.errors ? Object.values(result.errors).flat().join(', ') : 'Gagal membuat tagihan.');
                    showToast(errorMsg, 'error');
                }
            } catch (error) {
                showToast('Terjadi kesalahan. Silakan coba lagi.', 'error');
                console.error('Submit error:', error);
            } finally {
                btnSubmit.disabled = false;
                btnSubmit.innerHTML = `
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                    </svg>
                    Buat Tagihan
                `;
            }
        });
    }

    // ─── Toast Notification ─────────────────────────────────────────
    function showToast(message, type) {
        const container = document.getElementById('toast-container');
        if (!container) return;

        const toast = document.createElement('div');
        toast.className = `toast toast-${type}`;
        toast.innerHTML = `
            <div class="flex items-center gap-3">
                ${type === 'success'
                    ? '<svg class="w-5 h-5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm3.857-9.809a.75.75 0 0 0-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 1 0-1.06 1.061l2.5 2.5a.75.75 0 0 0 1.137-.089l4-5.5Z" clip-rule="evenodd"/></svg>'
                    : '<svg class="w-5 h-5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 1 1-16 0 8 8 0 0 1 16 0Zm-8-5a.75.75 0 0 1 .75.75v4.5a.75.75 0 0 1-1.5 0v-4.5A.75.75 0 0 1 10 5Zm0 10a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z" clip-rule="evenodd"/></svg>'
                }
                <p class="text-sm font-medium">${message}</p>
            </div>
        `;

        container.appendChild(toast);

        // Auto-remove after 4 seconds
        setTimeout(function () {
            toast.style.opacity = '0';
            toast.style.transform = 'translateX(100px)';
            toast.style.transition = 'all 0.3s ease-in';
            setTimeout(function () {
                toast.remove();
            }, 300);
        }, 4000);
    }
});
