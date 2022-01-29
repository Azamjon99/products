<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\MaterialCollection;
use App\Http\Resources\ProductCollection;
use App\Models\Product;
use App\Services\CalculateRemainer;
use Illuminate\Http\Request;

class ProductController extends BaseController
{
    protected $calculate;

    public function __construct(CalculateRemainer $calculate)
    {
        $this->calculate =$calculate;
    }

    public function calculate(Request $request)
    {
        $array =$request->all();
        $keys= collect($array)->keys();
        $products = Product::whereIn('code', $keys)->get();
        if ($products->isEmpty())
        {
            return $this->errorResponse([], 'Product not found');
        }
        $arr=  $this->formatArray($products, $array);
        return  $this->successResponse($arr);
    }

    public function formatArray($products, $array)
    {
        foreach($products as $product)
        {
        $number = $array[$product->code];
        $arr[]= ['product_name'=>$product->name, 'product_qty'=>$number, 'product_materials'=>$this->calculate->calculate($product, $number)];
        }
        return $arr;
    }



}
