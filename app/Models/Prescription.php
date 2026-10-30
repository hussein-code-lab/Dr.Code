<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Prescription extends Model
{
    protected $fillable = [
        'consultation_id',
        'patient_id',
        'general_advice',
    ];

    public function consultation(): BelongsTo
    {
        return $this->belongsTo(Consultation::class);
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(PrescriptionItem::class);
    }

    protected static function booted(): void
    {
        static::creating(function (Prescription $prescription) {
            if (blank($prescription->patient_id) && $prescription->consultation) {
                $prescription->patient_id = $prescription->consultation->patient_id;
            }
        });
    }
}
