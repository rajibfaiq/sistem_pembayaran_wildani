<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Services\FonnteWhatsappService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class StudentPaymentController extends Controller
{
    public function __construct(protected FonnteWhatsappService $whatsAppService) {}

    /**
     * Process payment request: save WhatsApp number and send payment details via Fonnte.
     */
    public function requestPayment(Request $request, Bill $bill): RedirectResponse
    {
        $user = Auth::user();

        // Ensure the bill belongs to the authenticated student
        if ($bill->student_id !== $user->student_id) {
            abort(403, 'Anda tidak memiliki akses ke tagihan ini.');
        }

        // Only pending or rejected bills can be paid
        if (! in_array($bill->status, ['pending', 'overdue', 'rejected'])) {
            return back()->with('error', 'Tagihan ini tidak dapat dibayar.');
        }

        $request->validate([
            'whatsapp_number' => ['required', 'string', 'max:20'],
        ]);

        $bill->update([
            'whatsapp_number' => $request->input('whatsapp_number'),
        ]);

        $student = $bill->student;
        $paymentType = $bill->paymentType;

        // Build and send the bill message using Fonnte
        $message = $this->whatsAppService->buildBillMessage([
            'nama' => $student->nama,
            'kelas' => $student->kelas,
            'payment_type' => $paymentType->name,
            'amount_formatted' => 'Rp '.number_format($bill->amount, 0, ',', '.'),
            'due_date' => $bill->due_date ? $bill->due_date->format('d M Y') : now()->addDays(7)->format('d M Y'),
            'bill_id' => $bill->id,
            'payment_method' => 'bank_transfer', // BRI Bank Transfer is default in buildBillMessage
        ]);

        $waResult = $this->whatsAppService->send($bill->whatsapp_number, $message);

        if ($waResult['success']) {
            return back()->with('success', 'Instruksi pembayaran telah dikirim ke WhatsApp Anda! Silakan transfer dan unggah bukti transfer di bawah.');
        }

        return back()->with('success', 'Nomor WhatsApp berhasil didaftarkan. Namun gagal mengirim pesan WhatsApp otomatis. Silakan hubungi admin sekolah.');
    }

    /**
     * Upload payment proof (receipt/struk) for a bill.
     */
    public function uploadProof(Request $request, Bill $bill): RedirectResponse
    {
        $user = Auth::user();

        // Ensure the bill belongs to the authenticated student
        if ($bill->student_id !== $user->student_id) {
            abort(403, 'Anda tidak memiliki akses ke tagihan ini.');
        }

        // Only pending, overdue, rejected, or waiting_verification bills can have proof uploaded
        if (! in_array($bill->status, ['pending', 'overdue', 'rejected', 'waiting_verification'])) {
            return back()->with('error', 'Bukti pembayaran tidak dapat diunggah untuk tagihan ini.');
        }

        $request->validate([
            'payment_proof' => ['required', 'image', 'mimes:jpeg,jpg,png', 'max:2048'],
        ]);

        // Delete old proof if exists
        if ($bill->payment_proof) {
            Storage::disk('public')->delete($bill->payment_proof);
        }

        // Store the new proof
        $path = $request->file('payment_proof')->store('payment-proofs', 'public');

        $bill->update([
            'payment_proof' => $path,
            'status' => 'waiting_verification',
            'rejected_reason' => null,
        ]);

        return back()->with('success', 'Bukti pembayaran berhasil diunggah! Menunggu verifikasi admin.');
    }
}
