<!DOCTYPE html>
<html lang="ar" dir="rtl">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>@yield('title', 'عيادة د. كود | حجز المواعيد')</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800&display=swap" rel="stylesheet">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-slate-50 font-sans text-slate-900 antialiased selection:bg-teal-100 selection:text-teal-900">
        <header class="no-print sticky top-0 z-30 border-b border-slate-200/80 bg-white/90 backdrop-blur">
            <div class="mx-auto flex max-w-6xl items-center justify-between px-4 py-3.5 sm:px-6 lg:px-8">
                <a href="{{ route('home') }}" class="flex items-center gap-3">
                    <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-teal-600 text-white shadow-sm shadow-teal-600/20">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                    </span>
                    <div>
                        <p class="text-base font-extrabold leading-tight text-slate-900">عيادة د. كود</p>
                        <p class="text-xs font-medium text-teal-700">بوابة حجز المواعيد الإلكترونية</p>
                    </div>
                </a>

                <nav class="flex items-center gap-2 sm:gap-3">
                    <a
                        href="{{ route('home') }}"
                        class="rounded-lg px-3 py-2 text-sm font-semibold transition {{ request()->routeIs('home') ? 'bg-teal-50 text-teal-700' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}"
                    >
                        حجز موعد
                    </a>
                    <a
                        href="{{ route('appointments.track') }}"
                        class="rounded-lg px-3 py-2 text-sm font-semibold transition {{ request()->routeIs('appointments.track') ? 'bg-teal-50 text-teal-700' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}"
                    >
                        متابعة الحجز
                    </a>
                    <a
                        href="{{ url('/admin') }}"
                        class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-3.5 py-2 text-sm font-semibold text-slate-700 shadow-2xs transition hover:border-teal-300 hover:text-teal-700"
                    >
                        <span>بوابة الإدارة</span>
                    </a>
                </nav>
            </div>
        </header>

        <main class="mx-auto max-w-6xl px-4 py-8 sm:px-6 lg:px-8 lg:py-10">
            @if (session('success'))
                <div class="mb-6 flex items-center gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-800">
                    <svg class="h-5 w-5 shrink-0 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 flex items-center gap-3 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-semibold text-rose-800">
                    <svg class="h-5 w-5 shrink-0 text-rose-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                    </svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @yield('content')
        </main>

        <footer class="no-print mt-12 border-t border-slate-200 bg-white">
            <div class="mx-auto flex max-w-6xl flex-col items-center justify-between gap-2 px-4 py-6 text-xs text-slate-500 sm:flex-row sm:px-6 lg:px-8">
                <p>&copy; {{ now()->year }} عيادة د. كود — جميع الحقوق محفوظة.</p>
                <p>الحجوزات الإلكترونية تصل مباشرةً إلى مكتب الاستقبال والإدارة.</p>
            </div>
        </footer>
    </body>
</html>
