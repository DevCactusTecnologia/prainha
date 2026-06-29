<?php

namespace App\Repositories\Appointment\Pipes\Create;

use App\Enums\Shared\NotificationTypeEnum;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use App\Models\Appointment\Appointment;
use App\Models\Notification;
use Illuminate\Support\Facades\DB;
use Closure;

class NotificationPipe
{
    public function handle(array $pipe, Closure $next): mixed
    {
        $notifications = $this->notifications($pipe['appointment']);

        foreach ($notifications as $notification) {
            Notification::create($notification);
        }

        return $next($pipe);
    }

    private function notifications(Appointment $appointment): array
    {
        $payloads = [];
        $users = DB::table('users')
            ->select('id')
            ->where('id', '<>', Sentinel::getUser()->id)
            ->get();

        foreach ($users as $user) {
            $payloads[] = [
                'title' => 'Adicionado',
                'type_id' => NotificationTypeEnum::INSERTED,
                'patient_id' => $appointment->patient_id,
                'from_user_id' => Sentinel::getUser()->id,
                'to_user_id' => $user->id,
                'created_at' => date('Y-m-d H:i:s'),
            ];
        }

        return $payloads;
    }
}
