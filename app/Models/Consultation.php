<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Consultation extends Model
{
    protected $fillable = [
        'appointment_id',
        'patient_id',
        'vitals',
        'chief_complaint',
        'diagnosis',
        'internal_notes',
    ];

    protected function casts(): array
    {
        return [
            'vitals' => 'array',
        ];
    }

    public function appointment(): BelongsTo
    {
        return $this->belongsTo(Appointment::class);
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function prescription(): HasOne
    {
        return $this->hasOne(Prescription::class);
    }

    public function prescriptionItems(): HasManyThrough
    {
        return $this->hasManyThrough(
            PrescriptionItem::class,
            Prescription::class,
            'consultation_id',
            'prescription_id',
            'id',
            'id'
        );
    }
}
