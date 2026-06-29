<?php

namespace App\Services;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FonnteWhatsappService
{
    private string $apiUrl = 'https://api.fonnte.com/send';

    private string $token;

    public function __construct()
    {
        $this->token = config('services.fonnte.token', '');
    }

    /**
     * Send a WhatsApp message via Fonnte API.
     *
     * @param  array{target: string, message: string}  $payload
     * @return array{success: bool, message: string, detail: mixed}
     */
    public function send(string $target, string $message): array
    {
        if (empty($this->token)) {
            Log::warning('Fonnte API token is not configured. Skipping WhatsApp notification.');

            return [
                'success' => false,
                'message' => 'Fonnte API token belum dikonfigurasi.',
                'detail' => null,
            ];
        }

        try {
            /** @var Response $response */
            $response = Http::timeout(15)
                ->connectTimeout(5)
                ->withHeaders([
                    'Authorization' => $this->token,
                ])
                ->asForm()
                ->post($this->apiUrl, [
                    'target' => $target,
                    'message' => $message,
                    'typing' => false,
                ]);

            $body = $response->json();

            if ($response->successful() && ($body['status'] ?? false)) {
                Log::info('WhatsApp notification sent successfully', [
                    'target' => $target,
                    'response' => $body,
                ]);

                return [
                    'success' => true,
                    'message' => 'Notifikasi WhatsApp berhasil dikirim.',
                    'detail' => $body,
                ];
            }

            Log::error('Fonnte API returned an error', [
                'target' => $target,
                'response' => $body,
                'status_code' => $response->status(),
            ]);

            return [
                'success' => false,
                'message' => $body['reason'] ?? 'Gagal mengirim notifikasi WhatsApp.',
                'detail' => $body,
            ];
        } catch (\Throwable $e) {
            Log::error('Failed to send WhatsApp notification', [
                'target' => $target,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengirim notifikasi: '.$e->getMessage(),
                'detail' => null,
            ];
        }
    }

    /**
     * Build a formatted bill notification message.
     *
     * @param  array{nama: string, kelas: string, payment_type: string, amount_formatted: string, due_date: string, bill_id: int, payment_method: string}  $data
     */
    public function buildBillMessage(array $data): string
    {
        $paymentMethodLabel = match ($data['payment_method']) {
            'bank_transfer' => "Bank Transfer\n   🏦 *Bank BRI*: 657401013488535\n   👤 *A.N.*: Uswatun Hasanah",
            'e_wallet' => 'E-Wallet (GoPay/OVO/DANA)',
            default => $data['payment_method'],
        };

        return <<<EOT
        📋 *TAGIHAN PEMBAYARAN - TK/PAUD WILDANI*
        ━━━━━━━━━━━━━━━━━━━━━━

        Assalamu'alaikum Wr. Wb.

        Yth. Orang Tua/Wali dari:
        👤 *{$data['nama']}*
        🏫 Kelas: {$data['kelas']}

        Berikut informasi tagihan pembayaran:

        📌 Jenis: *{$data['payment_type']}*
        💰 Jumlah: *{$data['amount_formatted']}*
        📅 Jatuh Tempo: {$data['due_date']}
        🔖 No. Tagihan: #{$data['bill_id']}
        💳 Metode: {$paymentMethodLabel}

        ━━━━━━━━━━━━━━━━━━━━━━

        Mohon untuk segera melakukan pembayaran sebelum jatuh tempo.

        Terima kasih atas perhatiannya.

        Wassalamu'alaikum Wr. Wb.
        🏫 *TK/PAUD WILDANI*
        EOT;
    }
}
