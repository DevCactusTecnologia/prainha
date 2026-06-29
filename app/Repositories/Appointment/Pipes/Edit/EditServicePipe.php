<?php

namespace App\Repositories\Appointment\Pipes\Edit;

use App\Enums\Appointment\PaymentTaxEnum;
use Illuminate\Support\Facades\DB;
use Closure;

class EditServicePipe
{
    public function handle(array $pipe, Closure $next): mixed
    {
        $data = $pipe['request']->all();

        DB::table('appointment_payment_service')
            ->where('appointment_id', $pipe['appointment']->id)
            ->delete();

        if (array_key_exists('tax_collect_external', $data)) {
            foreach ((array) $data['tax_collect_external'] as $index => $value) {
                DB::table('appointment_payment_service')->insert([
                    'appointment_id' => $pipe['appointment']->id,
                    'payment_service_id' => PaymentTaxEnum::COLLECT_EXTERNAL->value,
                    'price' => $value,
                    'paid_at' => $data['tax_collect_external_paid_at'][$index],
                ]);
            }
        }
        
        if (array_key_exists('tax_send_material', $data)) {
            foreach ((array) $data['tax_send_material'] as $index => $value) {
                DB::table('appointment_payment_service')->insert([
                    'appointment_id' => $pipe['appointment']->id,
                    'payment_service_id' => PaymentTaxEnum::SEND_MATERIAL->value,
                    'price' => $value,
                    'paid_at' => $data['tax_send_material_paid_at'][$index],
                ]);
            }
        }

        return $next($pipe);
    }
}
