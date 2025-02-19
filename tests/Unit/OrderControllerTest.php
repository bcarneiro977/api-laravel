<?php

namespace Tests\Unit;

use App\Models\Customer;
use App\Models\Address;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Support\Str;

class OrderControllerTest extends TestCase
{
    use DatabaseTransactions;

    public function test_can_list_orders()
    {
        Order::factory()->count(5)->create();

        $response = $this->getJson('/api/orders');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'customer_id', 'total_price', 'status']
                ]
            ]);
    }

    public function test_can_create_order()
    {
        $customer = Customer::factory()->create();
        $address = Address::factory()->create();
        $payment = Payment::factory()->create();
    
        $product1 = Product::factory()->create(['price' => 10.50]);
        $product2 = Product::factory()->create(['price' => 20.00]);
    
        $products = [
            ['product_id' => $product1->id, 'quantity' => 1, 'price' => $product1->price],
            ['product_id' => $product2->id, 'quantity' => 2, 'price' => $product2->price],
        ];
    
        $totalPrice = collect($products)->sum(fn($p) => $p['quantity'] * $p['price']);
    
        $response = $this->postJson('/api/orders', [
            'customer_id' => $customer->id,
            'address_id' => $address->id,
            'payment_id' => $payment->id,
            'products' => $products,
        ]);
    
        $response->assertStatus(201) 
            ->assertJson([
                'message' => 'Order created successfully!',
            ]);
    
   
        $this->assertDatabaseHas('orders', [
            'customer_id' => $customer->id,
            'address_id' => $address->id,
            'payment_id' => $payment->id,
            'status' => 'pending',
            'total_price' => $totalPrice,
        ]);
    
        foreach ($products as $product) {
            $this->assertDatabaseHas('order_items', [
                'product_id' => $product['product_id'],
                'quantity' => $product['quantity'],
                'price' => $product['price'],
            ]);
        }
    }
    

    public function test_can_update_order()
    {
        $customer = Customer::factory()->create();
        $address = Address::factory()->create();
        $payment = Payment::factory()->create();
        $products = Product::factory(2)->create();

        $order = Order::factory()->create([
            'customer_id' => $customer->id,
            'address_id'  => (string) $address->id,
            'payment_id'  => (string) $payment->id,
            'status' => 'pending',
            'total_price' => 100.00,
        ]);

        $order->products()->attach([
            $products[0]->id => [
                'id'       => \Ramsey\Uuid\Uuid::uuid4()->toString(),
                'quantity' => 1,
                'price'    => $products[0]->price
            ],
            $products[1]->id => [
                'id'       => \Ramsey\Uuid\Uuid::uuid4()->toString(),
                'quantity' => 2,
                'price'    => $products[1]->price
            ],
        ]);
        
        $updatedData = [
            'customer_id' => $customer->id,
            'address_id'  => (string) $address->id,
            'payment_id'  => (string) $payment->id,
            'status' => 'paid',
            'total_price' => 400.00,
            'products' => [
                [
                    'product_id'  => (string) $products[0]->id,
                    'quantity'    => 3,
                    'price'       => $products[0]->price,
                ],
                [
                    'product_id' => (string) $products[1]->id,
                    'quantity'   => 1,
                    'price'      => $products[1]->price,
                ]
            ]
        ];
    
        $this->assertDatabaseHas('customers', ['id' => $customer->id]);
        $this->assertDatabaseHas('addresses', ['id' => $address->id]);
        $this->assertDatabaseHas('payments', ['id' => $payment->id]);

        $response = $this->putJson("/api/orders/" . (string) $order->id, $updatedData);

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'status' => 'paid',
        ]);

        $this->assertDatabaseHas('orders', [
            'id' => $order->id->toString(),
            'status' => 'paid',
        ]);
    }

    public function test_can_delete_order()
    {
        // Criar registros necessários
        $customer = Customer::factory()->create();
        $address = Address::factory()->create();
        $payment = Payment::factory()->create();
        $products = Product::factory(2)->create();

        // Criar e associar o pedido com produtos
        $order = Order::factory()->create([
            'customer_id' => $customer->id,
            'address_id'  => (string) $address->id,
            'payment_id'  => (string) $payment->id,
            'status' => 'pending',
            'total_price' => 400.00,
        ]);
        $order->products()->attach([
            $products[0]->id => [
                'id'       => \Ramsey\Uuid\Uuid::uuid4()->toString(),
                'quantity' => 1,
                'price'    => $products[0]->price
            ],
            $products[1]->id => [
                'id'       => \Ramsey\Uuid\Uuid::uuid4()->toString(),
                'quantity' => 2,
                'price'    => $products[1]->price
            ],
        ]);

        $this->assertNotNull($order->id);
        $response = $this->deleteJson("/api/orders/{$order->id}");

        $response->assertStatus(200);
        $this->assertSoftDeleted('orders', ['id' => $order->id]);
        
    }
}
