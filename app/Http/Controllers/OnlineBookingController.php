<?php

namespace App\Http\Controllers;

use App\Actions\CreateOnlineBookingAction;
use App\Enums\AppointmentStatus;
use App\Enums\AppointmentType;
use App\Enums\Gender;
use App\Filament\Resources\Appointments\AppointmentResource;
use App\Http\Requests\StoreOnlineBookingRequest;
use App\Models\Admin;
use App\Models\Appointment;
use App\Models\Patient;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\View\View;

class OnlineBookingController extends Controller
{
    public function index(): View
    {
        $todayBookedCount = Appointment::whereDate('appointment_date', today())
            ->whereNotIn('status', [AppointmentStatus::Cancelled, AppointmentStatus::NoShow])
            ->count();

        return view('booking.index', [
            'appointmentTypes' => AppointmentType::cases(),
            'genders' => Gender::cases(),
            'todayBookedCount' => $todayBookedCount,
        ]);
    }

    public function store(StoreOnlineBookingRequest $request, CreateOnlineBookingAction $action): RedirectResponse
    {
        $appointment = $action->execute($request->validated());

        $confirmationUrl = URL::temporarySignedRoute(
            'appointments.confirmation',
            now()->addDays(7),
            ['appointment' => $appointment],
        );

        return redirect($confirmationUrl);
    }

    public function confirmation(Appointment $appointment): View
    {
        $appointment->load('patient');

        $cancelUrl = $appointment->status === AppointmentStatus::Booked
            ? URL::temporarySignedRoute(
                'appointments.cancel',
                now()->addDays(7),
                ['appointment' => $appointment],
            )
            : null;

        return view('booking.confirmation', [
            'appointment' => $appointment,
            'cancelUrl' => $cancelUrl,
        ]);
    }

    public function track(Request $request): View
    {
        $query = trim((string) $request->query('query', ''));
        $patient = null;
        $appointments = collect();

        if ($query !== '') {
            $normalizedPhone = preg_replace('/[\s\-]+/', '', $query) ?? $query;

            $patient = Patient::where('phone', $normalizedPhone)
                ->orWhere('code', $query)
                ->first();

            if ($patient) {
                $appointments = $patient->appointments()
                    ->latest('appointment_date')
                    ->latest('id')
                    ->limit(15)
                    ->get()
                    ->map(function (Appointment $appointment): array {
                        return [
                            'appointment' => $appointment,
                            'cancelUrl' => $appointment->status === AppointmentStatus::Booked
                                ? URL::temporarySignedRoute(
                                    'appointments.cancel',
                                    now()->addDays(7),
                                    ['appointment' => $appointment],
                                )
                                : null,
                        ];
                    });
            }
        }

        return view('booking.track', [
            'query' => $query,
            'patient' => $patient,
            'appointments' => $appointments,
        ]);
    }

    public function cancel(Appointment $appointment): RedirectResponse
    {
        if ($appointment->status !== AppointmentStatus::Booked) {
            return back()->with('error', 'لا يمكن إلغاء هذا الحجز في حالته الحالية.');
        }

        $appointment->update([
            'status' => AppointmentStatus::Cancelled,
        ]);

        $appointment->load('patient');

        $admins = Admin::all();

        if ($admins->isNotEmpty()) {
            Notification::make()
                ->title('إلغاء حجز بواسطة المريض')
                ->body("قام المريض {$appointment->patient?->name} بإلغاء موعده بتاريخ {$appointment->appointment_date->format('Y-m-d')} (دور #{$appointment->queue_number})")
                ->icon('heroicon-o-x-circle')
                ->warning()
                ->actions([
                    Action::make('view')
                        ->label('عرض الموعد')
                        ->url(AppointmentResource::getUrl('view', ['record' => $appointment], panel: 'admin'))
                        ->markAsRead(),
                ])
                ->sendToDatabase($admins);
        }

        return redirect()
            ->route('appointments.track', ['query' => $appointment->patient?->phone])
            ->with('success', 'تم إلغاء الحجز بنجاح.');
    }
}
