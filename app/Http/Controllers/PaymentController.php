<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\PaymentType;
use App\Models\Student;
use App\Services\FonnteWhatsappService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PaymentController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct(
        protected FonnteWhatsappService $whatsAppService
    ) {}

    /**
     * Display the dashboard with payment type cards.
     */
    public function dashboard(): View
    {
        $paymentTypes = PaymentType::all();

        return view('dashboard', compact('paymentTypes'));
    }

    /**
     * Show the payment form for a specific payment type.
     */
    public function showPaymentForm(string $slug): View
    {
        $paymentType = PaymentType::where('slug', $slug)->firstOrFail();
        $paymentTypes = PaymentType::all();

        return view('payment-form', compact('paymentType', 'paymentTypes'));
    }

    /**
     * Search for a student by NISN and return their data with outstanding bills.
     */
    public function searchStudent(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nisn' => ['required', 'string', 'max:20'],
        ]);

        $student = Student::with(['bills' => function ($query): void {
            $query->where('status', 'pending')
                ->with('paymentType')
                ->latest();
        }])->where('nisn', $validated['nisn'])->first();

        if (! $student) {
            return response()->json([
                'success' => false,
                'message' => 'Data siswa dengan NISN tersebut tidak ditemukan.',
            ], 404);
        }

        $totalTagihan = $student->bills->sum('amount');

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $student->id,
                'nisn' => $student->nisn,
                'nama' => $student->nama,
                'kelas' => $student->kelas,
                'parent_phone' => $student->parent_phone,
                'total_tagihan' => $totalTagihan,
                'total_tagihan_formatted' => 'Rp ' . number_format($totalTagihan, 0, ',', '.'),
                'bills' => $student->bills->map(fn (Bill $bill): array => [
                    'id' => $bill->id,
                    'type' => $bill->paymentType->name,
                    'amount' => $bill->amount,
                    'amount_formatted' => 'Rp ' . number_format($bill->amount, 0, ',', '.'),
                    'due_date' => $bill->due_date?->format('d M Y'),
                    'status' => $bill->status,
                ]),
            ],
        ]);
    }

    /**
     * Handle the bill creation form submission.
     */
    public function createBill(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'student_id' => ['required', 'exists:students,id'],
            'payment_type_id' => ['required', 'exists:payment_types,id'],
            'phone' => ['required', 'string', 'max:20'],
            'payment_method' => ['required', 'string'],
        ]);

        $student = Student::findOrFail($validated['student_id']);
        $paymentType = PaymentType::findOrFail($validated['payment_type_id']);

        $bill = Bill::create([
            'student_id' => $validated['student_id'],
            'payment_type_id' => $validated['payment_type_id'],
            'amount' => $paymentType->amount,
            'status' => 'pending',
            'due_date' => now()->addDays(7),
        ]);

        // Update parent phone if provided
        $student->update(['parent_phone' => $validated['phone']]);

        // Format amount for WhatsApp message
        $amountFormatted = 'Rp ' . number_format($paymentType->amount, 0, ',', '.');
        $dueDateFormatted = $bill->due_date ? $bill->due_date->format('d M Y') : now()->addDays(7)->format('d M Y');

        // Build and send WhatsApp notification
        $message = $this->whatsAppService->buildBillMessage([
            'nama' => $student->nama,
            'kelas' => $student->kelas,
            'payment_type' => $paymentType->name,
            'amount_formatted' => $amountFormatted,
            'due_date' => $dueDateFormatted,
            'bill_id' => $bill->id,
            'payment_method' => $validated['payment_method'],
        ]);

        $waResult = $this->whatsAppService->send($validated['phone'], $message);

        $responseMessage = 'Tagihan berhasil dibuat!';
        if ($waResult['success']) {
            $responseMessage .= ' Notifikasi WhatsApp berhasil dikirim.';
        } else {
            $responseMessage .= ' Namun, gagal mengirim WhatsApp: ' . $waResult['message'];
        }

        return response()->json([
            'success' => true,
            'message' => $responseMessage,
            'data' => [
                'bill_id' => $bill->id,
                'amount_formatted' => $amountFormatted,
                'whatsapp_sent' => $waResult['success'],
            ],
        ]);
    }
}
