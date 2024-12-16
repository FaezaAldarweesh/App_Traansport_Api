<?php

namespace App\Services\ApiServices;

use Illuminate\Support\Facades\Log;
use App\Http\Traits\ApiResponseTrait;
use App\Models\driver;
use Illuminate\Support\Facades\Request;

class DriverService {
    //trait customize the methods for successful , failed , authentecation responses.
    use ApiResponseTrait;
    /**
     * method to view all drivers 
     * @param   Request $request
     * @return /Illuminate\Http\JsonResponse if have an error
     */
    public function get_all_Drivers(){
        try {
            $driver = Driver::all();
            return $driver;
        } catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('حدث خطأ أثناء محاولة الوصول إلى السائقين', 400);}
    }
    //========================================================================================================================
    /**
     * method to store a new driver
     * @param   $data
     * @return /Illuminate\Http\JsonResponse ig have an error
     */
    public function create_Driver($data) {
        try {
            $driver = new Driver();
            $driver->name = $data['name'];
            $driver->phone = $data['phone'];
            $driver->location = $data['location'];
            
            $driver->save(); 
    
            return $driver; 
        } catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('حدث خطأ أثناء محاولة إضافة سائق جديد', 400);}
    }    
    //========================================================================================================================
    /**
     * method to update driver alraedy exist
     * @param  $data
     * @param  Driver $driver
     * @return /Illuminate\Http\JsonResponse if have an error
     */
    public function update_Driver($data, Driver $driver){
        try { 
            $driver->name = $data['name'] ?? $driver->name;
            $driver->phone = $data['phone'] ?? $driver->phone;
            $driver->location = $data['location'] ?? $driver->location;
            
            $driver->save(); 
            return $driver;

        }catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('حدث خطأ أثناء محاولة التعديل على السائق', 400);}
    }
    //========================================================================================================================
    /**
     * method to soft delete driver alraedy exist
     * @param  Driver $driver)
     * @return /Illuminate\Http\JsonResponse if have an error
     */
    public function delete_driver(Driver $driver)
    {
        try {  
            $driver->delete();
            return true;
        } catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('حدث خطأ أثناء محاولة حذف السائق', 400);}
    }
    //========================================================================================================================
    /**
     * method to return all soft delete drivers
     * @return /Illuminate\Http\JsonResponse if have an error
     */
    public function all_trashed_driver()
    {
        try {  
            $drivers = Driver::onlyTrashed()->get();
            return $drivers;
        } catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('حدث خطأ أثناء محاولة الوصول إلى أرشيف السائقين', 400);}
    }
    //========================================================================================================================
    /**
     * method to restore soft delete station alraedy exist
     * @param   $driver_id
     * @return /Illuminate\Http\JsonResponse if have an error
     */
    public function restore_driver($driver_id)
    {
        try {
            $driver = Driver::onlyTrashed()->find($driver_id);
            if(!$driver){
                throw new \Exception('السائق المطلوب غير موجود');
            }
            return $driver->restore();
        }catch (\Exception $e) { Log::error($e->getMessage()); return $this->failed_Response($e->getMessage(), 400);      
        } catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('حدث خطأ أثناء محاولة إستعادة السائق', 400);
        }
    }
    //========================================================================================================================
    /**
     * method to force delete on driver that soft deleted before
     * @param   $driver_id
     * @return /Illuminate\Http\JsonResponse if have an error
     */
    public function forceDelete_driver($driver_id)
    {   
        try {
            $driver = Driver::onlyTrashed()->find($driver_id);
            if(!$driver){
                throw new \Exception('السائق المطلوب غير موجود');
            }
 
            return $driver->forceDelete();
        }catch (\Exception $e) { Log::error($e->getMessage()); return $this->failed_Response($e->getMessage(), 400);   
        } catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('حدث خطأ أثناء محاولة حذف أرشيف السائق', 400);}
    }
    //========================================================================================================================

}
