<?php

namespace App\Http\Controllers;

use App\Models\Prescription;
use Mpdf\Mpdf;
use Mpdf\Output\Destination;
use Symfony\Component\HttpFoundation\Response;

class PrintPrescriptionController extends Controller
{
    public function __invoke(Prescription $prescription): Response
    {
        $prescription->load([
            'patient',
            'consultation.appointment',
            'items',
        ]);

        $html = view('prescriptions.print', [
            'prescription' => $prescription,
        ])->render();

        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A5',
            'orientation' => 'P',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 10,
            'margin_bottom' => 10,
            'autoArabic' => true,
        ]);

        $mpdf->WriteHTML($html);

        $fileName = "prescription-{$prescription->patient->code}-{$prescription->id}.pdf";
        $pdfContent = $mpdf->Output('', Destination::STRING_RETURN);

        return response($pdfContent, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => "inline; filename=\"{$fileName}\"",
        ]);
    }
}
