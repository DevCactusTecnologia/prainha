<?php

namespace App\Repositories\Appointment\Pipes\Edit;

use App\Enums\Appointment\PaymentEnum;
use Illuminate\Support\Facades\DB;
use Closure;

class EditPaymentPipe
{
    public function handle(array $pipe, Closure $next): mixed
    {
        $data = $pipe['request']->all();

        $pipe['appointment']->payment()->update([
            'subtotal' => array_key_exists('subtotal', $data) ? $data['subtotal'] : null,
            'tax_collect_external' => $this->taxCollectExternal($data),
            'tax_send_material' => $this->taxSendMaterial($data),
            'discount_money' => array_key_exists('discount_money', $data) ? $data['discount_money'] : null,
            'discount_percent' => array_key_exists('discount_percent', $data) ? $data['discount_percent'] : null,
            'total' => array_key_exists('total', $data) ? $data['total'] : null,
            'total_paid'=> array_key_exists('total_paid', $data) ? $data['total_paid'] : null,
            'due_balance' => array_key_exists('due_balance', $data) ? $data['due_balance'] : null,
            'registered_at' => $data['registered_at'],
        ]);

        DB::table('appointment_payment_type')
            ->where('appointment_id', $pipe['appointment']->id)
            ->delete();

        if (array_key_exists('payment_money', $data)) {
            foreach ((array) $data['payment_money'] as $index => $value) {
                DB::table('appointment_payment_type')->insert([
                    'appointment_id' => $pipe['appointment']->id,
                    'payment_type_id' => PaymentEnum::MONEY->value,
                    'price_paid' => $value,
                    'paid_at' => $data['payment_money_paid_at'][$index],
                ]);
            }
        }
        
        if (array_key_exists('payment_pix', $data)) {
            foreach ((array) $data['payment_pix'] as $index => $value) {
                DB::table('appointment_payment_type')->insert([
                    'appointment_id' => $pipe['appointment']->id,
                    'payment_type_id' => PaymentEnum::PIX->value,
                    'price_paid' => $value,
                    'paid_at' => $data['payment_pix_paid_at'][$index],
                ]);
            }
        }
        
        if (array_key_exists('payment_card', $data)) {
            foreach ((array) $data['payment_card'] as $index => $value) {
                DB::table('appointment_payment_type')->insert([
                    'appointment_id' => $pipe['appointment']->id,
                    'payment_type_id' => PaymentEnum::CARD->value,
                    'price_paid' => $value,
                    'paid_at' => $data['payment_card_paid_at'][$index],
                ]);
            }
        }
        
        if (array_key_exists('payment_company', $data)) {
            foreach ((array) $data['payment_company'] as $index => $value) {
                DB::table('appointment_payment_type')->insert([
                    'appointment_id' => $pipe['appointment']->id,
                    'payment_type_id' => PaymentEnum::COMPANY->value,
                    'price_paid' => $value,
                    'paid_at' => $data['payment_company_paid_at'][$index],
                ]);
            }
        }
        
        if (array_key_exists('payment_cheque', $data)) {
            foreach ((array) $data['payment_cheque'] as $index => $value) {
                DB::table('appointment_payment_type')->insert([
                    'appointment_id' => $pipe['appointment']->id,
                    'payment_type_id' => PaymentEnum::CHEQUE->value,
                    'price_paid' => $value,
                    'paid_at' => $data['payment_cheque_paid_at'][$index],
                ]);
            }
        }

        return $next($pipe);
    }

    private function taxCollectExternal(array $data): float|null
    {
        $taxs = null;

        if (array_key_exists('tax_collect_external', $data)) {
            foreach ((array) $data['tax_collect_external'] as $value) {
                $taxs += (float) $value;
            }
        }

        return $taxs;
    }

    private function taxSendMaterial(array $data): float|null
    {
        $taxs = null;

        if (array_key_exists('tax_send_material', $data)) {
            foreach ((array) $data['tax_send_material'] as $value) {
                $taxs += (float) $value;
            }
        }

        return $taxs;
    }
}
