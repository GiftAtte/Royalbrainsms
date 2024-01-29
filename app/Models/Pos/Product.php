<?php

namespace App\Models\Pos;


use App\User;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Arr;

class Product extends Model
{
    use  SoftDeletes;
    protected $fillable=[
        'name',
        'product_code',
        'barcode',
        'units',
        'sku',
        'unit_price',
        'product_image',
        'slug',
        'stock',
        'deleted_at',
        'category_id',
        'created_by',
        'school_id'
    ];


    protected $appends = [
     'available_stock',
        
    ];

    protected $casts=[
        'units'=>'json',
        'unit_price'=>'json'
    ];
    protected $hidden = [
        'createdBy'
    ];
    public function category()  {
        return $this->belongsTo(Category::class);
    }

  

    public function createdBy()  {
        return $this->belongsTo(User::class,'created_by');
    }

    protected function getAvailableStockAttribute()
{
    $sorted =  collect($this->unit_price)->sortByDesc('skuQtyPerUnit');

  
     $stock=$this->stock;
      $stockArr=[];
        foreach ($sorted as  $unitObj) {
            $unitStock=    floor($stock/$unitObj['skuQtyPerUnit']);
            $stock=$stock-($unitStock*$unitObj['skuQtyPerUnit']);
            array_push($stockArr,[
                'unit'=>$unitObj['unit'],
                 'value'=>$unitStock
            ]);
             
        }
  
    

    
    return $stockArr;

}


public function compareSkuQtyPerUnit($a, $b) {
    return $b['skuQtyPerUnit'] - $a['skuQtyPerUnit'];
}




public static function search($keyword)
    {
        return self::where('barcode', 'LIKE', '%' . $keyword . '%')
                    ->orWhere('name', 'LIKE', '%' . $keyword . '%')
                     ->get();
    }

    
}
