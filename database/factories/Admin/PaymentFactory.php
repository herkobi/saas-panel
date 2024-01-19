<?php

namespace Database\Factories\Admin;

use App\Enums\Status;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Admin\Currency>
 */
class PaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'status' => Status::ACTIVE,
            'is_system' => 1,
            'title' => 'EFT/Havale İle Ödeme',
            'desc' => 'Doğrudan banka havalesi ile ödeme yapılması için etkinleştiriniz.'
        ];
    }

    /**
     * Define a state for credit card payment.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function forCreditCardPayment()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => Status::PASSIVE,
                'is_system' => 1,
                'title' => 'Kredi Kartı İle Ödeme',
                'desc' => 'Kredi kartı ile ödeme yapılması için etkinleştiriniz.'
            ];
        });
    }
}
