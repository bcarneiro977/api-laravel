<?php

namespace Tests\Unit;

use App\Models\Address;
use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Support\Str;

class AddressControllerTest extends TestCase
{
    use DatabaseTransactions;

    public function test_it_can_fetch_all_addresses()
    {    
        $data = Address::factory()->count(3)->create();

        $response = $this->getJson('/api/addresses');
    
        $response->assertStatus(200);
    
        $jsonResponse = $response->json();
    
        $this->assertCount(4, $jsonResponse);
    }
    

    public function test_it_can_create_an_address()
    {
        $customer = Customer::factory()->create();
        $addressData = Address::factory()->make(['customer_id' => $customer->id])->toArray();

        $response = $this->postJson('/api/addresses', $addressData);

        $response->assertStatus(201);
        $response->assertJson(['message' => 'Endereço criado com sucesso', 'data' => $addressData]);
    }

    public function test_it_can_show_address_by_id()
    {
        $address = Address::factory()->create(['id' => Str::uuid()]);

        $response = $this->getJson("/api/addresses/{$address->id}");

        $response->assertStatus(200)
                 ->assertJson($address->toArray());
    }

    public function test_it_can_update_address()
    {
        $address = Address::factory()->create(['id' => Str::uuid()]);
        
        $updatedData = [
            'street' => 'Updated Street',
            'zip_code' => '12345678',
            'customer_id' => $address->customer_id, 
            'neighborhood' => 'Updated Neighborhood', 
        ];

        $response = $this->putJson("/api/addresses/{$address->id}", $updatedData);

        $response->assertStatus(200);
        $this->assertDatabaseHas('addresses', array_merge(['id' => $address->id], $updatedData));
    }

    public function test_it_can_delete_address()
    {
        $address = Address::factory()->create(['id' => Str::uuid()]);

        $this->assertNotNull($address->id);

        $response = $this->deleteJson("/api/addresses/{$address->id}");

        $response->assertStatus(200);
        $this->assertSoftDeleted('addresses', ['id' => $address->id]);
    }
}
