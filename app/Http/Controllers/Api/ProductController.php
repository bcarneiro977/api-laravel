<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Repositories\ProductRepository;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Models\Product;

class ProductController extends Controller
{
    protected $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function index()
    {
        try {
            $products = $this->productRepository->all();
            return response()->json($products);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error fetching products', 'error' => $e->getMessage()], 500);
        }
    }

    public function store(ProductRequest $request)
    {
        try {
            $data = $request->validated();
    
            if ($request->hasFile('photo')) {
                $file = $request->file('photo');
                $filename = $file->hashName();
                $file->storeAs('products', $filename, 'public');
                $data['photo'] = $filename;
            } else {
                $data['photo'] = 'default.jpg';
            }

            $product = $this->productRepository->create($data);
            $product->photo_url = asset("storage/products/{$product->photo}");
            return response()->json($product, 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error creating the product', 'error' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $product = $this->productRepository->find($id);
            if (!$product) {
                return response()->json(['message' => 'Product not found'], 404);
            }
            $product->photo_url = asset("storage/products/{$product->photo}");
            return response()->json($product);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Product not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error fetching the product'], 500);
        }
    }

    public function update(ProductRequest $request, $id)
    {

        try {
            $product = Product::findOrFail($id);
            $data = $request->validated();
    
            if ($request->hasFile('photo')) {
                $file = $request->file('photo');
                $filename = $file->hashName();
                $file->storeAs('products', $filename, 'public');
                $data['photo'] = "products/{$filename}";
            }
    
            $product->update($data);
            return response()->json($product);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao atualizar produto'], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $id = trim($id, '"');
            $this->productRepository->delete($id);
            return response()->json(['message' => 'Product deleted'], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Product not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error deleting the product'], 500);
        }
    }
}
