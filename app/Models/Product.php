<?php

namespace App\Models;

use App\Services\CalculateRemainer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $guarded = [];
    protected $calculate;
    protected $remainer=[];
    protected $quantity;
    protected $total=[];


    public function materials()
    {

        return $this->belongsToMany(Material::class, 'product_and_materials');
    }

    public function materials_qtn()
    {

        return $this->hasMany(ProductAndMaterial::class);
    }

        // public function calculateMaterialNumber($product)
        // {
        //     $arr[]= $this->calculate->calculate($product, $number);


        // }

        
}
