<?php

namespace App\Repositories\Appointment\Pipes\Create;

use App\Enums\Appointment\PaymentTaxEnum;
use Illuminate\Support\Facades\DB;
use Closure;

class ServicePipe
{
    public function handle(array $pipe, Closure $next): mixed
    {
        if (array_key_exists('tax_collect_external', $pipe['request']->validated())) {
            DB::table('appointment_payment_service')->insert([
                'appointment_id' => $pipe['appointment']->id,
                'payment_service_id' => PaymentTaxEnum::COLLECT_EXTERNAL->value,
                'price' => $pipe['request']['tax_collect_external'],
                'paid_at' => date('Y-m-d'),
            ]);
        }

        if (array_key_exists('tax_send_material', $pipe['request']->validated())) {
            DB::table('appointment_payment_service')->insert([
                'appointment_id' => $pipe['appointment']->id,
                'payment_service_id' => PaymentTaxEnum::SEND_MATERIAL->value,
                'price' => $pipe['request']['tax_send_material'],
                'paid_at' => date('Y-m-d'),
            ]);
        }

        return $next($pipe);
    }
}
