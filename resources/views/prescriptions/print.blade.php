<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>روشتة علاجية - {{ $prescription->patient->name }}</title>
    <style>
        @page {
            header: page-header;
            footer: page-footer;
            margin-top: 35mm;
            margin-bottom: 25mm;
            margin-left: 12mm;
            margin-right: 12mm;
        }

        body {
            font-family: 'cairo', 'tajawal', sans-serif;
            direction: rtl;
            text-align: right;
            font-size: 11pt;
            color: #1e293b;
            line-height: 1.5;
        }

        /* رأس الصفحة الثابت */
        .header-table {
            width: 100%;
            border-bottom: 2px solid #0284c7;
            padding-bottom: 8px;
        }

        .doctor-name {
            font-size: 16pt;
            font-weight: bold;
            color: #0369a1;
        }

        .doctor-spec {
            font-size: 10pt;
            color: #64748b;
        }

        /* صندوق بيانات المريض المنظم */
        .patient-card {
            width: 100%;
            border: 1px solid #cbd5e1;
            background-color: #f8fafc;
            border-radius: 6px;
            margin-bottom: 15px;
            padding: 6px 10px;
        }

        .patient-card table {
            width: 100%;
            border-collapse: collapse;
        }

        .patient-card td {
            padding: 3px 6px;
            font-size: 9.5pt;
        }

        /* علامة Rx الطبية الكلاسيكية */
        .rx-symbol {
            font-family: 'DejaVu Serif', serif;
            font-size: 24pt;
            font-weight: bold;
            font-style: italic;
            color: #0284c7;
            margin-bottom: 5px;
            line-height: 1;
        }

        /* جدول الأدوية المنظم بدقة */
        .rx-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        .rx-table th {
            background-color: #f1f5f9;
            border-bottom: 1.5px solid #94a3b8;
            padding: 6px 8px;
            font-size: 9.5pt;
            font-weight: bold;
            color: #334155;
            text-align: right;
        }

        .rx-table td {
            padding: 8px 8px;
            border-bottom: 1px dashed #e2e8f0;
            vertical-align: middle;
            font-size: 9.5pt;
        }

        .med-name {
            font-weight: bold;
            font-size: 10.5pt;
            color: #0f172a;
            direction: ltr;
            text-align: right;
            display: inline-block;
        }

        .med-instructions {
            font-size: 8.5pt;
            color: #64748b;
            margin-top: 2px;
        }

        /* ملاحظات الطبيب والإرشادات */
        .advice-section {
            border: 1px dashed #0284c7;
            background-color: #f0f9ff;
            border-radius: 4px;
            padding: 8px 12px;
            font-size: 9pt;
            color: #0369a1;
            margin-top: 10px;
        }

        /* تذييل الصفحة */
        .footer-table {
            width: 100%;
            border-top: 1px solid #e2e8f0;
            padding-top: 6px;
            font-size: 8pt;
            color: #94a3b8;
            text-align: center;
        }
    </style>
</head>

<body>

    <!-- الهيدر المخصص لـ mPDF -->
    <htmlpageheader name="page-header">
        <table class="header-table">
            <tr>
                <td style="text-align: right;">
                    <div class="doctor-name">عيادة دكتور / ........................</div>
                    <div class="doctor-spec">أخصائي الطب والجراحة العامة</div>
                </td>
                <td style="text-align: left; vertical-align: bottom; font-size: 9pt; color: #64748b;">
                    تاريخ الكشف: {{ $prescription->created_at->format('Y/m/d') }}
                </td>
            </tr>
        </table>
    </htmlpageheader>

    <!-- الفوتر المخصص لـ mPDF -->
    <htmlpagefooter name="page-footer">
        <div class="footer-table">
            مع تمنياتنا لكم بالشفاء العاجل | العنوان: ...................... | هاتف: ......................
        </div>
    </htmlpagefooter>

    <!-- بيانات المريض (Grid منظم بجداول سليمة لـ mPDF) -->
    <div class="patient-card">
        <table>
            <tr>
                <td width="45%"><strong>المريض:</strong> {{ $prescription->patient->name }}</td>
                <td width="30%"><strong>الكود:</strong> {{ $prescription->patient->code }}</td>
                <td width="25%"><strong>الدور:</strong>
                    #{{ $prescription->consultation?->appointment?->queue_number ?? 1 }}</td>
            </tr>
            <tr>
                @php
                    $age = $prescription->patient->birth_date
                        ? \Carbon\Carbon::parse($prescription->patient->birth_date)->age . ' سنة'
                        : '—';
                @endphp
                <td><strong>السن:</strong> {{ $age }}</td>
                <td><strong>النوع:</strong> {{ $prescription->patient->gender?->getLabel() ?? '—' }}</td>
                <td><strong>فصيلة الدم:</strong> {{ $prescription->patient->blood_group?->value ?? '—' }}</td>
            </tr>
        </table>
    </div>

    <!-- علامة الروشتة الطبية -->
    <div class="rx-symbol">℞</div>

    <!-- قائمة الأدوية -->
    <table class="rx-table">
        <thead>
            <tr>
                <th width="42%">اسم العلاج والتعليمات</th>
                <th width="18%">الجرعة</th>
                <th width="20%">معدل التكرار</th>
                <th width="20%">المدة المقررة</th>
            </tr>
        </thead>
        <tbody>
            @forelse($prescription->items as $item)
                <tr>
                    <td>
                        <div class="med-name">{{ $item->medicine_name }}</div>
                        @if ($item->instructions)
                            <div class="med-instructions">({{ $item->instructions }})</div>
                        @endif
                    </td>
                    <td>{{ $item->dosage }}</td>
                    <td>{{ $item->frequency }}</td>
                    <td>{{ $item->duration }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="text-align: center; color: #94a3b8; padding: 15px;">
                        لا توجد أدوية مسجلة في هذه الروشتة.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- إرشادات ونصائح إضافية -->
    @if ($prescription->general_advice)
        <div class="advice-section">
            <strong>إرشادات ونصائح طبية:</strong> {{ $prescription->general_advice }}
        </div>
    @endif

</body>

</html>
