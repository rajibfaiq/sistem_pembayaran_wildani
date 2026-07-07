<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Bill extends Model
{
    use HasFactory;

    /** @var list<string> */
    protected $fillable = [
        'student_id',
        'payment_type_id',
        'amount',
        'status',
        'due_date',
        'paid_at',
        'payment_proof',
        'whatsapp_number',
        'rejected_reason',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'due_date' => 'date',
            'paid_at' => 'datetime',
        ];
    }

    /**
     * Check if bill is awaiting admin verification.
     */
    public function isWaitingVerification(): bool
    {
        return $this->status === 'waiting_verification';
    }

    /**
     * @return BelongsTo<Student, $this>
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * @return BelongsTo<PaymentType, $this>
     */
    public function paymentType(): BelongsTo
    {
        return $this->belongsTo(PaymentType::class);
    }
}
