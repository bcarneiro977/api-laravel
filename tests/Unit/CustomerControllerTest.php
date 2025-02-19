<?php

namespace Tests\Unit;

use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Support\Str;

class CustomerControllerTest extends TestCase
{
    use DatabaseTransactions;

    public function test_it_can_fetch_all_customers()
    {
        Customer::factory()->count(3)->create();

        $response = $this->getJson('/api/customers');
       
        $response->assertStatus(200);
    
        $jsonResponse = $response->json();
    
        $this->assertCount(4, $jsonResponse);
    }

    public function test_it_can_create_a_customer()
    {
        $customerData = Customer::factory()->make()->toArray();

        $response = $this->postJson('/api/customers', $customerData);

        $response->assertStatus(201);
        $response->assertJson($customerData);
    }

    public function test_it_can_show_customer_by_id()
    {
        $customer = Customer::factory()->create(['id' => Str::uuid()]);

        $response = $this->getJson("/api/customers/{$customer->id}");

        $response->assertStatus(200)
                 ->assertJson($customer->toArray());
    }

    public function test_it_can_update_customer()
    {
        $customer = Customer::factory()->create(['id' => Str::uuid()]);
        $customerData = ['name' => 'Updated Name', 'email' => 'updated@example.com'];

        $response = $this->putJson("/api/customers/{$customer->id}", $customerData);

        $response->assertStatus(200);
        $this->assertDatabaseHas('customers', array_merge(['id' => $customer->id], $customerData));
    }

    public function test_it_can_delete_customer()
    {
        $customer = Customer::factory()->create(['id' => Str::uuid()]);

        $this->assertNotNull($customer->id);

        $response = $this->deleteJson("/api/customers/{$customer->id}");

        $response->assertStatus(204);
        $this->assertSoftDeleted('customers', ['id' => $customer->id]);
    }
}
