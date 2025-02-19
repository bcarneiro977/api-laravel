<?php
namespace Database\Factories;

use App\Models\Order;
use App\Models\Customer;
use App\Models\Address;
use App\Models\Payment;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition()
    {
        return [
            'customer_id' => Customer::factory(),
            'address_id' => Address::factory(),
            'payment_id' => Payment::factory(),
            'status' => $this->faker->randomElement(['pending', 'paid', 'shipped', 'delivered', 'canceled']),
            'total_price' => 0, 
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    public function withProducts($count = 1)
    {
        return $this->afterCreating(function (Order $order) use ($count) {
            $products = Product::factory()->count($count)->create();
            $totalPrice = 0;

            foreach ($products as $product) {
                $quantity = $this->faker->numberBetween(1, 5);
                $order->products()->attach($product->id, [
                    'quantity' => $quantity,
                    'price' => $product->price,
                ]);
                $totalPrice += $product->price * $quantity;
            }

            $order->update(['total_price' => $totalPrice]);
        });
        
    }
}
