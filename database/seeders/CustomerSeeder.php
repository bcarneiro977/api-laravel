<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

class CustomerSeeder extends Seeder
{
    public function run()
    {
        DB::table('customers')->insert([
            [
                'id'                 => Uuid::uuid4()->toString(),
                'name'               => 'João Silva',
                'email'              => 'joao.silva@example.com',
                'phone'              => '11987654321',
                'birth_date'         => '1990-05-15',
                'default_address_id' => null,
                'created_at'         => now(),
                'updated_at'         => now(),
            ]
        ]);
    }
}
