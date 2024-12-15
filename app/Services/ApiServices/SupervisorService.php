<?php

namespace App\Services\ApiServices;

use App\Models\Supervisor;
use Illuminate\Support\Facades\Log;
use App\Http\Traits\ApiResponseTrait;

class SupervisorService {
    //trait customize the methods for successful , failed , authentecation responses.
    use ApiResponseTrait;
    /**
     * method to view all Supervisors 
     * @return /Illuminate\Http\JsonResponse if have an error
     */
    public function get_all_Supervisors(){
        try {
            $Supervisor = Supervisor::all();
            return $Supervisor;
        } catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('حدث خطأ أثناء محاولة الوصول إلى المشرف', 400);}
    }
    //========================================================================================================================
    /**
     * method to store a new Supervisor
     * @param   $data
     * @return /Illuminate\Http\JsonResponse ig have an error
     */
    public function create_Supervisor($data) {
        try {
            $Supervisor = new Supervisor();
            $Supervisor->name = ['first_name' => $data['first_name'], 'last_name' => $data['last_name']];
            $Supervisor->username = $data['username'];
            $Supervisor->password = $data['password'];
            $Supervisor->location = $data['location'];
            $Supervisor->phone = $data['phone'];
            
            $Supervisor->save(); 
    
            return $Supervisor; 
        } catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('حدث خطأ أثناء محاولة إضافة مشرف جديد', 400);}
    }    
    //========================================================================================================================
    /**
     * method to update Supervisor alraedy exist
     * @param  $data
     * @param  $Supervisor_id
     * @return /Illuminate\Http\JsonResponse if have an error
     */
    public function update_Supervisor($data,$Supervisor_id){
        try {  
            $Supervisor = Supervisor::find($Supervisor_id);
            if(!$Supervisor){
                throw new \Exception('المشرف المطلوب غير موجود');
            }
            if (isset($data['first_name']) && isset($data['last_name'])) {
                $Supervisor->name = ['first_name' => $data['first_name'], 'last_name' => $data['last_name']];
            }
            $Supervisor->username = $data['username'] ?? $Supervisor->username;
            $Supervisor->password = $data['password'] ?? $Supervisor->password;  
            $Supervisor->location = $data['location'] ?? $Supervisor->location;  
            $Supervisor->phone = $data['phone'] ?? $Supervisor->phone;  

            $Supervisor->save();  
            return $Supervisor;
        } catch (\Exception $e) { Log::error($e->getMessage()); return $this->failed_Response($e->getMessage(), 404);
        }catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('حدث خطأ أثناء محاولة التعديل على المشرف', 400);}
    }
    //========================================================================================================================
    /**
     * method to show Supervisor alraedy exist
     * @param  $Supervisor_id
     * @return /Illuminate\Http\JsonResponse if have an error
     */
    public function view_Supervisor($Supervisor_id) {
        try {    
            $Supervisor = Supervisor::find($Supervisor_id);
            if(!$Supervisor){
                throw new \Exception('المشرف المطلوب غير موجود');
            }
            return $Supervisor;
        } catch (\Exception $e) { Log::error($e->getMessage()); return $this->failed_Response($e->getMessage(), 404);
        } catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('حدث خطأ أثناء محاولة عرض المشرف', 400);}
    }
    //========================================================================================================================
    /**
     * method to soft delete Supervisor alraedy exist
     * @param  Supervisor $Supervisor
     * @return /Illuminate\Http\JsonResponse if have an error
     */
    public function delete_Supervisor($Supervisor_id)
    {
        try {  
            $Supervisor = Supervisor::find($Supervisor_id);
            if(!$Supervisor){
                throw new \Exception('المشرف المطلوب غير موجود');
            }

            $Supervisor->delete();
            return true;
        }catch (\Exception $e) { Log::error($e->getMessage()); return $this->failed_Response($e->getMessage(), 400);
        } catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('حدث خطأ أثناء محاولة حذف المشرف', 400);}
    }
    //========================================================================================================================
    /**
     * method to return all soft delete Supervisors
     * @return /Illuminate\Http\JsonResponse if have an error
     */
    public function all_trashed_Supervisor()
    {
        try {  
            $Supervisors = Supervisor::onlyTrashed()->get();
            return $Supervisors;
        } catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('حدث خطأ أثناء محاولة الوصول إلى أرشيف المشرفين', 400);}
    }
    //========================================================================================================================
    /**
     * method to restore soft delete Supervisor alraedy exist
     * @param   $Supervisor_id
     * @return /Illuminate\Http\JsonResponse if have an error
     */
    public function restore_Supervisor($Supervisor_id)
    {
        try {
            $Supervisor = Supervisor::onlyTrashed()->find($Supervisor_id);
            if(!$Supervisor){
                throw new \Exception('المشرف المطلوب غير موجود');
            }
            return $Supervisor->restore();
        }catch (\Exception $e) { Log::error($e->getMessage()); return $this->failed_Response($e->getMessage(), 400);   
        } catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('حدث خطأ أثناء محاولة إستعادة المشرف', 400);
        }
    }
    //========================================================================================================================
    /**
     * method to force delete on Supervisor that soft deleted before
     * @param   $Supervisor_id
     * @return /Illuminate\Http\JsonResponse if have an error
     */
    public function forceDelete_Supervisor($Supervisor_id)
    {   
        try {
            $Supervisor = Supervisor::onlyTrashed()->find($Supervisor_id);
            if(!$Supervisor){
                throw new \Exception('المشرف المطلوب غير موجود');
            }

            return $Supervisor->forceDelete();
        }catch (\Exception $e) { Log::error($e->getMessage()); return $this->failed_Response($e->getMessage(), 400);   
        } catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('حدث خطأ أثناء محاولة حذف أرشيف المشرف', 400);}
    }
    //========================================================================================================================

}
