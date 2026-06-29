<?php

namespace App\Http\Controllers;

use App\Models\Appointment\Appointment;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class ReportController extends Controller
{
    public function getMonthlyUsersRevenue()
    {
        $appointment = DB::select(
            'SELECT 
                MONTH(appointments.created_at) AS Month,
                count(id) AS total_appointment
            FROM appointments 
            WHERE YEAR(appointments.created_at) = YEAR(CURDATE())
            GROUP BY MONTH(appointments.created_at)'
        );

        $exams = DB::select(
            'SELECT 
                MONTH(appointment_exams.collected_at) AS Month,
                count(appointment_exams.collected_at) AS total_exams
            FROM appointment_exams, appointments 
            WHERE appointment_exams.appointment_id = appointments.id AND YEAR(appointment_exams.collected_at) = YEAR(CURDATE())
            GROUP BY MONTH(appointment_exams.collected_at)'
        );

        return [
            'total_appointment' => $appointment,
            'total_exams' => $exams
        ];
    }

    public function getMonthlyAppointments()
    {
        $user = Sentinel::getUser();
        $appointments = Appointment::select(DB::raw('MONTH(appointment_date) Month'), DB::raw('count(id) as `total_appointment`'))
            ->whereYear('created_at', Carbon::now()->year)
            ->groupBy(DB::raw('MONTH(appointment_date)'))
            ->where('appointment_with', $user->id)
            ->get();

        return $appointments;
    }

    public static function getMonthlyEarning()
    {
        $user = Sentinel::getUser();
        $role = $user->roles[0]->slug;
        $userId = $user->id;

        if ($role == 'patient') {
            $invoice = Invoice::withCount(['invoice_detail as total' => function ($query) {
                    $query->select(DB::raw('SUM(amount)'));
                }])
                ->whereMonth('created_at', date('m'))
                ->where('patient_id', $userId)
                ->pluck('id');

            $preInvoice = Invoice::withCount(['invoice_detail as total' => function ($query) {
                    $query->select(DB::raw('SUM(amount)'));
                }])
                ->whereMonth('created_at', Carbon::now()->subMonth()->month)
                ->where('patient_id', $userId)
                ->pluck('id');

            $currentMonthEarning = InvoiceDetail::whereIn('invoice_id', $invoice)->sum('amount');
            $prevMonthEarning = InvoiceDetail::whereIn('invoice_id', $preInvoice)->sum('amount');

        } elseif ($role == 'doctor') {
            $invoice = Invoice::withCount(['invoice_detail as total' => function ($query) {
                    $query->select(DB::raw('SUM(amount)'));
                }])
                ->whereMonth('created_at', date('m'))
                ->where('created_by', $userId)
                ->pluck('id');
            
            $preInvoice = Invoice::withCount(['invoice_detail as total' => function ($query) {
                    $query->select(DB::raw('SUM(amount)'));
                }])
                ->whereMonth('created_at', Carbon::now()->subMonth()->month)
                ->where('created_by', $userId)
                ->pluck('id');

            $currentMonthEarning = InvoiceDetail::whereIn('invoice_id', $invoice)->sum('amount');
            $prevMonthEarning = InvoiceDetail::whereIn('invoice_id', $preInvoice)->sum('amount');

        } else {
            $invoice = Invoice::withCount(['invoice_detail as total' => function ($query) {
                    $query->select(DB::raw('SUM(amount)'));
                }])
                ->whereMonth('created_at', date('m'))
                ->pluck('id');

            $preInvoice = Invoice::withCount(['invoice_detail as total' => function ($query) {
                    $query->select(DB::raw('SUM(amount)'));
                }])
                ->whereMonth('created_at', Carbon::now()->subMonth()->month)
                ->pluck('id');

            $currentMonthEarning = InvoiceDetail::whereIn('invoice_id', $invoice)->sum('amount');
            $prevMonthEarning = InvoiceDetail::whereIn('invoice_id', $preInvoice)->sum('amount');

        }

        $diff = $currentMonthEarning - $prevMonthEarning;
        if ($prevMonthEarning == 0) {
            $total_diff = 100;
        } else {
            $total_diff = $diff / $prevMonthEarning * 100;
        }

        return [
            'monthlyEarning' => $currentMonthEarning,
            'diff' => number_format($total_diff, 2)
        ];
    }
}
