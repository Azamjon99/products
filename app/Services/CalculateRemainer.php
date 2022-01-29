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
            if(!$this->checkKey($qtn->material_id,$this->total  ))
            {
                $this->total[$qtn->material_id]= $warehouses->sum('remainder');
            }
             foreach($warehouses as $warehouse){
                $this->checkKey($warehouse->id,$this->remainer) ? $this->addRemainer($this->remainer[$warehouse->id][$qtn->material_id],$warehouse->remainder) : $this->remainer($warehouse->id, $qtn->material_id,$warehouse->remainder) ;


                if($this->checkRemainerBig($warehouse, $qtn))
                {
                    // $this->changeRemainer($warehouse, $qtn, $product);

                    $arr[] =$this->makeArray($warehouse, $this->quantity, $product);
                    $this->changeRemainer($warehouse, $qtn, $product);

                    break;
                }
                elseif($this->checkRemainerLess($warehouse, $qtn))
                {
                    $arr[] =$this->makeArray($warehouse, $this->remainer[$warehouse->id][$qtn->material_id], $product);
                    $this->changeQuantity($warehouse, $qtn);

                }
                if($this->checkRemainerEqual($qtn)){
                    $this->stopLoop($warehouse, $qtn);
                    $arr[] = $this->makeArray($warehouse, $this->quantity, $product);

                    break;

                }
             }
            }
            return $arr;
    }



    public function makeArray($warehouse, $qtn, $product)
    {
        return ["warehouse_id"=>$warehouse->id, 'material_name'=>$warehouse->materials->name ,"qty"=>$qtn, 'price'=>$warehouse->price];
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
        return $this->remainer[$warehouse->id][$qtn->material_id] < $this->quantity&&$this->remainer[$warehouse->id][$qtn->material_id]!==0;
    }
    public function checkRemainerEqual( $qtn)
    {
        // dd($warehouse);
        return $this->total[$qtn->material_id] == 0&&$this->quantity>0;
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
        $this->makeArray($warehouse, $this->quantity, $product);
        $this->remainer[$warehouse->id][$qtn->material_id] -= $this->quantity;
        $this->total[$qtn->material_id] -= $this->quantity;

    }

    public function changeQuantity($warehouse, $qtn)
    {
        $this->quantity-=$this->remainer[$warehouse->id][$qtn->material_id];
        $this->total[$qtn->material_id]-=$this->remainer[$warehouse->id][$qtn->material_id];
        $this->remainer[$warehouse->id][$qtn->material_id] = 0;

    }
    public function stopLoop($warehouse, $qtn)
    {
        $this->remainer[$warehouse->id][$qtn->material_id] = 0;
        $this->total[$qtn->material_id] = 0;
        $warehouse->id= null;
        $warehouse->price= null;

    }


}
