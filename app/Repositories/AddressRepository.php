<?php

namespace App\Repositories;

use App\Models\Address;
use App\Models\Customer;

class  AddressRepository extends BaseRepository
{
    public function create(array $data)
    {
        $address = Address::create($data);

        if (!empty($data['is_default']) && $data['is_default']) {
            Customer::where('id', $data['customer_id'])->update(['default_address_id' => $address->id]);
        }

        return $address;
    }
}
