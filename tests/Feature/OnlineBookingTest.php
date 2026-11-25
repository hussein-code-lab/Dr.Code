<?php

use App\Enums\AppointmentSource;
use App\Enums\AppointmentStatus;
use App\Enums\AppointmentType;
use App\Enums\Gender;
use App\Models\Admin;
use App\Models\Appointment;
use App\Models\Patient;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\URL;

uses(RefreshDatabase::class);

it('renders the public online booking page', function (): void {
    $response = $this->get(route('home'));

    $response->assertSuccessful()
        ->assertSee('عيادة د. كود')
        ->assertSee('نموذج حجز موعد جديد')
        ->assertSee(AppointmentType::Examination->getLabel());
});

it('allows a new patient to book an appointment online and notifies admins', function (): void {
    $admin = Admin::factory()->create();
    $bookingDate = today()->addDay()->toDateString();

    $response = $this->post(route('appointments.book'), [
        'name' => 'أحمد محمود حسن',
        'phone' => '01012345678',
        'gender' => Gender::Male->value,
        'birth_date' => '1995-05-15',
        'appointment_date' => $bookingDate,
        'type' => AppointmentType::Examination->value,
        'notes' => 'صداع مستمر منذ يومين',
    ]);

    $appointment = Appointment::with('patient')->first();

    expect($appointment)->not->toBeNull()
        ->and($appointment->patient->name)->toBe('أحمد محمود حسن')
        ->and($appointment->patient->phone)->toBe('01012345678')
        ->and($appointment->patient->code)->toStartWith('PAT-')
        ->and($appointment->queue_number)->toBe(1)
        ->and($appointment->status)->toBe(AppointmentStatus::Booked)
        ->and($appointment->source)->toBe(AppointmentSource::Online)
        ->and((float) $appointment->fee)->toBe(AppointmentType::Examination->defaultFee())
        ->and($appointment->notes)->toBe('صداع مستمر منذ يومين');

    $response->assertRedirect();

    $confirmationResponse = $this->get($response->headers->get('Location'));
    $confirmationResponse->assertSuccessful()
        ->assertSee('تم استلام حجزك بنجاح!')
        ->assertSee('#1')
        ->assertSee($appointment->patient->code)
        ->assertSee('صداع مستمر منذ يومين');

    expect($admin->fresh()->notifications()->count())->toBe(1);
    expect($admin->fresh()->notifications()->first()->data['title'])->toBe('حجز إلكتروني جديد');
});

it('reuses existing patient record by phone and increments queue number for same day', function (): void {
    $existingPatient = Patient::create([
        'name' => 'سارة علي',
        'phone' => '01099887766',
        'gender' => Gender::Female,
    ]);

    $otherPatient = Patient::create([
        'name' => 'محمد إبراهيم',
        'phone' => '01122334455',
        'gender' => Gender::Male,
    ]);

    $bookingDate = today()->addDays(2)->toDateString();

    Appointment::create([
        'patient_id' => $otherPatient->id,
        'appointment_date' => $bookingDate,
        'queue_number' => 1,
        'type' => AppointmentType::Examination,
        'status' => AppointmentStatus::Booked,
        'source' => AppointmentSource::Reception,
        'fee' => 300,
    ]);

    $this->post(route('appointments.book'), [
        'name' => 'سارة علي',
        'phone' => '010 9988-7766',
        'gender' => Gender::Female->value,
        'appointment_date' => $bookingDate,
        'type' => AppointmentType::FollowUp->value,
    ])->assertRedirect();

    expect(Patient::count())->toBe(2);

    $newAppointment = Appointment::where('patient_id', $existingPatient->id)->first();

    expect($newAppointment)->not->toBeNull()
        ->and($newAppointment->queue_number)->toBe(2)
        ->and($newAppointment->source)->toBe(AppointmentSource::Online)
        ->and((float) $newAppointment->fee)->toBe(AppointmentType::FollowUp->defaultFee());
});

it('prevents duplicate active booking for the same patient on the same date', function (): void {
    $bookingDate = today()->addDay()->toDateString();

    $this->post(route('appointments.book'), [
        'name' => 'خالد سمير',
        'phone' => '01234567890',
        'gender' => Gender::Male->value,
        'appointment_date' => $bookingDate,
        'type' => AppointmentType::Consultation->value,
    ])->assertRedirect();

    $this->post(route('appointments.book'), [
        'name' => 'خالد سمير',
        'phone' => '01234567890',
        'gender' => Gender::Male->value,
        'appointment_date' => $bookingDate,
        'type' => AppointmentType::Consultation->value,
    ])->assertSessionHasErrors('appointment_date');

    expect(Appointment::count())->toBe(1);
});

it('validates online booking request fields', function (): void {
    $this->post(route('appointments.book'), [
        'name' => 'أ',
        'phone' => 'invalid',
        'gender' => 'unknown',
        'appointment_date' => today()->subDay()->toDateString(),
        'type' => 'invalid',
    ])->assertSessionHasErrors(['name', 'phone', 'gender', 'appointment_date', 'type']);
});

it('allows a patient to track and cancel a booked appointment', function (): void {
    $admin = Admin::factory()->create();
    $patient = Patient::create([
        'name' => 'منى طارق',
        'phone' => '01555666777',
        'gender' => Gender::Female,
    ]);

    $appointment = Appointment::create([
        'patient_id' => $patient->id,
        'appointment_date' => today()->addDay()->toDateString(),
        'queue_number' => 1,
        'type' => AppointmentType::Examination,
        'status' => AppointmentStatus::Booked,
        'source' => AppointmentSource::Online,
        'fee' => 300,
    ]);

    $this->get(route('appointments.track', ['query' => $patient->phone]))
        ->assertSuccessful()
        ->assertSee('منى طارق')
        ->assertSee($patient->code)
        ->assertSee('محجوز');

    $cancelUrl = URL::temporarySignedRoute(
        'appointments.cancel',
        now()->addDay(),
        ['appointment' => $appointment],
    );

    $this->post($cancelUrl)
        ->assertRedirect(route('appointments.track', ['query' => $patient->phone]));

    expect($appointment->fresh()->status)->toBe(AppointmentStatus::Cancelled);
    expect($admin->fresh()->notifications()->count())->toBe(1);
    expect($admin->fresh()->notifications()->first()->data['title'])->toBe('إلغاء حجز بواسطة المريض');
});

it('rejects unsigned confirmation and cancel links', function (): void {
    $patient = Patient::create([
        'name' => 'يوسف عادل',
        'phone' => '01033445566',
        'gender' => Gender::Male,
    ]);

    $appointment = Appointment::create([
        'patient_id' => $patient->id,
        'appointment_date' => today()->toDateString(),
        'queue_number' => 1,
        'type' => AppointmentType::Examination,
        'status' => AppointmentStatus::Booked,
        'source' => AppointmentSource::Online,
        'fee' => 300,
    ]);

    $this->get("/appointments/confirmation/{$appointment->id}")->assertForbidden();
    $this->post("/appointments/{$appointment->id}/cancel")->assertForbidden();
});

it('displays online bookings inside the admin panel appointments list', function (): void {
    $admin = Admin::factory()->create();

    $this->post(route('appointments.book'), [
        'name' => 'نورهان جمال',
        'phone' => '01077889900',
        'gender' => Gender::Female->value,
        'appointment_date' => today()->toDateString(),
        'type' => AppointmentType::Examination->value,
        'notes' => 'كشف باطنة',
    ])->assertRedirect();

    $this->actingAs($admin, 'admin')
        ->get('/admin/appointments')
        ->assertSuccessful()
        ->assertSee('نورهان جمال')
        ->assertSee('حجز إلكتروني');
});
