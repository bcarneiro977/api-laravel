<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PaymentRequest;
use App\Repositories\PaymentRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;

class PaymentController extends Controller
{
    protected $paymentRepository;

    public function __construct(PaymentRepository $paymentRepository)
    {
        $this->paymentRepository = $paymentRepository;
    }

    public function index()
    {
        try {
            $payments = $this->paymentRepository->all();
            return response()->json($payments);
        } catch (Exception $e) {
            
            return response()->json(['message' => 'An error occurred while fetching payments'], 500);
        }
    }
    
    public function store(PaymentRequest $request)
    {
        try {
            $payment = $this->paymentRepository->create($request->validated());
            return response()->json($payment, 201);
        } catch (Exception $e) {
            dd($e);
            return response()->json(['message' => 'An error occurred while creating the payment'], 500);
        }
    }

    public function show($id)
    {
        try {
            $payment = $this->paymentRepository->find($id);
            if (!$payment) {
                return response()->json(['message' => 'Payment not found'], 404);
            }

            return response()->json($payment);
        } catch (Exception $e) {
            return response()->json(['message' => 'An error occurred while fetching the payment'], 500);
        }
    }

    public function update(PaymentRequest $request, string $id)
    {   
        try {
            $data = $request->validated();
            $payment = $this->paymentRepository->update($id, $data);
    
            return response()->json($payment);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Payment not found'], 404);
        } catch (Exception $e) {
            return response()->json(['message' => 'An error occurred while updating the payment'], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $id = trim($id, '"');
            $payment = $this->paymentRepository->find($id);

            if (!$payment) {
                return response()->json(['message' => 'Payment not found'], 404);
            }

            $this->paymentRepository->delete($id);
            return response()->json(['message' => 'Payment deleted'], 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'An error occurred while deleting the payment'], 500);
        }
    }
}
