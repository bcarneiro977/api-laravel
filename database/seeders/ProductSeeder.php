<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

class ProductSeeder extends Seeder
{
    public function run()
    {
        DB::table('products')->insert([
            [
                'id'         => Uuid::uuid4()->toString(),
                'name'       => 'Pastel de Queijo',
                'price'      => 10.00,
                'photo'      => 'pastel_queijo.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
