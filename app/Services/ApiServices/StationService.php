<?php

namespace App\Services\ApiServices;

use Illuminate\Support\Facades\Log;
use App\Http\Traits\ApiResponseTrait;
use App\Models\Station;
use Illuminate\Support\Facades\Request;

class StationService {
    //trait customize the methods for successful , failed , authentecation responses.
    use ApiResponseTrait;
    /**
     * method to view all stations 
     * @param   Request $request
     * @return /Illuminate\Http\JsonResponse if have an error
     */
    public function get_all_Stations(){
        try {
            $station = Station::all();
            return $station;
        } catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('حدث خطأ أثناء محاولة الوصول إلى المحطة', 400);}
    }
    //========================================================================================================================
    /**
     * method to store a new station
     * @param   $data
     * @return /Illuminate\Http\JsonResponse ig have an errorc
     */
    public function create_Station($data) {
        try {
            $station = new Station();
            $station->name = $data['name'];
            $station->path_id = $data['path_id'];
            
            $station->save(); 
    
            return $station; 
        } catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('حدث خطأ أثناء محاولة إضافة محطة جديدة', 400);}
    }    
    //========================================================================================================================
    /**
     * method to updstation alraedy exist
     * @param  $data
     * @param  $station_id
     * @return /Illuminate\Http\JsonResponse if have an error
     */
    public function update_Station($data, $station_id){
        try {  
            $station = Station::find($station_id);
            if(!$station){
                throw new \Exception('المحطة المطلوبة غير موجودة');
            }
            $station->name = $data['name'] ?? $station->name;
            $station->path_id = $data['path_id'] ?? $station->path_id;
            
            $station->save(); 
            return $station;

        } catch (\Exception $e) { Log::error($e->getMessage()); return $this->failed_Response($e->getMessage(), 404);
        }catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('حدث خطأ أثناء محاولة التعديل على المحطة', 400);}
    }
    //========================================================================================================================
    /**
     * method to show station alraedy exist
     * @param  $station_id
     * @return /Illuminate\Http\JsonResponse if have an error
     */
    public function view_Station($station_id) {
        try {    
            $station = Station::find($station_id);
            if(!$station){
                throw new \Exception('المحطة المطلوبة غير موجودة');
            }
            return $station;
        } catch (\Exception $e) { Log::error($e->getMessage()); return $this->failed_Response($e->getMessage(), 404);
        } catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('حدث خطأ أثناء محاولة عرض المحطة', 400);}
    }
    //========================================================================================================================
    /**
     * method to soft delete station alraedy exist
     * @param  $station_id
     * @return /Illuminate\Http\JsonResponse if have an error
     */
    public function delete_station($station_id)
    {
        try {  
            $station = Station::find($station_id);
            if(!$station){
                throw new \Exception('المحطة المطلوبة غير موجودة');
            }

            $station->delete();
            return true;
        }catch (\Exception $e) { Log::error($e->getMessage()); return $this->failed_Response($e->getMessage(), 400);
        } catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('حدث خطأ أثناء محاولة حذف المحطة', 400);}
    }
    //========================================================================================================================
    /**
     * method to return all soft delete stations
     * @return /Illuminate\Http\JsonResponse if have an error
     */
    public function all_trashed_station()
    {
        try {  
            $stations = Station::onlyTrashed()->get();
            return $stations;
        } catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('حدث خطأ أثناء محاولة الوصول إلى المحطات', 400);}
    }
    //========================================================================================================================
    /**
     * method to restore soft delete station alraedy exist
     * @param   $station_id
     * @return /Illuminate\Http\JsonResponse if have an error
     */
    public function restore_station($station_id)
    {
        try {
            $station = Station::onlyTrashed()->find($station_id);
            if(!$station){
                throw new \Exception('المحطة المطلوبة غير موجودة');
            }
            return $station->restore();
        }catch (\Exception $e) { Log::error($e->getMessage()); return $this->failed_Response($e->getMessage(), 400);      
        } catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('حدث خطأ أثناء محاولة إستعادة المحطة', 400);
        }
    }
    //========================================================================================================================
    /**
     * method to force delete on station that soft deleted before
     * @param   $station_id
     * @return /Illuminate\Http\JsonResponse if have an error
     */
    public function forceDelete_station($station_id)
    {   
        try {
            $station = Station::onlyTrashed()->find($station_id);
            if(!$station){
                throw new \Exception('المحطة المطلوبة غير موجودة');
            }
 
            return $station->forceDelete();
        }catch (\Exception $e) { Log::error($e->getMessage()); return $this->failed_Response($e->getMessage(), 400);   
        } catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('حدث خطأ أثناء محاولة حذف أرشيف المحطات', 400);}
    }
    //========================================================================================================================

}
