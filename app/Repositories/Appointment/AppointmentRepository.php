<?php

namespace App\Repositories\Appointment;

use App\Models\Appointment\Appointment;
use App\Models\Appointment\GuideNumberCounter;
use App\Http\Requests\Appointment\AppointmentRequest;
use App\Models\Patient;
use App\Repositories\Appointment\Pipes\Cancel\CreateTraceabilityPipe;
use App\Repositories\Appointment\Pipes\Cancel\UpdateAppointmentPipe;
use App\Repositories\Appointment\Pipes\Cancel\UpdateExamsPipe;
use App\Repositories\Appointment\Pipes\Create\ExamPipe;
use App\Repositories\Appointment\Pipes\Create\MoreDoctorPipe;
use App\Repositories\Appointment\Pipes\Create\NotificationPipe;
use App\Repositories\Appointment\Pipes\Create\PaymentPipe;
use App\Repositories\Appointment\Pipes\Create\ServicePipe;
use App\Repositories\Appointment\Pipes\Edit\EditAppointmentPipe;
use App\Repositories\Appointment\Pipes\Edit\EditExamsPipe;
use App\Repositories\Appointment\Pipes\Edit\EditPaymentPipe;
use App\Repositories\Appointment\Pipes\Edit\EditServicePipe;
use Illuminate\Http\Request;
use Illuminate\Pipeline\Pipeline;

class AppointmentRepository
{
    public function store(AppointmentRequest $request): void
    {
        $guide = GuideNumberCounter::incrementGuide($request->unity_id);
        $appointment = Appointment::create($request->validated() + ['guide_number' => $guide]);

        $pipeline = app(Pipeline::class)
            ->send(passable: [
                'appointment' => $appointment,
                'request' => $request,
            ])
            ->through(pipes: [
                PaymentPipe::class,
                ExamPipe::class,
                ServicePipe::class,
                MoreDoctorPipe::class,
                NotificationPipe::class,
            ])
            ->thenReturn();

        session()->put('appointment_id', $pipeline['appointment']->id);
        session()->put('status', 'Agendamento criado com sucesso');
    }

    public function update(AppointmentRequest $request, Appointment $appointment)
    {
        app(Pipeline::class)
            ->send(passable: [
                'appointment' => $appointment,
                'request' => $request,
            ])
            ->through(pipes: [
                EditAppointmentPipe::class,
                EditPaymentPipe::class,
                EditExamsPipe::class,
                EditServicePipe::class,
                NotificationPipe::class,
            ])
            ->thenReturn();
    }

    public function cancel(Request $request, Appointment $appointment)
    {
        app(Pipeline::class)
            ->send(passable: [
                'appointment' => $appointment,
                'request' => $request,
            ])
            ->through(pipes: [
                UpdateAppointmentPipe::class,
                UpdateExamsPipe::class,
                CreateTraceabilityPipe::class,
            ])
            ->thenReturn();
    }

    public function getPrintUrl(Appointment $appointment): string|null
    {
        $patient = Patient::find($appointment->patient_id);

        if (! $patient->cpf && ! $patient->cns) {
            $urlPatient = null;
        } else {
            $cpfEncoded = base64_encode($patient->cpf ?: '0');
            $cnsEncoded = base64_encode($patient->cns ?: '0');
            $urlPatient = route('patient.result.index', [$cpfEncoded, $cnsEncoded]);
        }

        return $urlPatient;
    }
}
