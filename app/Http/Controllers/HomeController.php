<?php

namespace App\Http\Controllers;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use App\Models\Home\Campaign;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\View\View;
use stdClass;

class HomeController extends Controller
{
    public function index(): View
    {
        $user = Sentinel::getUser();
        $role = $user->roles[0]->slug;

        if ($role == 'admin' || $role == 'doctor') {
            $examsMonths = DB::select(
                "SELECT COUNT(id) AS total 
                FROM appointment_exams 
                WHERE collected_at BETWEEN ? AND ?
                AND (status = 0 OR status = 1)", [
                    Carbon::now()->startOfMonth(),
                    Carbon::now()->endOfMonth()
                ]
            );

            // SISLAC: novo dashboard administrativo (design system appsislac).
            // Renderiza 'dashboards.admin-sislac' diretamente, que extende o
            // novo master 'layouts.sislac.master' (sidebar branca + tema claro).
            // O dashboard legado 'layouts.admin-dashboard' continua disponível
            // mas não é mais o caminho default para admin/doctor.
            return view('dashboards.admin-sislac', [
                'total_appointment' => DB::select('SELECT COUNT(id) AS total FROM appointments')[0]->total,
                'total_exam_month_current' => $examsMonths[0]->total,
                'total_exams' => DB::select("SELECT COUNT(id) AS total FROM appointment_exams WHERE (status = 0 OR status = 1)")[0]->total,
                'today_appointment_total' => $this->getTotalAppointmentWhere('appointment_date = CURDATE()'),
                'today_appointment_exam_total' => $this->getTotalAppointmentExamWhere('appointments.appointment_date = CURDATE()'),
                'pending_appointment_total' => $this->getTotalAppointmentWhere('status = 0'),
                'pending_appointment_exam_total' => $this->getTotalAppointmentExamWhere('appointments.status = 0'),
                'occurrences' => $this->getTotalOccurrences(),
                'campaignCurrent' => Campaign::monthCurrent(),
            ]);
        
        } elseif ($role == 'receptionist' || $role == 'biomedical') {
            return view('index', [
                'today_appointment_total' => $this->getTotalAppointmentWhere('appointment_date = CURDATE()'),
                'today_appointment_exam_total' => $this->getTotalAppointmentExamWhere('appointments.appointment_date = CURDATE()'),
                'pending_appointment_total' => $this->getTotalAppointmentWhere('status = 0'),
                'pending_appointment_exam_total' => $this->getTotalAppointmentExamWhere('appointments.status = 0'),
                'occurrences' => $this->getTotalOccurrences(),
                'appointments' => $this->getAppointmentsToday(),
                'campaignCurrent' => Campaign::monthCurrent(),
            ]);
        }
    }

    private function getAppointmentsToday(): LengthAwarePaginator
    {
        return DB::table('appointments')
            ->select([
                'appointments.id AS protocol',
                'user_patient.id AS patient_id',
                'user_patient.first_name AS patient_name',
                'patients.patient_social_name AS patient_name_social',
                'user_doctor.first_name AS doctor_name',
                'appointments.status',
                'appointments.priority_id',
                'appointments.created_at'
            ])
            ->join('users AS user_doctor', 'appointments.appointment_with', '=', 'user_doctor.id')
            ->join('users AS user_patient', 'appointments.appointment_for', '=', 'user_patient.id')
            ->join('patients', 'user_patient.id', '=', 'patients.user_id')
            ->whereDate('appointments.appointment_date', '=', date('Y-m-d'))
            ->orderByDesc('appointments.id')
            ->paginate(10);
    }

    private function getTotalAppointmentWhere(string $clause = ''): int
    {
        return DB::select(
            "SELECT COUNT(id) AS total 
            FROM appointments 
            WHERE {$clause}"
        )[0]->total;
    }

    private function getTotalAppointmentExamWhere(string $clause = ''): int
    {
        return DB::select(
            "SELECT COUNT(appointment_exams.id) AS total 
            FROM appointments
            INNER JOIN appointment_exams
            ON appointment_exams.appointment_id = appointments.id
            WHERE {$clause}"
        )[0]->total;
    }

    private function getTotalOccurrences(): stdClass
    {
        return DB::select(
            "SELECT 
                SUM(IF(occurrences.status = 0, 1, 0)) AS pending, 
                SUM(IF(occurrences.status = 2, 1, 0)) AS resolved 
            FROM appointment_occurrences AS occurrences"
        )[0];
    }
}
