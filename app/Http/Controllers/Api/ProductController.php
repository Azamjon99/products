<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductCollection;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends BaseController
{
    public function product()
    {
        // return "dasda";
        // return Product::all();

        $data =  ProductCollection::collection(Product::all());

        return $this->successResponse(ProductCollection::collection(Product::all()), 'Products');
    }

    public function calculate(Request $request)
    {
        return $request->all();
    }
}
