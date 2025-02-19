<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\ProductRepository;
use App\Http\Requests\OrderRequest;
use App\Repositories\OrderRepository;
use Illuminate\Support\Facades\Log;
use App\Jobs\SendOrderEmail;

use App\Models\Customer;
use App\Models\Order;

use App\Jobs\SendOrderEmailJob;

use Exception;

class OrderController extends Controller
{
    protected $productRepository;
    protected $orderRepository;

    public function __construct(ProductRepository $productRepository, OrderRepository $orderRepository)
    {
        $this->productRepository = $productRepository;
        $this->orderRepository = $orderRepository;
    }

    public function index()
    {
        try {
            $orders = $this->orderRepository->all()->load('orderItems');
                       
            return response()->json([
                'data' => $orders
            ], 200);
        } catch (Exception $e) {    
            return response()->json([
                'message' => 'Error retrieving orders',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function store(OrderRequest $request)
    {
        try {
            $validatedData = $request->validated();
            $totalPrice = 0;
            $products = [];

            foreach ($validatedData['products'] as $productData) {
                $product = $this->productRepository->find($productData['product_id']);
                if (!$product) {
                    return response()->json(['message' => 'Product not found'], 404);
                }

                $subtotal = $product->price * $productData['quantity'];
                $totalPrice += $subtotal;

                $products[] = [
                    'product_id' => $product->id,
                    'quantity'   => $productData['quantity'],
                    'price'      => $product->price
                ];
            }

            $validatedData['total_price'] = $totalPrice;
            $validatedData['products'] = $products;

            $order = $this->orderRepository->createOrder($validatedData);


            if (!$order) {
                return response()->json(['message' => 'Failed to create order'], 500);
            }
            
            $customer = Customer::find($validatedData['customer_id']);
            dispatch(new SendOrderEmailJob($order, $customer->email));
            
            return response()->json([
                'message' => 'Order created successfully!',
                'data'    => $order
            ], 201);
        } catch (Exception $e) {
            Log::error('Error creating order', ['error' => $e->getMessage()]);
            return response()->json([
                'message' => 'Error creating order',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $order = $this->orderRepository->find($id);
            if (!$order) {
                return response()->json(['message' => 'Order not found'], 404);
            }
            return response()->json($order, 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Error retrieving order',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function update(OrderRequest $request, $id)
    {
        try {
            $validatedData = $request->validated();
            $totalPrice = 0;
            $products = [];
    
            $order = $this->orderRepository->find($id);
            if (!$order) {
                return response()->json(['message' => 'Order not found'], 404);
            }
   
            foreach ($validatedData['products'] as $productData) {
                $product = $this->productRepository->find($productData['product_id']);
                if (!$product) {
                    return response()->json(['message' => 'Product not found'], 404);
                }
    
                $subtotal = $product->price * $productData['quantity'];
                $totalPrice += $subtotal;
    
                $products[] = [
                    'product_id' => $product->id,
                    'quantity'   => $productData['quantity'],
                    'price'      => $product->price
                ];
            }
    
            $validatedData['total_price'] = $totalPrice;
            $validatedData['products'] = $products;
    
            $order = $this->orderRepository->updateOrder($order, $validatedData);
    
            if (!$order) {
                return response()->json(['message' => 'Failed to update order'], 500);
            }
    
            return response()->json([
                'message' => 'Order updated successfully!',
                'data'    => $order
            ], 200);
    
        } catch (Exception $e) {
            Log::error('Error updating order', ['error' => $e->getMessage()]);
            return response()->json([
                'message' => 'Error updating order',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
    
    public function destroy($id)
    {
        try {
            $deleted = $this->orderRepository->deleteOrder($id);
            if (!$deleted) {
                return response()->json(['message' => 'Order not found'], 404);
            }
            return response()->json(['message' => 'Order deleted successfully!'], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Error deleting order',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
}
