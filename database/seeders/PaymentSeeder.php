<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

class PaymentSeeder extends Seeder
{
    public function run()
    {
        DB::table('payments')->insert([
            [
                'id'         => Uuid::uuid4()->toString(),
                'amount'     => 50.00,
                'method'     => 'Cartão de Crédito',
                'status'     => 'pago',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
