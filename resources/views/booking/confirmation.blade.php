@extends('layouts.clinic')

@section('title', 'تأكيد الحجز | عيادة د. كود')

@section('content')
    <div class="mx-auto max-w-2xl">
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="bg-gradient-to-l from-teal-700 to-emerald-600 px-6 py-8 text-center text-white">
                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-white/20 backdrop-blur">
                    <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                    </svg>
                </div>
                <h1 class="mt-4 text-2xl font-extrabold">تم استلام حجزك بنجاح!</h1>
                <p class="mt-1.5 text-sm text-teal-50">وصل الحجز مباشرةً إلى إدارة العيادة والاستقبال.</p>
            </div>

            <div class="p-6 sm:p-8">
                <div class="mb-6 grid gap-4 rounded-xl border border-teal-100 bg-teal-50/60 p-5 text-center sm:grid-cols-2">
                    <div>
                        <p class="text-xs font-semibold text-teal-800">رقم الدور</p>
                        <p class="mt-1 text-4xl font-extrabold text-teal-700">#{{ $appointment->queue_number }}</p>
                    </div>
                    <div class="border-t border-teal-200/70 pt-4 sm:border-r sm:border-t-0 sm:pr-4 sm:pt-0">
                        <p class="text-xs font-semibold text-teal-800">كود المريض</p>
                        <p class="mt-2 font-mono text-lg font-extrabold text-slate-900" dir="ltr">{{ $appointment->patient->code }}</p>
                    </div>
                </div>

                <dl class="divide-y divide-slate-100 text-sm">
                    <div class="flex items-center justify-between py-3">
                        <dt class="text-slate-500">اسم المريض</dt>
                        <dd class="font-bold text-slate-900">{{ $appointment->patient->name }}</dd>
                    </div>
                    <div class="flex items-center justify-between py-3">
                        <dt class="text-slate-500">رقم الهاتف</dt>
                        <dd class="font-bold text-slate-900" dir="ltr">{{ $appointment->patient->phone }}</dd>
                    </div>
                    <div class="flex items-center justify-between py-3">
                        <dt class="text-slate-500">تاريخ الكشف</dt>
                        <dd class="font-bold text-slate-900">{{ $appointment->appointment_date->format('Y-m-d') }}</dd>
                    </div>
                    <div class="flex items-center justify-between py-3">
                        <dt class="text-slate-500">نوع الكشف</dt>
                        <dd class="font-bold text-slate-900">{{ $appointment->type->getLabel() }}</dd>
                    </div>
                    <div class="flex items-center justify-between py-3">
                        <dt class="text-slate-500">قيمة الكشف</dt>
                        <dd class="font-bold text-teal-700">{{ number_format((float) $appointment->fee, 2) }} ج.م</dd>
                    </div>
                    <div class="flex items-center justify-between py-3">
                        <dt class="text-slate-500">حالة الحجز</dt>
                        <dd>
                            <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-slate-800">
                                {{ $appointment->status->getLabel() }}
                            </span>
                        </dd>
                    </div>
                    @if ($appointment->notes)
                        <div class="py-3">
                            <dt class="mb-1 text-slate-500">ملاحظات الحجز</dt>
                            <dd class="rounded-lg bg-slate-50 p-3 text-slate-700">{{ $appointment->notes }}</dd>
                        </div>
                    @endif
                </dl>

                <div class="no-print mt-8 flex flex-wrap items-center justify-between gap-3 border-t border-slate-100 pt-6">
                    <div class="flex flex-wrap gap-2">
                        <button
                            type="button"
                            onclick="window.print()"
                            class="inline-flex cursor-pointer items-center gap-2 rounded-xl bg-teal-600 px-4 py-2.5 text-sm font-bold text-white transition hover:bg-teal-700"
                        >
                            طباعة تذكرة الحجز
                        </button>
                        <a
                            href="{{ route('appointments.track', ['query' => $appointment->patient->phone]) }}"
                            class="inline-flex items-center rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-bold text-slate-700 transition hover:bg-slate-50"
                        >
                            متابعة الحجز
                        </a>
                        <a
                            href="{{ route('home') }}"
                            class="inline-flex items-center rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-600 transition hover:bg-slate-50"
                        >
                            حجز موعد آخر
                        </a>
                    </div>

                    @if ($cancelUrl)
                        <form method="POST" action="{{ $cancelUrl }}" onsubmit="return confirm('هل أنت متأكد من رغبتك في إلغاء هذا الحجز؟');">
                            @csrf
                            <button
                                type="submit"
                                class="cursor-pointer text-xs font-bold text-rose-600 hover:text-rose-700 hover:underline"
                            >
                                إلغاء الحجز
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
