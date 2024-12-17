<?php

namespace App\Services\ApiServices;

use App\Models\Bus;
use App\Models\Trip;
use App\Models\driver;
use Illuminate\Support\Facades\Log;
use App\Http\Traits\ApiResponseTrait;
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
    public function update_Trip($data, $id){
        try {
            $trip = Trip::find($id);
            if (!$trip) {
                throw new \Exception('Trip not found');
            }
            
            // جلب رحلة الإياب المرتبطة (إذا كانت موجودة)
            $returnTrip = Trip::where('name', $trip->name)
                              ->where('type', $trip->type === 'go' ? 'back' : 'go')
                              ->where('path_id', $trip->path_id)
                              ->where('bus_id', $trip->bus_id)
                              ->first();
    
            // التحقق من وجود رحلة بنفس المسار والباص
            if (($data['name'] ?? $trip->name) === 'delivery') {
                $existingTripByPath = Trip::where('name', 'delivery')
                                          ->where('type', 'go')
                                          ->where('path_id', $data['path_id'] ?: $trip->path_id)
                                          ->where('id', '!=', $id)
                                          ->exists();
    
                if ($existingTripByPath) {
                    throw new \Exception('هذا المسار مرتبط برحلة توصيل أخرى مسبقاً');
                }
    
                $existingTripByBus = Trip::where('name', 'delivery')
                                         ->where('type', 'go')
                                         ->where('bus_id', $data['bus_id'] ?: $trip->bus_id)
                                         ->where('id', '!=', $id)
                                         ->exists();
                if ($existingTripByBus) {
                    throw new \Exception('هذا الباص مرتبط برحلة توصيل أخرى مسبقاً');
                }
            }
    
            // جلب معلومات الباص
            $busId = $data['bus_id'] ?: $trip->bus_id;
            $bus = Bus::find($busId);
            if (!$bus) {
                throw new \Exception('الباص غير موجود');
            }
    
            // التحقق من عدد المقاعد
            $students = $data['students'] ?? $trip->students->pluck('id')->toArray();
            if (count($students) > $bus->number_of_seats) {
                throw new \Exception('عدد الطلاب يجب أن يساوي عدد مقاعد الباص');
            }
    
            // تحديث معلومات رحلة الذهاب
            $trip->update([
                'name' => $data['name'] ?: $trip->name,
                'path_id' => $data['path_id'] ?: $trip->path_id,
                'bus_id' => $busId,
            ]);
    
            // تحديث علاقات رحلة الذهاب
            $trip->students()->sync($students);
            $trip->supervisors()->sync($data['supervisors'] ?? $trip->supervisors->pluck('id')->toArray());
            $trip->drivers()->sync($data['drivers'] ?? $trip->drivers->pluck('id')->toArray());
    
            // تحديث رحلة الإياب إذا كانت موجودة
            if ($returnTrip) {
                $returnTrip->update([
                    'name' => $data['name'] ?: $returnTrip->name,
                    'type' => $returnTrip->type,
                    'path_id' => $data['path_id'] ?: $returnTrip->path_id,
                    'bus_id' => $busId,
                ]);
    
                // تحديث علاقات رحلة الإياب
                $returnTrip->students()->sync($students);
                $returnTrip->supervisors()->sync($data['supervisors'] ?? $returnTrip->supervisors->pluck('id')->toArray());
                $returnTrip->drivers()->sync($data['drivers'] ?? $returnTrip->drivers->pluck('id')->toArray());
            }
    
            return $trip;
    
        } catch (\Exception $e) {Log::error($e->getMessage());return $this->failed_Response($e->getMessage(), 404);
        } catch (\Throwable $th) {Log::error($th->getMessage());return $this->failed_Response('Something went wrong with updating Trip', 400);
        }
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
