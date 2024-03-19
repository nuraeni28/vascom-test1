<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;

class ProductController extends BaseController
{
    public function store(Request $request)
    {
        $data = $request->all();
        $product = Product::create($data);

        return response()->json(['code' => 201, 'message' => 'Product created successfully', 'data' => $product]);
    }
    public function index()
    {
        $product = Product::all();
        return response()->json(['code' => 200, 'message' => 'Product list', 'data' => $product]);
    }

    public function update(Request $request, $id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['code' => 404, 'message' => 'Product not found', 'data' => $product]);
        }

        $data = $request->all();
        $product->fill($data);
        $product->save();

        return response()->json(['code' => 200, 'message' => 'Product updated successfully', 'data' => $product]);
    }
    public function destroy($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['code' => 404, 'message' => 'Product not found', 'data' => $product]);
        }

        $product->delete();

        return response()->json(['code' => 200, 'message' => 'Data deleted successfully', 'data' => $product]);
    }
}
