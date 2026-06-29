<?php

namespace App\Repositories\Appointment\Pipes\Create;

use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use App\Models\Appointment\Appointment;
use App\Models\User;
use App\Models\ReceptionListDoctor;
use Closure;

class MailPipe
{
    public function handle(Appointment $appointment, Closure $next): mixed
    {
        $MailAppointment = Appointment::with('doctor', 'patient', 'BookedBy')
            ->where('id', $appointment->id)
            ->first();

        $user = Sentinel::getUser();
        $role = $user->roles[0]->slug;
        $userId = $user->id;

        $verify_mail = $user->email;
        $app_name =  config('app.name');

        if ($role == 'receptionist') {
            $admin_role = Sentinel::findRoleBySlug('admin');
            $admin_email = $admin_role->users()->with('roles')->pluck('email');
            $receptionists_doctor_email = User::where('id', $appointment->doctor_id)->pluck('email');
            $receptionists_patient_email = User::where('id', $appointment->patient_id)->pluck('email');
            // return $receptionists_patient_email;

            $mailSend = collect();
            $mailSend->push($receptionists_patient_email);
            $mailSend->push($receptionists_doctor_email);
            $mailSend->push($admin_email);
            $mailSend = $mailSend->flatten();
            $mailArray = $mailSend->toarray();
            // Mail::send('emails.appointment_create', ['MailAppointment' => $MailAppointment, 'email' => $verify_mail], function ($message) use ($mailArray, $app_name) {
            //     $message->to($mailArray)->subject($app_name . ' ' . 'Novo Atendimento gerado');
            // });

        } elseif ($role == 'biomedical') {
            $admin_role = Sentinel::findRoleBySlug('admin');
            $admin_email = $admin_role->users()->with('roles')->pluck('email');
            $biomedicalist_doctor_email = User::where('id', $appointment->doctor_id)->pluck('email');
            $biomedicalist_patient_email = User::where('id', $appointment->patient_id)->pluck('email');
            // return $biomedicalist_patient_email;

            $mailSend = collect();
            $mailSend->push($biomedicalist_patient_email);
            $mailSend->push($biomedicalist_doctor_email);
            $mailSend->push($admin_email);
            $mailSend = $mailSend->flatten();
            $mailArray = $mailSend->toarray();

            // Mail::send('emails.appointment_create', ['MailAppointment' => $MailAppointment, 'email' => $verify_mail], function ($message) use ($mailArray, $app_name) {
            //     $message->to($mailArray)->subject($app_name . ' ' . 'Novo Atendimento gerado');
            // });
        } elseif ($role == 'doctor') {
            $receptionists_doctor_id = ReceptionListDoctor::where('doctor_id', $appointment->doctor_id)->pluck('reception_id');
            $admin_role = Sentinel::findRoleBySlug('admin');

            $receptionists_doctor_mail = ReceptionListDoctor::where('doctor_id', $appointment->doctor_id)->pluck('reception_id');
            $reception_email = User::whereIN('id', $receptionists_doctor_mail)->pluck('email');
            $patient_email = User::where('id', $appointment->patient_id)->pluck('email');
            $admin_email = $admin_role->users()->with('roles')->pluck('email');

            $mailSend = collect();
            $mailSend->push($reception_email);
            $mailSend->push($patient_email);
            $mailSend->push($admin_email);
            $mailSend = $mailSend->flatten();

            $mailArray = $mailSend->toarray();
            // Mail::send('emails.appointment_create', ['MailAppointment' => $MailAppointment, 'email' => $verify_mail], function ($message) use ($mailArray, $app_name) {
            //     $message->to($mailArray)->subject($app_name . ' ' . 'Novo Atendimento gerado');
            // });
        }

        return $next($appointment);
    }
}
