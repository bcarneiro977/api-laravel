<?php

namespace Database\Factories;

use App\Models\Payment;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    public function definition()
    {
        return [
            'amount' => $this->faker->randomFloat(2, 10, 1000),
            'method' => $this->faker->randomElement(['credit_card', 'paypal', 'bank_transfer']),
            'status' => $this->faker->randomElement(['pending', 'paid', 'canceled']),
        ];
    }
}