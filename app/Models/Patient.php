<?php

namespace App\Models;

use App\Actions\GeneratePatientCodeAction;
use App\Enums\BloodGroup;
use App\Enums\Gender;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Patient extends Model
{
    protected $fillable = [
        'code',
        'name',
        'phone',
        'national_id',
        'birth_date',
        'gender',
        'blood_group',
        'medical_history',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
            'gender' => Gender::class,
            'blood_group' => BloodGroup::class,
            'medical_history' => 'array',
        ];
    }

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }

    public function consultations(): HasMany
    {
        return $this->hasMany(Consultation::class);
    }

    public function prescriptions(): HasMany
    {
        return $this->hasMany(Prescription::class);
    }

    protected static function booted(): void
    {
        static::creating(function (Patient $patient) {
            if (blank($patient->code)) {
                $patient->code = app(GeneratePatientCodeAction::class)->execute();
            }
        });
    }
}
