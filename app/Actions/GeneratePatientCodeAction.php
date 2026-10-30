<?php

namespace App\Actions;

use App\Models\Patient;
use Illuminate\Support\Facades\DB;

final class GeneratePatientCodeAction
{
    public function execute(): string
    {
        return DB::transaction(function () {
            $year = now()->format('Y');
            $prefix = "PAT-{$year}-";

            $latestPatient = Patient::where('code', 'like', "{$prefix}%")
                ->lockForUpdate()
                ->latest('id')
                ->first();

            if (! $latestPatient) {
                return $prefix.'0001';
            }

            $lastNumber = (int) str_replace($prefix, '', $latestPatient->code);
            $nextNumber = str_pad((string) ($lastNumber + 1), 4, '0', STR_PAD_LEFT);

            return "{$prefix}{$nextNumber}";
        });
    }
}
