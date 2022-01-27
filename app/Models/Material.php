<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $guarded = [];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'role_user');
    }
    public function warehouses()
    {
        return $this->hasMany(Warehouse::class);
    }
}