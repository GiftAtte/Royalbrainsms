<?php

namespace App\Http\Controllers\API\Pos;

use App\Http\Controllers\API\Traits\AppUtility;
use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Models\Pos\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller

    {
        use AppUtility;

        public function __construct()
        {
            // Apply the 'check.permissions' middleware to specific controller methods
          
        }



 
        public function index()
        {
            $categories=Category::where('school_id',$this->school())->latest()->get();
            return $this->appResponse(['categories'=>$categories]);
        }
    

        public function store(CategoryRequest $request)
        {   
        
            $validatedData=$request->validated();
            $validatedData['created_by']=$this->currentUser();
            $validatedData['school_id']=$this->school();
            $category=Category::create($validatedData);
            return $this->appResponse(['category'=>$category,'message','Created successfully'],201);;
        }
    


 
        public function show(Category $category)
        {
            return $this->appResponse(['category'=>$category]);
        }
         


        public function update(CategoryRequest $request, Category $category)
        {
            $validatedData=$request->validated();
            $validatedData['created_by']=$this->currentUser();
            $category->update($validatedData);
            return  $this->appResponse(['category'=>$category,'message'=>'Updated successfully']);
        }
    


        public function destroy(Category $category)
        {
            $category->delete();
            return  $this->appResponse(['message' => 'Category deleted successfully'],203);
        }
    }
    