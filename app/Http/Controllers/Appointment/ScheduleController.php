<?php

namespace App\Http\Controllers\Appointment;

use App\Models\Appointment\Appointment;
use App\Http\Controllers\Controller;
use App\Models\DoctorAvailableDay;
use App\Models\DoctorAvailableSlot;
use App\Models\DoctorAvailableTime;
use Carbon\Carbon;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ScheduleController extends Controller
{
    public function index()
    {
        $user = Sentinel::getUser();
        $role = $user->roles[0]->slug;

        if ($role == 'doctor') {
            $appointments = Appointment::with('patient', 'timeSlot')
                ->where('appointment_with', $user->id)
                ->where('appointment_date', Carbon::today())
                ->get();

        } elseif ($role == 'patient') {
            $appointments = Appointment::with('doctor', 'timeSlot')
                ->where('appointment_for', $user->id)
                ->where('appointment_date', Carbon::today())
                ->get();

        } else {
            $userId = $user->id;
            $doctor_role = Sentinel::findRoleBySlug('doctor');
            $doctor_id = $doctor_role->users()
                ->with(['roles', 'doctor'])
                ->where('is_deleted', 0)
                ->pluck('id');

            $appointments = Appointment::with('doctor', 'patient', 'timeSlot')
                ->where(function ($query) use ($userId, $doctor_id) {
                    $query->whereIn('appointment_with', $doctor_id);
                    $query->orWhereIn('booked_by', $doctor_id);
                    $query->orWhere('booked_by', $userId);
                })
                ->where('appointment_date', Carbon::today())
                ->get();
        }

        return view('appointments.schedule', 
            compact('appointments')
        );
    }

    public function list(Request $request)
    {
        $user = Sentinel::getUser();
        $role = $user->roles[0]->slug;
        $userId = $user->id;
        
        if ($role == 'doctor') {
            $res = Appointment::with('patient', 'timeSlot')
                ->where('appointment_with', $userId)
                ->where('appointment_date', $request->date)
                ->get();

        } elseif ($role == 'patient') {
            $res = Appointment::with('doctor', 'timeSlot')
                ->where('appointment_for', $userId)
                ->where('appointment_date', $request->date)
                ->get();

        } else {
            $doctor_role = Sentinel::findRoleBySlug('doctor');
            $doctor_id = $doctor_role->users()->with(['roles', 'doctor'])->where('is_deleted', 0)->pluck('id');
            
            $res = Appointment::with('patient', 'timeSlot', 'doctor')
                ->where('appointment_date', $request->date)
                ->where(function ($query) use ($userId, $doctor_id) {
                    $query->whereIN('appointment_with', $doctor_id);
                    $query->orWhereIN('booked_by', $doctor_id);
                    $query->orWhere('booked_by', $userId);
                })
                ->get();
        }

        if (empty($res)) {
            $response = [
                'status' => 'error',
                'message' => 'Nenhum atendimento encontrado'
            ];
        } else {
            $response = [
                'role' => $role,
                'appointments' => $res
            ];
        }

        return response()->json($response);
    }

    public function doctor_by_day_time(Request $request)
    {
        if ($request->ajax()) {
            $doctor_id = $request->doctor_id;
            $doctor_available_day = DoctorAvailableDay::firstWhere('appointment_with', $doctor_id);
            $doctor_available_time = DoctorAvailableTime::where('appointment_with', $doctor_id)
                ->where('is_deleted', 0)
                ->get();

            return response()->json([
                'isSuccess' => true,
                'Message' => 'Médico disponível',
                'data' => [$doctor_available_day, $doctor_available_time],
            ]);
        }
    }

    public function time_by_slot(Request $request)
    {
        if ($request->ajax()) {
            $timeId = $request->timeId;
            $doctorId = $request->doctorId;
            $date = $request->dates;
            $dates = Carbon::createFromFormat('m/d/Y', $date)->format('Y-m-d');

            $appointment_slot = DoctorAvailableSlot::with(['appointment' => function ($query) use ($dates) {
                    $query->where('appointment_date', $dates);
                }])
                ->where('doctor_available_time_id', $timeId)->get();

            return response()->json([
                'isSuccess' => true,
                'Message' => 'O horário de agendamento foi encontrado com sucesso',
                'data' => [$appointment_slot, $dates, $doctorId]
            ]);
        }
    }

    public function cal_appointment_show(Request $request)
    {
        if ($request->ajax()) {
            $user = Sentinel::getUser();
            $userId = $user->id;
            $role = $user->roles[0]->slug;

            if ($role == 'doctor') {
                $appointment = Appointment::select(DB::raw('count(id) as `total_appointment`'), DB::raw('appointment_date'))
                    ->whereDate('appointment_date', '>=', $request->start)
                    ->whereDate('appointment_date', '<=', $request->end)
                    ->groupBy(DB::raw('appointment_date'))
                    ->where('appointment_with', $user->id)
                    ->get();

            } elseif ($role == 'patient') {
                $appointment = Appointment::select(DB::raw('count(id) as `total_appointment`'), DB::raw('appointment_date'))
                    ->whereDate('appointment_date', '>=', $request->start)
                    ->whereDate('appointment_date',   '<=', $request->end)
                    ->groupBy(DB::raw('appointment_date'))
                    ->where('appointment_for', $user->id)
                    ->get();

            } else {
                $doctor_role = Sentinel::findRoleBySlug('doctor');
                $doctors_id = $doctor_role->users()->with(['roles', 'doctor'])->where('is_deleted', 0)->pluck('id');
                $appointment = Appointment::select(DB::raw('count(id) as `total_appointment`'), DB::raw('appointment_date'))
                    ->whereDate('appointment_date', '>=', $request->start)
                    ->whereDate('appointment_date',   '<=', $request->end)
                    ->where(function ($query) use ($userId, $doctors_id) {
                        $query->whereIN('appointment_with', $doctors_id);
                        $query->orWhereIN('booked_by', $doctors_id);
                        $query->orWhere('booked_by', $userId);
                    })
                    ->groupBy(DB::raw('appointment_date'))
                    ->get();
            }

            if (empty($appointment)) {
                $response = [
                    'status' => 'error',
                    'message' => 'Nenhum agendamento encontrado em '
                ];
            } else {
                $response = [
                    'role' => $role,
                    'appointments' => $appointment
                ];
            }

            return response()->json($response);
        }
    }

}
