<?php
namespace App\Http\Controllers\API\Traits;

use Illuminate\Support\Facades\File;

trait FileUtils
{


 //  HANDLE FILE UPLOAD
 public function handleFileUpload($file,$destination){
    $filename = time() . '_' . $file->getClientOriginalName();
    $path = $file->storeAs('uploaded-files/'.$destination, $filename, 'public');
    return asset($path);
}


    public function deleteFileIfExist($filePath){ // Replace with the path to your file
        if (File::exists($filePath)) {
            File::delete($filePath);
        } 
    }
}