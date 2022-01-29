<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductAndMaterial extends Model
{
    use HasFactory;
    public function materials()
    {

        return $this->belongsTo(Material::class);
    }

    public function product()
    {

        return $this->belongsTo(Product::class);
    }
}
