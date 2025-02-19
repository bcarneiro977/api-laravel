<?php

namespace Database\Factories;

use App\Models\Address;
use Illuminate\Database\Eloquent\Factories\Factory;

class AddressFactory extends Factory
{
    protected $model = Address::class;

    public function definition(): array
    {
        return [
            'customer_id'  => \App\Models\Customer::factory(),
            'street'       => $this->faker->streetAddress,
            'complement'   => $this->faker->secondaryAddress,
            'neighborhood' => $this->faker->citySuffix,
            'zip_code'     => $this->faker->postcode,
            'is_default'   => $this->faker->boolean,
        ];
    }
}
