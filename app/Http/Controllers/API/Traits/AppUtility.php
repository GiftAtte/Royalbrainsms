<?php
namespace App\Http\Controllers\API\Traits;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;


// use SimpleSoftwareIO\QrCode\Facades\QrCode;
trait AppUtility
{
   
    public function generateRandomString($length = 10)
    {
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $randomString = '';

        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }

        return $randomString;
    }

    /**
     * Format a date string to the specified format.
     *
     * @param string $date
     * @param string $format
     * @return string
     */
   
    public function currentUser()
    {
        return Auth::id();
    }


  
        public function appResponse($data,$reponseCode=200){
            return response()->json(['data'=>$data],$reponseCode);
       }
    
       public function school(){
        return Auth::user()->school_id;
   }
    // Add more utility functions as needed...

  


    // public function generateBarcode($data,$format='png') {
    //     return QrCode::format($format);
    // }




  function configureConnectionByName($tenantDatabaseName)
  {
    config(['database.connections.tenant.database' => $tenantDatabaseName]);
    Config::set('database.default','tenant');
     DB::purge('tenant');
     DB::reconnect('tenant');

  }
  
}
