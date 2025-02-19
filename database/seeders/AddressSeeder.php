<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

class AddressSeeder extends Seeder
{
    public function run()
    {
        // Buscar um cliente existente
        $customer = DB::table('customers')->first();

        if (!$customer) {
            $this->command->warn("Nenhum cliente encontrado. Execute o CustomerSeeder primeiro.");
            return;
        }

        DB::table('addresses')->insert([
            [
                'id'           => Uuid::uuid4()->toString(),
                'customer_id'  => $customer->id,
                'street'       => 'Rua das Flores, 123',
                'complement'   => 'Apto 101',
                'neighborhood' => 'Centro',
                'zip_code'     => '01001-000',
                'is_default'   => true,
                'created_at'   => now(),
                'updated_at'   => now(),
            ]
        ]);
    }
}
