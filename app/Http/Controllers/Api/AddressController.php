<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddressRequest;
use App\Repositories\AddressRepository;
use App\Repositories\CustomerRepository;
use Exception;

class AddressController extends Controller
{
    protected $addressRepository;
    protected $customerRepository;

    public function __construct(AddressRepository $addressRepository, CustomerRepository $customerRepository)
    {
        $this->addressRepository = $addressRepository;
        $this->customerRepository = $customerRepository;
    }

    public function index()
    {
        try {
            $addresses = $this->addressRepository->all();
            return response()->json($addresses);
        } catch (Exception $e) {
            return response()->json(['message' => 'Erro ao obter endereços', 'error' => $e->getMessage()], 500);
        }
    }

    public function store(AddressRequest $request)
    {
        try {
            $address = $this->addressRepository->create($request->validated());
            return response()->json(['message' => 'Endereço criado com sucesso', 'data' => $address], 201);
        } catch (Exception $e) {
            return response()->json(['message' => 'Erro ao criar endereço', 'error' => $e->getMessage()], 500);
        }
    }

    public function show(string $id)
    {
        try {
            $address = $this->addressRepository->find($id);
            if (!$address) {
                return response()->json(['message' => 'Address not found'], 404);
            }
            return response()->json($address);
        } catch (Exception $e) {
            return response()->json(['message' => 'Erro ao buscar endereço', 'error' => $e->getMessage()], 500);
        }
    }

    public function update(AddressRequest $request, string $id)
    {
        try {
            $address = $this->addressRepository->find($id);
            if (!$address) {
                return response()->json(['message' => 'Address not found'], 404);
            }

            $updatedAddress = $this->addressRepository->update($id, $request->validated());
            return response()->json($updatedAddress);
        } catch (Exception $e) {
            return response()->json(['message' => 'Erro ao atualizar endereço', 'error' => $e->getMessage()], 500);
        }
    }

    public function destroy(string $id)
    {
        try {
            $id = trim($id, '"');
            $address = $this->addressRepository->find($id);

            if (!$address) {
                return response()->json(['message' => 'Address not found'], 404);
            }

            $this->addressRepository->delete($id);

            return response()->json(['message' => 'Address deleted'], 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'Erro ao deletar endereço', 'error' => $e->getMessage()], 500);
        }
    }
}
