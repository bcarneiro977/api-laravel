<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

class OrderSeeder extends Seeder
{
    public function run()
    {
        $customerId = DB::table('customers')->inRandomOrder()->value('id');
        $addressId  = DB::table('addresses')->where('customer_id', $customerId)->inRandomOrder()->value('id');
        $paymentId  = DB::table('payments')->inRandomOrder()->value('id');

        DB::table('orders')->insert([
            [
                'id'          => Uuid::uuid4()->toString(),
                'customer_id' => $customerId,
                'address_id'  => $addressId,
                'payment_id'  => $paymentId,
                'total_price' => 120.50,
                'status'      => 'paid',
                'created_at'  => now(),
                'updated_at'  => now(),
            ]
        ]);
    }
}
