<?php


namespace App\Services;
use App\Models\Warehouse;


class CalculateRemainer
{
    protected $remainer=[];
    protected $quantity;
    protected $total=[];


    public function calculate($product, $number)
    {
        $arr=[];
        foreach($product->materials_qtn as $qtn)
        {
            $this->quantity = $qtn->quantity * $number;
            $warehouses = Warehouse::with('materials')->where('material_id', $qtn->material_id)->get();
            if(!$this->checkKey($qtn->material_id,$this->total))
            {
                $this->total[$qtn->material_id]= $warehouses->sum('remainder');
            }

             foreach($warehouses as $warehouse)
             {
                $this->checkKey($warehouse->id,$this->remainer) ? $this->addRemainer($this->remainer[$warehouse->id][$qtn->material_id],$warehouse->remainder) : $this->remainer($warehouse->id, $qtn->material_id,$warehouse->remainder) ;
                if($this->checkRemainerBig($warehouse, $qtn))
                {
                    $this->changeRemainer($warehouse, $qtn, $product);
                }
                elseif( $this->checkRemainerLess($warehouse, $qtn))
                {
                    $this->changeQuantity($warehouse, $qtn, $product);
                }

                if($this->checkRemainerEqual($warehouse, $qtn))
                {
                    $this->stopLoop($warehouse, $qtn, $product);
                    break;
                }
             }
            }
            return $arr;
    }

    public function makeArray($warehouse, $qtn, $product)
    {
        return $arr[] = [$product->id=>["warehouse_id"=>$warehouse->id, 'material_name'=>$warehouse->materials->name ,"qty"=>$this->quantity, 'price'=>$warehouse->price]];
    }

    public function checkKey($key, $arr)
    {
        return array_key_exists($key,$arr);
    }
    public function checkRemainerBig($warehouse, $qtn)
    {
        return $this->remainer[$warehouse->id][$qtn->material_id] >= $this->quantity;
    }
    public function checkRemainerLess($warehouse, $qtn)
    {
        return $this->remainer[$warehouse->id][$qtn->material_id] < $this->quantity;
    }
    public function checkRemainerEqual($warehouse, $qtn)
    {
        // dd($warehouse);
        return $this->remainer[$warehouse->id][$qtn->material_id] == $this->quantity&&$this->quantity>0;
    }
    public function addRemainer($remainer, $remainder)
    {
        // dd($remainer);
       return $remainer += $remainder;
    }
    public function remainer($id, $material_id, $remainder)
    {
        return $this->remainer[$id][$material_id] = $remainder;
    }
    public function changeRemainer($warehouse, $qtn, $product)
    {
        $this->makeArray($warehouse, $qtn, $product);
        $this->remainer[$warehouse->id][$qtn->material_id] -= $this->quantity;
        $this->total[$qtn->material_id] -= $this->quantity;

    }

    public function changeQuantity($warehouse, $qtn, $product)
    {
        $this->makeArray($warehouse, $qtn, $product);
        $this->quantity-=$this->remainer[$warehouse->id][$qtn->material_id];
        $this->total[$qtn->material_id]-=$this->remainer[$warehouse->id][$qtn->material_id];
        $this->remainer[$warehouse->id][$qtn->material_id] = 0;

    }
    public function stopLoop($warehouse, $qtn, $product)
    {
        $this->makeArray($warehouse, $qtn, $product);
        $this->remainer[$warehouse->id][$qtn->material_id] = 0;
        $this->total[$qtn->material_id] = 0;
        $warehouse->id= null;
        $warehouse->price= null;

    }
}
