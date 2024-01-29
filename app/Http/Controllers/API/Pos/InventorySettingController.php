<?php

namespace App\Http\Controllers\API\Pos;

use App\Http\Controllers\API\Traits\AppUtility;
use App\Http\Controllers\Controller;
use App\Http\Requests\InventorySettingRequest;
use App\Models\Pos\InventorySetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InventorySettingController extends Controller
{
    use AppUtility;
  


     /**
        * @OA\Get(
        * path="/api/inventory-settings",
        * operationId="Get All settings",
        * tags={"Inventory-Settings"},
        * summary="Get All settings",
        * security={{ "bearer_token": {} }},
        * description="Get all settings", 
        *      @OA\Response(response=200,description="success", @OA\JsonContent()),
        *      @OA\Response(response=400, description="Bad request"),
        *      @OA\Response(response=404, description="Resource Not Found"),
        * )
        */
    public function index()
    {
       $settings=InventorySetting::with('createdBy')->get();
       return $this->appResponse(['settings'=>$settings]);
    }

    
         /**
        * @OA\Post(
        * path="/api/inventory-settings",
        * operationId="Create new settings",
        * tags={"Inventory-Settings"},
        * summary="Create New inventory setting",
        * security={{ "bearer_token": {} }},
        * description="Create inventory setting",
        *     @OA\RequestBody(
        *         @OA\JsonContent(),
        *         @OA\MediaType(
        *            mediaType="multipart/form-data",
        *            @OA\Schema(
        *               type="object",
        *               required={"module","setting_key","setting_value","store_id"},
        *               @OA\Property(property="module", type="text"),
        *               @OA\Property(property="setting_key", type="text"),
        *               @OA\Property(property="setting_value", type="text"),
         *               @OA\Property(property="store_id", type="number"),
        
        *              
        *            ),
        *        ),
        *    ),
        *      @OA\Response(response=201,description="success",@OA\JsonContent()),
        *      @OA\Response(response=200,description="success", @OA\JsonContent()),
        *      @OA\Response(response=422,description="Unprocessable Entity", @OA\JsonContent()),
        *      @OA\Response(response=400, description="Bad request"),
        *      @OA\Response(response=404, description="Resource Not Found"),
        * )
        */
    public function store(InventorySettingRequest $request)
    {
        $data= $request->validated();
        $data['created_by']=$this->currentUser();
        $setting=InventorySetting::create($data);
        return $this->appResponse(['setting'=>$setting]);
    }

    
   /**
        * @OA\Get(
        * path="/api/inventory-settings/{id}",
        * operationId="Get settings by Id",
        * tags={"Inventory-Settings"},
        * summary="Get settings by id",
        * security={{ "bearer_token": {} }},
        * description="Get settings by id",
        * @OA\Parameter(
        *    name="id",
        *    in="path",
        *    description="setting id to delete",
        *    required=true,
        *      ),
        *      @OA\Response(response=201,description="success",@OA\JsonContent()),
        *      @OA\Response(response=200,description="success", @OA\JsonContent()),
        *      @OA\Response(response=422,description="Unprocessable Entity", @OA\JsonContent()),
        *      @OA\Response(response=400, description="Bad request"),
        *      @OA\Response(response=404, description="Resource Not Found"),
        * )
        */
    public function show(InventorySetting $inventorySetting)
    {
        return $this->appResponse(['setting'=>$inventorySetting]);
    }

    
        /**
        * @OA\put(
        * path="/api/inentory-settings",
        * operationId="Update inentory-settings",
        * tags={"Inventory-Settings"},
        * summary="Update settings",
        * security={{ "bearer_token": {} }},
        * description="Update Inventory settings",
        *     @OA\RequestBody(
        *         @OA\JsonContent(),
        *         @OA\MediaType(
        *            mediaType="multipart/form-data",
        *            @OA\Schema(
        *               type="object",
        *               required={"name"},
        *               @OA\Property(property="name", type="text"),
        *       
        *            ),
        *        ),
        *    ),
        * @OA\Parameter(
        *    name="id",
        *    in="path",
        *    description="setting id to update",
        *    required=true,
        *      ),
        *      @OA\Response(response=201,description="success",@OA\JsonContent()),
        *      @OA\Response(response=200,description="success", @OA\JsonContent()),
        *      @OA\Response(response=422,description="Unprocessable Entity", @OA\JsonContent()),
        *      @OA\Response(response=400, description="Bad request"),
        *      @OA\Response(response=404, description="Resource Not Found"),
        * )
        */
    public function update(InventorySettingRequest $request,InventorySetting $inventorySetting)
    {
        $data= $request->validated();
        $data['created_by']=$this->currentUser();
       $inventorySetting->update($data);
       return $this->appResponse(['setting'=>$inventorySetting,'message'=>'updated successfully']);
    }

     /**
        * @OA\Delete(
        * path="/api/inventory-settings/{id}",
        * operationId="Delete settings",
        * tags={"Inventory-Settings"},
        * summary="Delete settings by id",
        * security={{ "bearer_token": {} }},
        * description="Delete settings by id",
        * @OA\Parameter(
        *    name="id",
        *    in="path",
        *    description="settings id to delete",
        *    required=true,
        *      ),
        *      @OA\Response(response=201,description="success",@OA\JsonContent()),
        *      @OA\Response(response=200,description="success", @OA\JsonContent()),
        *      @OA\Response(response=422,description="Unprocessable Entity", @OA\JsonContent()),
        *      @OA\Response(response=400, description="Bad request"),
        *      @OA\Response(response=404, description="Resource Not Found"),
        * )
        */ 
    public function destroy(InventorySetting $inventorySetting)
    {
        $inventorySetting->delete();
        return $this->appResponse(['setting'=>$inventorySetting,'message'=>'updated successfully']);
    }
}
