@extends('layouts.clinic')

@section('title', 'متابعة الحجز | عيادة د. كود')

@section('content')
    <div class="mx-auto max-w-3xl">
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-xs sm:p-8">
            <h1 class="text-2xl font-extrabold text-slate-900">متابعة حالة الحجز</h1>
            <p class="mt-1 text-sm text-slate-500">أدخل رقم الهاتف أو كود المريض (مثل PAT-2026-0001) لعرض مواعيدك وحالتها.</p>

            <form method="GET" action="{{ route('appointments.track') }}" class="mt-5 flex flex-col gap-3 sm:flex-row">
                <input
                    type="text"
                    name="query"
                    value="{{ $query }}"
                    placeholder="رقم الهاتف أو كود المريض..."
                    required
                    class="flex-1 rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-2xs transition focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20"
                >
                <button
                    type="submit"
                    class="cursor-pointer rounded-xl bg-teal-600 px-6 py-3 text-sm font-extrabold text-white shadow-xs transition hover:bg-teal-700"
                >
                    بحث عن الحجوزات
                </button>
            </form>
        </div>

        @if ($query !== '')
            @if ($patient)
                <div class="mt-6 rounded-2xl border border-slate-200 bg-white p-6 shadow-xs">
                    <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-100 pb-4">
                        <div>
                            <p class="text-lg font-extrabold text-slate-900">{{ $patient->name }}</p>
                            <p class="text-xs text-slate-500">
                                كود المريض: <span class="font-mono font-bold text-slate-700" dir="ltr">{{ $patient->code }}</span>
                                • الهاتف: <span dir="ltr">{{ $patient->phone }}</span>
                            </p>
                        </div>
                        <a
                            href="{{ route('home') }}"
                            class="rounded-xl bg-teal-50 px-4 py-2 text-xs font-bold text-teal-700 transition hover:bg-teal-100"
                        >
                            + حجز موعد جديد
                        </a>
                    </div>

                    @if ($appointments->isEmpty())
                        <p class="py-8 text-center text-sm text-slate-500">لا توجد حجوزات مسجلة لهذا الملف حتى الآن.</p>
                    @else
                        <div class="mt-4 space-y-3">
                            @foreach ($appointments as $item)
                                @php
                                    /** @var \App\Models\Appointment $appointment */
                                    $appointment = $item['appointment'];
                                    $badgeClasses = match ($appointment->status->getColor()) {
                                        'warning' => 'bg-amber-100 text-amber-800',
                                        'info' => 'bg-sky-100 text-sky-800',
                                        'success' => 'bg-emerald-100 text-emerald-800',
                                        'danger' => 'bg-rose-100 text-rose-800',
                                        default => 'bg-slate-100 text-slate-700',
                                    };
                                @endphp
                                <div class="flex flex-col justify-between gap-4 rounded-xl border border-slate-200 p-4 sm:flex-row sm:items-center">
                                    <div class="flex items-center gap-4">
                                        <div class="flex h-12 w-12 shrink-0 flex-col items-center justify-center rounded-xl bg-teal-50 text-teal-700">
                                            <span class="text-[10px] font-bold">الدور</span>
                                            <span class="text-lg font-extrabold leading-none">#{{ $appointment->queue_number }}</span>
                                        </div>
                                        <div>
                                            <div class="flex flex-wrap items-center gap-2">
                                                <span class="font-bold text-slate-900">{{ $appointment->appointment_date->format('Y-m-d') }}</span>
                                                <span class="rounded-full px-2.5 py-0.5 text-xs font-bold {{ $badgeClasses }}">
                                                    {{ $appointment->status->getLabel() }}
                                                </span>
                                            </div>
                                            <p class="mt-1 text-xs text-slate-500">
                                                {{ $appointment->type->getLabel() }}
                                                • {{ number_format((float) $appointment->fee, 0) }} ج.م
                                                • {{ $appointment->is_paid ? 'تم السداد' : 'السداد في العيادة' }}
                                            </p>
                                            @if ($appointment->notes)
                                                <p class="mt-1 text-xs text-slate-600">{{ $appointment->notes }}</p>
                                            @endif
                                        </div>
                                    </div>

                                    @if ($item['cancelUrl'])
                                        <form method="POST" action="{{ $item['cancelUrl'] }}" onsubmit="return confirm('هل أنت متأكد من إلغاء هذا الحجز؟');">
                                            @csrf
                                            <button
                                                type="submit"
                                                class="cursor-pointer rounded-lg border border-rose-200 px-3 py-1.5 text-xs font-bold text-rose-600 transition hover:bg-rose-50"
                                            >
                                                إلغاء الحجز
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            @else
                <div class="mt-6 rounded-2xl border border-slate-200 bg-white p-8 text-center">
                    <p class="text-base font-bold text-slate-800">لم نجد حجوزات مطابقة للبحث "{{ $query }}"</p>
                    <p class="mt-1 text-sm text-slate-500">تأكد من رقم الهاتف أو كود المريض، أو قم بعمل حجز جديد الآن.</p>
                    <a
                        href="{{ route('home') }}"
                        class="mt-4 inline-flex items-center rounded-xl bg-teal-600 px-5 py-2.5 text-sm font-bold text-white transition hover:bg-teal-700"
                    >
                        حجز موعد جديد
                    </a>
                </div>
            @endif
        @endif
    </div>
@endsection
