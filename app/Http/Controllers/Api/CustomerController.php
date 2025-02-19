<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerRequest;
use App\Repositories\CustomerRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;

class CustomerController extends Controller
{
    protected $customerRepository;

    public function __construct(CustomerRepository $customerRepository)
    {
        $this->customerRepository = $customerRepository;
    }

    public function index()
    {
        try {
            $customers = $this->customerRepository->all();
            return response()->json($customers);
        } catch (Exception $e) {
            return response()->json(['message' => 'An error occurred while fetching customers'], 500);
        }
    }
    
    public function store(CustomerRequest $request)
    {
        try {
            $customer = $this->customerRepository->create($request->validated());
            return response()->json($customer, 201);
        } catch (Exception $e) {
            return response()->json(['message' => 'An error occurred while creating the customer'], 500);
        }
    }

    public function show($id)
    {
        try {
            $customer = $this->customerRepository->find($id);
            if (!$customer) {
                return response()->json(['message' => 'Customer not found'], 404);
            }

            return response()->json($customer);
        } catch (Exception $e) {
            return response()->json(['message' => 'An error occurred while fetching the customer'], 500);
        }
    }

    public function update(CustomerRequest $request, string $id)
    {   
        try {
            $data = $request->validated();
            $customer = $this->customerRepository->update($id, $data);
    
            return response()->json($customer, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Customer not found'], 404);
        } catch (Exception $e) {
            return response()->json(['message' => 'An error occurred while updating the customer'], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $id = trim($id, '"');
            $customer = $this->customerRepository->find($id);
    
            if (!$customer) {
                return response()->json(['message' => 'Customer not found'], 404);
            }
    
            $this->customerRepository->delete($id);
    
            return response()->noContent(); 
        } catch (Exception $e) {
            return response()->json(['message' => 'An error occurred while deleting the customer'], 500);
        }
    }
}
