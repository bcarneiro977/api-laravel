<?php

namespace App\Repositories;

use App\Models\Order;
use App\Models\OrderItem;
use App\Repositories\BaseRepository;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderRepository extends BaseRepository
{
    public function __construct(Order $model)
    {
        parent::__construct($model);
    }

    public function createOrder(array $data)
    {

        return DB::transaction(function () use ($data) {
            $order = $this->model->create([
                'customer_id' => $data['customer_id'],
                'address_id'  => $data['address_id'],
                'payment_id'  => $data['payment_id'],
                'total_price' => $data['total_price'],
                'status'      => 'pending',
            ]);
    
            $orderItems = collect($data['products'])->map(function ($item) use ($order) {
                return [
                    'order_id'   => (string) $order->id, 
                    'id'         => (string) Str::uuid(), 
                    'product_id' => $item['product_id'],
                    'quantity'   => $item['quantity'],
                    'price'      => $item['price'],
                ];
            });
    
            $order->orderItems()->createMany($orderItems->toArray());
    
            return $order;
        });
    }


    public function updateOrder($order, array $data)
    {
        return DB::transaction(function () use ($order, $data) {

            $order->update([
                'customer_id' => $data['customer_id'],
                'address_id'  => $data['address_id'],
                'payment_id'  => $data['payment_id'],
                'total_price' => $data['total_price'],
                'status'      => $data['status'] ?? $order->status,  
            ]);

            $order->orderItems()->delete();
            $orderItems = collect($data['products'])->map(function ($item) use ($order) {
            
                return [
                    'order_id'   => (string) $order->id, 
                    'id'         => (string) Str::uuid(),
                    'product_id' => $item['product_id'],
                    'quantity'   => $item['quantity'],
                    'price'      => $item['price'],
                ];
            });

            $order->orderItems()->createMany($orderItems->toArray());
            return $order;
        });
    }

    public function deleteOrder($id)
    {
        $order = $this->model->find($id);

        if (!$order) {
            return false;
        }

        return $order->delete();
    }
}
