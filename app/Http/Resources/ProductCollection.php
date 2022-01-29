<?php

namespace App\Http\Resources;

use App\Services\CalculateRemainer;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductCollection extends JsonResource
{
    protected $calculate;

    // public function __construct(CalculateRemainer $calculate)
    // {
    //     $this->calculate =$calculate;
    // }
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */

     public function toArray($request)
    {
        $array = $request->all();
        $number = $array[$this->code];
        // dd(MaterialCollection::collection($this->materials)->push(['some_id'=>$number]));
        return [
            'product_name' => $this->name,
            'product_qty' => $number,
            // 'product_materials' =>MaterialCollection::collection($this->materials)->push(['some_id'=>$number]),
            'product_materials'=>CalculateRemainer::calculate(ProductCollection::class, $number)
        ];
    }


}
