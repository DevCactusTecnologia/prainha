<?php

namespace App\Repositories\Appointment\Pipes\Edit;

use Closure;

class EditAppointmentPipe
{
    public function handle(array $pipe, Closure $next): mixed
    {
        session()->put('status', 'Atendimento alterado com sucesso.');
        $pipe['appointment']->update(
            $pipe['request']->all()
        );

        return $next($pipe);
    }
}
