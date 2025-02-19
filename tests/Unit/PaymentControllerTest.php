<?php

namespace Tests\Unit;

use App\Models\Payment;
use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class PaymentControllerTest extends TestCase
{
    use DatabaseTransactions;

    public function test_it_can_fetch_all_payments()
    {
        Payment::factory(3)->create();
        $response = $this->getJson('/api/payments');

        $response->assertStatus(200);
    
        $jsonResponse = $response->json();
    
        $this->assertCount(4, $jsonResponse);
    }

    public function test_it_can_create_payment()
    {
        $customer = Payment::factory()->create();

        $data = [
            'amount' => 100.50,
            'method' => 'credit_card',
            'status' => 'paid',
        ];

        $response = $this->postJson('/api/payments', $data);
        $response->assertStatus(201);
        $response->assertJsonFragment(['amount' => 100.50]);
        $this->assertDatabaseHas('payments', ['amount' => 100.50]);
    }

    public function test_it_can_show_payment_by_id()
    {
        $payment = Payment::factory()->create();
        $response = $this->getJson("/api/payments/{$payment->id}");
        $response->assertStatus(200);
        $response->assertJsonFragment(['amount' => number_format($payment->amount, 2, '.', '')]);

    }

    public function test_it_can_update_payment()
    {
        $payment = Payment::factory()->create();
        
        $updatedData = [
            'amount' => 150.75,
            'method' => 'paypal',
            'status' => 'paid',
        ];

        $response = $this->putJson("/api/payments/{$payment->id}", $updatedData);
        $response->assertStatus(200);

        // $response->assertJsonFragment(['amount' => '10']);
        $this->assertDatabaseHas('payments', ['amount' => 150.75]);
    }

    public function test_it_can_delete_payment()
    {
       
        $payment = Payment::factory()->create();
        $response = $this->deleteJson("/api/payments/{$payment->id}");
        $response->assertStatus(200);
        $response->assertJsonFragment(['message' => 'Payment deleted']);
        $this->assertSoftDeleted('payments', ['id' => $payment->id]);
    }
}
