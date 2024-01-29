<?php

namespace App\Http\Controllers\API\Pos;

use App\Http\Controllers\API\Traits\AppUtility;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;

use App\Models\Pos\Product;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class ProductController extends Controller

{use  AppUtility;
 
    public function index()
    {

       
        $products=Product::with('category')->latest()->get();
        return $this->appResponse(['products'=>$products]);
    }


    public function store(ProductRequest $request)
    {  
        
        $validatedData=$request->validated();
        $validatedData['created_by']=$this->currentUser();
        $validatedData['school_id']=$this->school();
        // $validatedData['unit_price']=$this->updateUnitPrice($units);
        // HANDLE PRODUCT IMAGE
        if($request->hasFile('product_image')){
        $file = $request->file('product_image');
        $filename = time() . '_' . $file->getClientOriginalName();
        $path = $file->storeAs('img/product', $filename, 'public');
        $validatedData['product_image'] = asset($path);
        }
$product=null;
if(!empty($validatedData['id'])){
   $product= Product::where('id',$validatedData['id'])->update($validatedData);
}else{

    $product=Product::create($validatedData);
}

        // $this->addProductToGlobalTable($validatedData);
        return $this->appResponse(['product'=>$product,'message'=>'created successfullly'],201);;
    }







    

    public function show(Product $product)
    {
        return $this->appResponse(['product'=>$product]);
    }


    public function update(ProductRequest $request, Product $product)
    
    { 
        $validatedData=$request->validated();
        $validatedData['created_by']=currentUser();
        // $validatedData['unit_price']=$this->updateUnitPrice($units);
        // HANDLE PRODUCT IMAGE
        if($request->hasFile('product_image')){
        $file = $request->file('product_image');
        $filename = time() . '_' . $file->getClientOriginalName();
        $path = $file->storeAs('img/product', $filename, 'public');
        $validatedData['product_image'] = asset($path);
        }
        $product->update($validatedData);
      
        return $this->appResponse(['product'=>$product]);
    }


    public function destroy(Product $product)
    {   
        $product->delete(); 
        return $this->appResponse(['message' => 'Product deleted successfully'],203);
    }



public function getProductByLocation($store_id)  {
    $products=Product::where('store_id',$store_id)->get();
    return $this->appResponse(['products'=>$products,'message' => 'success'],200);
}

public function getProductByCategory($category_id)  {
    $products=Product::where('category_id',$category_id)->get();
    return $this->appResponse(['products'=>$products,'message' => 'success'],200);
}




public function searchProduct(Request $request){
    $barcodeOrName=$request->query('q');
    $products =[];
     $products=$this->searchLocalProduct($barcodeOrName);
     if(!empty($products)){
        return $this->appResponse([
            'isLocal'=>true,
            'products'=>$products
        ]);
        }else{
            $products=$this->searchGlobalProduct($barcodeOrName);
            if(!empty($products)){
                return $this->appResponse([
                    'isLocal'=>false,
                    'products'=>$products
                ]);
            }
        }
        return $this->appResponse([
            'isLocal'=>false,
            'products'=>$products,
            'message'=>'No product found'
        ]);
}





// SEARCH LOCAL PRODUCT
public function searchLocalProduct($keyword)
{
    return Product::search($keyword);
}








public function updateUnitPrice($units=[], $unit_price=[]){

    if(count($unit_price)>0){
        $unitPriceCollection=collect($unit_price);
        foreach ($units as $unit) {
         $result=$unitPriceCollection->where('unit',$unit['unit'])->first();
         if(empty($result)){
          array_push($unit_price,['unit'=>$unit['unit'],'price'=>$unit['price']?$unit['price']:0.00]);
         }
        }
      return $unit_price;
    }else{
        return $this->setUnitPrice($units);
    }

}




public function setUnitPrice($units=[]){
    if(count($units)>0){
        $unit_price=Arr::map($units,function($unit){
          return [
            'unit'=>$unit["unit"],
            'price'=>0.00,
            'skuQtyPerUnit'=>$unit["skuQtyPerUnit"]
          ];
        }); 
    }
    
    return $unit_price;
    }
    
    
    public function getProductByBarcode($barcode){
      return Product::where('barcode',$barcode)->first();
    }




    // public function importFromExcel(Request $request){
    //     if($request->has('file')){
    //       $data = Excel::import(new ProductImport, $request->file);

    //          return $this->appResponse([
    //             'products'=>$data,
    //             'message'=>"products imported successfully"]);
 
    //      }
    //      return $this->appResponse([
    //         'message'=>"No file selected"]);
    // }
}
