<?php

namespace App\Models;

use App\Enums\AppointmentStatus;
use App\Enums\AppointmentType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Appointment extends Model
{
    protected $fillable = [
        'patient_id',
        'appointment_date',
        'queue_number',
        'type',
        'status',
        'fee',
        'is_paid',
    ];

    protected function casts(): array
    {
        return [
            'appointment_date' => 'date',
            'type' => AppointmentType::class,
            'status' => AppointmentStatus::class,
            'fee' => 'decimal:2',
            'is_paid' => 'boolean',
        ];
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function consultation(): HasOne
    {
        return $this->hasOne(Consultation::class);
    }

    public function invoice(): HasOne
    {
        return $this->hasOne(Invoice::class);
    }
}
