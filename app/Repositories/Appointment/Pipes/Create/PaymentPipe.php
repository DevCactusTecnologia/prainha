<?php

namespace App\Repositories\Appointment\Pipes\Create;

use App\Enums\Appointment\PaymentEnum;
use Illuminate\Support\Facades\DB;
use Closure;

class PaymentPipe
{
    public function handle(array $pipe, Closure $next): mixed
    {
        $data = $pipe['request']->validated();
        $pipe['appointment']->payment()->create($data);

        if (array_key_exists('payment_money', $data)) {
            DB::table('appointment_payment_type')->insert([
                'appointment_id' => $pipe['appointment']->id,
                'payment_type_id' => PaymentEnum::MONEY->value,
                'price_paid' => $data['payment_money'],
                'paid_at' => date('Y-m-d'),
            ]);
        }

        if (array_key_exists('payment_pix', $data)) {
            DB::table('appointment_payment_type')->insert([
                'appointment_id' => $pipe['appointment']->id,
                'payment_type_id' => PaymentEnum::PIX->value,
                'price_paid' => $data['payment_pix'],
                'paid_at' => date('Y-m-d'),
            ]);
        }

        if (array_key_exists('payment_card', $data)) {
            DB::table('appointment_payment_type')->insert([
                'appointment_id' => $pipe['appointment']->id,
                'payment_type_id' => PaymentEnum::CARD->value,
                'price_paid' => $data['payment_card'],
                'paid_at' => date('Y-m-d'),
            ]);
        }

        if (array_key_exists('payment_company', $data)) {
            DB::table('appointment_payment_type')->insert([
                'appointment_id' => $pipe['appointment']->id,
                'payment_type_id' => PaymentEnum::COMPANY->value,
                'price_paid' => $data['payment_company'],
                'paid_at' => date('Y-m-d'),
            ]);
        }

        if (array_key_exists('payment_cheque', $data)) {
            DB::table('appointment_payment_type')->insert([
                'appointment_id' => $pipe['appointment']->id,
                'payment_type_id' => PaymentEnum::CHEQUE->value,
                'price_paid' => $data['payment_cheque'],
                'paid_at' => date('Y-m-d'),
            ]);
        }

        return $next($pipe);
    }
}
