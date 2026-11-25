@extends('layouts.clinic')

@section('title', 'عيادة د. كود | احجز موعدك أونلاين')

@section('content')
    <section class="mb-8 overflow-hidden rounded-2xl bg-gradient-to-l from-teal-700 via-teal-600 to-emerald-600 p-6 text-white shadow-lg shadow-teal-900/10 sm:p-8 lg:p-10">
        <div class="grid items-center gap-6 lg:grid-cols-12">
            <div class="lg:col-span-8">
                <span class="inline-flex items-center gap-2 rounded-full bg-white/15 px-3.5 py-1 text-xs font-bold tracking-wide text-teal-50 backdrop-blur">
                    <span class="h-2 w-2 rounded-full bg-emerald-300"></span>
                    حجز إلكتروني فوري ومباشر مع العيادة
                </span>
                <h1 class="mt-4 text-2xl font-extrabold leading-snug sm:text-3xl lg:text-4xl">
                    احجز موعدك الطبي في دقائق واحصل على رقم دورك فوراً
                </h1>
                <p class="mt-3 max-w-2xl text-sm leading-relaxed text-teal-50/90 sm:text-base">
                    سجّل بياناتك واختر اليوم ونوع الكشف المناسب، وسيصل حجزك مباشرةً إلى إدارة العيادة والاستقبال لتنظيم دورك وتجهيز ملفك الطبي قبل وصولك.
                </p>
            </div>

            <div class="lg:col-span-4">
                <div class="rounded-xl border border-white/20 bg-white/10 p-4 backdrop-blur">
                    <div class="flex items-center justify-between border-b border-white/15 pb-3">
                        <span class="text-xs font-semibold text-teal-100">حجوزات اليوم حتى الآن</span>
                        <span class="rounded-full bg-white/20 px-2.5 py-0.5 text-sm font-extrabold text-white">{{ $todayBookedCount }} حجز</span>
                    </div>
                    <div class="mt-3 space-y-2 text-xs text-teal-50">
                        <p class="flex items-center justify-between">
                            <span>مواعيد العمل</span>
                            <span class="font-bold">السبت – الخميس | 10 ص – 10 م</span>
                        </p>
                        <p class="flex items-center justify-between">
                            <span>تأكيد الحجز</span>
                            <span class="font-bold">فوري برقم دور وكود مريض</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="grid gap-8 lg:grid-cols-12">
        <div class="lg:col-span-8">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-xs sm:p-8">
                <div class="mb-6 border-b border-slate-100 pb-4">
                    <h2 class="text-xl font-extrabold text-slate-900">نموذج حجز موعد جديد</h2>
                    <p class="mt-1 text-sm text-slate-500">يرجى إدخال البيانات بدقة ليتم ربط الحجز بملفك الطبي تلقائياً.</p>
                </div>

                @if ($errors->any())
                    <div class="mb-6 rounded-xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-800">
                        <p class="font-bold">يرجى مراجعة البيانات التالية:</p>
                        <ul class="mt-2 list-inside list-disc space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('appointments.book') }}" class="space-y-6">
                    @csrf

                    <div>
                        <label class="mb-3 block text-sm font-bold text-slate-800">
                            اختر نوع الكشف <span class="text-rose-500">*</span>
                        </label>
                        <div class="grid gap-3 sm:grid-cols-3">
                            @foreach ($appointmentTypes as $type)
                                @php
                                    $isSelected = old('type', \App\Enums\AppointmentType::Examination->value) === $type->value;
                                @endphp
                                <label class="relative flex cursor-pointer flex-col justify-between rounded-xl border p-4 transition {{ $isSelected ? 'border-teal-600 bg-teal-50/60 ring-2 ring-teal-600/20' : 'border-slate-200 bg-white hover:border-teal-300' }}">
                                    <input
                                        type="radio"
                                        name="type"
                                        value="{{ $type->value }}"
                                        class="peer sr-only"
                                        @checked($isSelected)
                                        required
                                    >
                                    <div>
                                        <div class="flex items-center justify-between gap-2">
                                            <span class="text-sm font-extrabold text-slate-900">{{ $type->getLabel() }}</span>
                                            <span class="rounded-full bg-teal-100 px-2.5 py-0.5 text-xs font-extrabold text-teal-800">
                                                {{ number_format($type->defaultFee(), 0) }} ج.م
                                            </span>
                                        </div>
                                        <p class="mt-2 text-xs leading-relaxed text-slate-500">{{ $type->getDescription() }}</p>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="grid gap-5 sm:grid-cols-2">
                        <div>
                            <label for="name" class="mb-1.5 block text-sm font-bold text-slate-800">
                                اسم المريض بالكامل <span class="text-rose-500">*</span>
                            </label>
                            <input
                                id="name"
                                name="name"
                                type="text"
                                value="{{ old('name') }}"
                                placeholder="مثال: أحمد محمد علي"
                                required
                                class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 shadow-2xs transition focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20"
                            >
                        </div>

                        <div>
                            <label for="phone" class="mb-1.5 block text-sm font-bold text-slate-800">
                                رقم الهاتف <span class="text-rose-500">*</span>
                            </label>
                            <input
                                id="phone"
                                name="phone"
                                type="tel"
                                dir="ltr"
                                value="{{ old('phone') }}"
                                placeholder="01012345678"
                                required
                                class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-right text-sm text-slate-900 shadow-2xs transition focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20"
                            >
                        </div>

                        <div>
                            <label for="gender" class="mb-1.5 block text-sm font-bold text-slate-800">
                                النوع <span class="text-rose-500">*</span>
                            </label>
                            <select
                                id="gender"
                                name="gender"
                                required
                                class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 shadow-2xs transition focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20"
                            >
                                <option value="" disabled @selected(! old('gender'))>اختر النوع</option>
                                @foreach ($genders as $gender)
                                    <option value="{{ $gender->value }}" @selected(old('gender') === $gender->value)>
                                        {{ $gender->getLabel() }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="birth_date" class="mb-1.5 block text-sm font-bold text-slate-800">
                                تاريخ الميلاد <span class="text-xs font-normal text-slate-400">(اختياري)</span>
                            </label>
                            <input
                                id="birth_date"
                                name="birth_date"
                                type="date"
                                max="{{ today()->toDateString() }}"
                                value="{{ old('birth_date') }}"
                                class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 shadow-2xs transition focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20"
                            >
                        </div>
                    </div>

                    <div>
                        <label for="appointment_date" class="mb-1.5 block text-sm font-bold text-slate-800">
                            تاريخ الحجز المطلوب <span class="text-rose-500">*</span>
                        </label>
                        <input
                            id="appointment_date"
                            name="appointment_date"
                            type="date"
                            min="{{ today()->toDateString() }}"
                            max="{{ today()->addDays(60)->toDateString() }}"
                            value="{{ old('appointment_date', today()->toDateString()) }}"
                            required
                            class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 shadow-2xs transition focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20 sm:max-w-xs"
                        >
                    </div>

                    <div>
                        <label for="notes" class="mb-1.5 block text-sm font-bold text-slate-800">
                            الشكوى أو ملاحظات للطبيب <span class="text-xs font-normal text-slate-400">(اختياري)</span>
                        </label>
                        <textarea
                            id="notes"
                            name="notes"
                            rows="3"
                            maxlength="500"
                            placeholder="اكتب باختصار سبب الزيارة أو أي ملاحظات تود إيصالها للعيادة..."
                            class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 shadow-2xs transition focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20"
                        >{{ old('notes') }}</textarea>
                    </div>

                    <div class="flex flex-col gap-3 border-t border-slate-100 pt-5 sm:flex-row sm:items-center sm:justify-between">
                        <p class="text-xs text-slate-500">
                            الدفع يتم في العيادة عند الحضور، ويصلك رقم الدور فور تأكيد الحجز.
                        </p>
                        <button
                            type="submit"
                            class="inline-flex cursor-pointer items-center justify-center gap-2 rounded-xl bg-teal-600 px-6 py-3 text-sm font-extrabold text-white shadow-sm shadow-teal-600/25 transition hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2"
                        >
                            <span>تأكيد الحجز الآن</span>
                            <svg class="h-4 w-4 rotate-180" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                            </svg>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <aside class="space-y-6 lg:col-span-4">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-xs">
                <h3 class="text-base font-extrabold text-slate-900">كيف يعمل الحجز الإلكتروني؟</h3>
                <ol class="mt-4 space-y-4 text-sm text-slate-600">
                    <li class="flex gap-3">
                        <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-teal-100 text-xs font-extrabold text-teal-800">1</span>
                        <span>أدخل بياناتك واختر نوع الكشف وتاريخ الزيارة المناسب لك.</span>
                    </li>
                    <li class="flex gap-3">
                        <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-teal-100 text-xs font-extrabold text-teal-800">2</span>
                        <span>يصل حجزك فوراً إلى لوحة الإدارة مع إشعار مباشر لموظف الاستقبال.</span>
                    </li>
                    <li class="flex gap-3">
                        <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-teal-100 text-xs font-extrabold text-teal-800">3</span>
                        <span>احتفظ برقم الدور وكود المريض لتقديمه عند الوصول للعيادة.</span>
                    </li>
                </ol>
            </div>

            <div class="rounded-2xl border border-teal-200 bg-teal-50/70 p-6">
                <h3 class="text-base font-extrabold text-teal-950">لديك حجز مسبق بالفعل؟</h3>
                <p class="mt-2 text-sm leading-relaxed text-teal-900/80">
                    يمكنك الاستعلام عن موعدك ورقم دورك وحالة الكشف في أي وقت باستخدام رقم هاتفك أو كود المريض.
                </p>
                <a
                    href="{{ route('appointments.track') }}"
                    class="mt-4 inline-flex w-full items-center justify-center rounded-xl border border-teal-600 bg-white px-4 py-2.5 text-sm font-bold text-teal-700 transition hover:bg-teal-600 hover:text-white"
                >
                    متابعة حالة الحجز
                </a>
            </div>
        </aside>
    </div>

    <script>
        document.querySelectorAll('input[name="type"]').forEach((radio) => {
            radio.addEventListener('change', () => {
                document.querySelectorAll('input[name="type"]').forEach((item) => {
                    const label = item.closest('label');
                    if (item.checked) {
                        label.classList.add('border-teal-600', 'bg-teal-50/60', 'ring-2', 'ring-teal-600/20');
                        label.classList.remove('border-slate-200', 'bg-white');
                    } else {
                        label.classList.remove('border-teal-600', 'bg-teal-50/60', 'ring-2', 'ring-teal-600/20');
                        label.classList.add('border-slate-200', 'bg-white');
                    }
                });
            });
        });
    </script>
@endsection
