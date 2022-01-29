<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MaterialCollection extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // dd($this->quantity);
        return [
            'material_name' => $this->some_id,
            // 'product_qty' => $number,
            // 'product_materials' =>MaterialCollection::collection($this->materials),

        ];
    }
}
