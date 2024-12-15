<?php

namespace App\Services\ApiServices;

use App\Models\Student;
use Illuminate\Support\Facades\Log;
use App\Http\Traits\ApiResponseTrait;
use App\Models\User;

class StudentService {
    //trait customize the methods for successful , failed , authentecation responses.
    use ApiResponseTrait;
    /**
     * method to view all students 
     * @return /Illuminate\Http\JsonResponse if have an error
     */
    public function get_all_Students(){
        try {
            $student = Student::all();
            return $student;
        } catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('حدث خطأ أثناء محاولة الوصول إلى الطلاب', 400);}
    }
    //========================================================================================================================
    /**
     * method to store a new student
     * @param   $data
     * @return /Illuminate\Http\JsonResponse ig have an error
     */
    public function create_Student($data) {
        try {
            $student = new Student();
            $student->name = $data['name'];
            $student->father_phone = $data['father_phone'];
            $student->mather_phone = $data['mather_phone'];
            $student->longitude = $data['longitude'];
            $student->latitude = $data['latitude'];
            $student->user_id = $data['user_id'];

            $student->save(); 
    
            return $student; 
        } catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('حدث خطأ أثناء محاولة إضافة طالب جديد', 400);}
    }    
    //========================================================================================================================
    /**
     * method to update student alraedy exist
     * @param  $data
     * @param   $student_id
     * @return /Illuminate\Http\JsonResponse if have an error
     */
    public function update_student($data, $student_id){
        try {  
            $student = Student::find($student_id);
            if(!$student){
                throw new \Exception('الطالب المطلوب غير موجود');
            }
            if (isset($data['first_name']) && isset($data['last_name'])) {
                $student->name = ['first_name' => $data['first_name'], 'last_name' => $data['last_name']];
            }
            $student->father_phone = $data['father_phone'] ?? $student->father_phone;
            $student->mather_phone = $data['mather_phone'] ?? $student->mather_phone;
            $student->longitude = $data['longitude'] ?? $student->longitude;
            $student->latitude = $data['latitude'] ?? $student->latitude;
            $student->user_id = $data['user_id'] ?? $student->user_id;
            $student->status = $data['status'] ?? $student->status;

            $student->save(); 
            return $student;
        }catch (\Exception $e) { Log::error($e->getMessage()); return $this->failed_Response($e->getMessage(), 400);   
        }catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('حدث خطأ أثناء محاولة التعديل على الطالب', 400);}
    }
    //========================================================================================================================
    /**
     * method to show studen alraedy exist
     * @param  $studen_id
     * @return /Illuminate\Http\JsonResponse if have an error
     */
    public function view_Student($studen_id) {
        try {    
            $studen = Student::find($studen_id);
            if(!$studen){
                throw new \Exception('الطالب المطلوب غير موجود');
            }
            return $studen;
        } catch (\Exception $e) { Log::error($e->getMessage()); return $this->failed_Response($e->getMessage(), 404);
        } catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('حدث خطأ أثناء محاولة عرض الطالب', 400);}
    }
    //========================================================================================================================
    /**
     * method to soft delete student alraedy exist
     * @param  $student_id
     * @return /Illuminate\Http\JsonResponse if have an error
     */
    public function delete_student($student_id)
    {
        try {  
            $student = Student::find($student_id);
            if(!$student){
                throw new \Exception('الطالب المطلوب غير موجود');
            }

            $student->delete();
            return true;
        }catch (\Exception $e) { Log::error($e->getMessage()); return $this->failed_Response($e->getMessage(), 400);
        } catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('حدث خطأ أثناء محاولة حذف الطالب', 400);}
    }
    //========================================================================================================================
    /**
     * method to return all soft delete students
     * @return /Illuminate\Http\JsonResponse if have an error
     */
    public function all_trashed_student()
    {
        try {  
            $students = Student::onlyTrashed()->get();
            return $students;
        } catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('حدث خطأ أثناء محاولة الوصول إلى أرشيف الطلاب', 400);}
    }
    //========================================================================================================================
    /**
     * method to restore soft delete student alraedy exist
     * @param   $student_id
     * @return /Illuminate\Http\JsonResponse if have an error
     */
    public function restore_student($student_id)
    {
        try {
            $student = Student::onlyTrashed()->find($student_id);
            if(!$student){
                throw new \Exception('الطالب المطلوب غير موجود');
            }
            return $student->restore();
        }catch (\Exception $e) { Log::error($e->getMessage()); return $this->failed_Response($e->getMessage(), 400);      
        } catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('حدث خطأ أثناء محاولة إستعادة الطالب', 400);
        }
    }
    //========================================================================================================================
    /**
     * method to force delete on student that soft deleted before
     * @param   $student_id
     * @return /Illuminate\Http\JsonResponse if have an error
     */
    public function forceDelete_student($student_id)
    {   
        try {
            $student = Student::onlyTrashed()->find($student_id);
            if(!$student){
                throw new \Exception('الطالب المطلوب غير موجود');
            }
 
            return $student->forceDelete();
        }catch (\Exception $e) { Log::error($e->getMessage()); return $this->failed_Response($e->getMessage(), 400);   
        } catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('حدث خطأ أثناء محاولة حذف أرشيف الطالب', 400);}
    }
    //========================================================================================================================
}
