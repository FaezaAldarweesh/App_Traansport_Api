<?php

namespace App\Services\ApiServices;

use App\Models\Bus;
use Illuminate\Support\Facades\Log;
use App\Http\Traits\ApiResponseTrait;

class BusService {
    //trait customize the methods for successful , failed , authentecation responses.
    use ApiResponseTrait;
    /**
     * method to view all buses 
     * @return /Illuminate\Http\JsonResponse if have an error
     */
    public function get_all_Bus(){
        try {
            $bus = Bus::all();
            return $bus;
        } catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('حدث خطأ أثناء محاولة الوصول إلى الباصات', 400);}
    }
    //========================================================================================================================
    /**
     * method to store a new bus
     * @param   $data
     * @return /Illuminate\Http\JsonResponse ig have an error
     */
    public function create_bus($data) {
        try {
            $bus = new Bus(); 

            $bus->name = $data['name'];
            $bus->number_of_seats = $data['number_of_seats'];
            $bus->save();
    
            return $bus; 
        } catch (\Exception $e) { Log::error($e->getMessage()); return $this->failed_Response($e->getMessage(), 404);
        } catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('حدث خطأ أثناء محاولة إضافة باص جديد', 400);}
    }    
    //========================================================================================================================
    /**
     * method to update bus alraedy exist
     * @param  $data
     * @param  $bus_id
     * @return /Illuminate\Http\JsonResponse if have an error
     */
    public function update_bus($data, $bus_id){
        try {  
            $bus = Bus::find($bus_id);
            if(!$bus){
                throw new \Exception('الباص المطلوب غير موجود');
            }

            $bus->name = $data['name'] ?? $bus->name;
            $bus->number_of_seats = $data['number_of_seats'] ?? $bus->number_of_seats;

            $bus->save(); 
            return $bus;

        } catch (\Exception $e) { Log::error($e->getMessage()); return $this->failed_Response($e->getMessage(), 404);
        }catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('حدث خطأ أثناء محاولة التعديل على الباص', 400);}
    }
    //========================================================================================================================
    /**
     * method to show bus alraedy exist
     * @param  $bus_id
     * @return /Illuminate\Http\JsonResponse if have an error
     */
    public function view_bus($bus_id) {
        try {    
            $bus = Bus::find($bus_id);
            if(!$bus){
                throw new \Exception('الباص المطلوب غير موجود');
            }

            return $bus;
        } catch (\Exception $e) { Log::error($e->getMessage()); return $this->failed_Response($e->getMessage(), 404);
        } catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('حدث خطأ أثناء محاولة عرض الباص', 400);}
    }
    //========================================================================================================================
    /**
     * method to soft delete bus alraedy exist
     * @param  $bus_id
     * @return /Illuminate\Http\JsonResponse if have an error
     */
    public function delete_bus($bus_id)
    {
        try {  
            $bus = Bus::find($bus_id);
            if(!$bus){
                throw new \Exception('الباص المطلوب غير موجود');
            }
            $bus->trips()->delete();
            $bus->delete();
            return true;
        }catch (\Exception $e) { Log::error($e->getMessage()); return $this->failed_Response($e->getMessage(), 400);
        } catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('حدث خطأ أثناء محاولة حذف الباص', 400);}
    }
    //========================================================================================================================
    /**
     * method to return all soft delete bus
     * @return /Illuminate\Http\JsonResponse if have an error
     */
    public function all_trashed_bus()
    {
        try {  
            $bus = Bus::onlyTrashed()->get();
            return $bus;
        } catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('حدث خطأ أثناء محاولة الوصول إلى أرشيف الباصات', 400);}
    }
    //========================================================================================================================
    /**
     * method to restore soft delete bus alraedy exist
     * @param   $bus_id
     * @return /Illuminate\Http\JsonResponse if have an error
     */
    public function restore_bus($bus_id)
    {
        try {
            $bus = Bus::onlyTrashed()->find($bus_id);
            if(!$bus){
                throw new \Exception('الباص المطلوب غير موجود');
            }
            $bus->trips()->restore();
            $bus->restore();
            return true;
        }catch (\Exception $e) { Log::error($e->getMessage()); return $this->failed_Response($e->getMessage(), 400);      
        } catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('حدث خطأ أثناء محاولة إستعادة الباص', 400);
        }
    }
    //========================================================================================================================
    /**
     * method to force delete on bus that soft deleted before
     * @param   $bus_id
     * @return /Illuminate\Http\JsonResponse if have an error
     */
    public function forceDelete_bus($bus_id)
    {   
        try {
            $bus = Bus::onlyTrashed()->find($bus_id);
            if(!$bus){
                throw new \Exception('الباص المطلوب غير موجود');
            }
            $bus->trips()->forceDelete();
            return $bus->forceDelete();
        }catch (\Exception $e) { Log::error($e->getMessage()); return $this->failed_Response($e->getMessage(), 400);   
        } catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('حدث خطأ أثناء محاولة حذف أرشيف الباص', 400);}
    }
    //========================================================================================================================

}
