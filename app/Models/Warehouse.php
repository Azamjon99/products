<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    use HasFactory;
    public function materials()
    {
        return $this->belongsTo(Material::class, 'material_id');
    }

    public function getWarehouses($id)
    {
        $wareHouse=Warehouse::with('materials')->where('material_id', $id)->get();
        return $wareHouse;
    }
}
