<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Medicine;
use App\Models\Prescription;
use App\Models\TestReport;
use Illuminate\Support\Facades\Mail;

class EmailController extends Controller
{
    public function invoice_email_send($id)
    {
        $invoice = Invoice::with('doctor', 'patient', 'invoice_detail', 'appointment', 'appointment.timeSlot','transaction')
            ->where('id', $id)
            ->first();

        if (! $invoice) {
            return response()->json([
                'isSuccess' => false,
                'message' => 'Detalhes da fatura não encontrados',
            ], 409);
        }

        $verify_mail = $invoice->patient->email;
        if ($verify_mail == null) {
            return response()->json([
                'isSuccess' => false,
                'message' => 'Email não encontrado',
            ], 409); 
        }
        
        $app_name =  config('app.name');
        Mail::send('emails.invoice', ['invoice' => $invoice, 'email' => $verify_mail], function ($message) use ($verify_mail, $app_name) {
            $message->to($verify_mail);
            $message->subject($app_name . ' ' . 'Invoice');
        });

        return response()->json([
            'isSuccess' => true,
            'message' => 'E-mail enviado com sucesso',
        ], 200);
    }

    public function prescription_email_send($id)
    {
        $prescription = Prescription::with('patient', 'appointment', 'appointment.doctor')
            ->where('id', $id)
            ->where('is_deleted', 0)
            ->first();

        if (! $prescription) {
            return response()->json([
                'isSuccess' => false,
                'message' => 'Detalhes da prescrição não encontrados',
            ], 409);  
        }

        $verify_mail = $prescription->patient->email;
        if ($verify_mail == null) {
            return response()->json([
                'isSuccess' => false,
                'message' => 'Email não encontrado',
            ], 409);
        }

        $app_name = config('app.name');
        $medicines = Medicine::where('prescription_id', $prescription->id)->where('is_deleted', 0)->get();
        $test_reports = TestReport::where('prescription_id', $prescription->id)->where('is_deleted', 0)->get();

        Mail::send('emails.prescription', [
            'prescription' => $prescription, 'medicines' => $medicines, 
            'test_reports' => $test_reports, 'emails' => $verify_mail
        ], function ($message) use ($verify_mail, $app_name) {
            $message->to($verify_mail);
            $message->subject($app_name . ' ' . 'Prescription');
        });

        return response()->json([
            'isSuccess' => true,
            'message' => 'E-mail enviado com sucesso',
        ],200);
    }

}
