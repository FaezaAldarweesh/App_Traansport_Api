<?php

namespace App\Http\Controllers\ApiController;

use App\Services\ApiServices\StudentService;
use App\Http\Controllers\ApiController\Controller;
use App\Http\Traits\ApiResponseTrait;
use App\Http\Resources\StudentResources;
use App\Http\Requests\Student_Request\Store_Student_Request;
use App\Http\Requests\Student_Request\Update_Student_Request;

class StudentController extends Controller
{
    //trait customize the methods for successful , failed , authentecation responses.
    use ApiResponseTrait;
    protected $studentservices;
    /**
     * construct to inject Student Services 
     * @param StudentService $studentservices
     */
    public function __construct(StudentService $studentservices)
    {
        //security middleware
        $this->middleware('security');
        $this->studentservices = $studentservices;
    }
    //===========================================================================================================================
    /**
     * method to view all students
     * @return /Illuminate\Http\JsonResponse
     * StudentResources to customize the return responses.
     */
    public function index()
    {  
        $students = $this->studentservices->get_all_Students();
        return $this->success_Response(StudentResources::collection($students), "تمت عملية الوصول للطلاب بنجاح", 200);
    }
    //===========================================================================================================================
    /**
     * method to store a new Student
     * @param   Store_Student_Request $request
     * @return /Illuminate\Http\JsonResponse
     */
    public function store(Store_Student_Request $request)
    {
        $student = $this->studentservices->create_Student($request->validated());
        return $this->success_Response(new StudentResources($student), "تمت عملية إضافة الطالب بنجاح", 201);
    }
    
    //===========================================================================================================================
    /**
     * method to show student alraedy exist
     * @param  $student_id
     * @return /Illuminate\Http\JsonResponse
     */
    public function show($student_id)
    {
        $student = $this->studentservices->view_student($student_id);

        // In case error messages are returned from the services section 
        if ($student instanceof \Illuminate\Http\JsonResponse) {
            return $student;
        }
            return $this->success_Response(new StudentResources($student), "تمت عملية عرض الطالب بنجاح", 200);
    }
    //===========================================================================================================================
    /**
     * method to update student alraedy exist
     * @param  Update_Student_Request $request
     * @param  $student_id
     * @return /Illuminate\Http\JsonResponse
     */
    public function update(Update_Student_Request $request, $student_id)
    {
        $student = $this->studentservices->update_Student($request->validated(), $student_id);

        // In case error messages are returned from the services section 
        if ($student instanceof \Illuminate\Http\JsonResponse) {
            return $student;
        }
            return $this->success_Response(new StudentResources($student), "تمت عملية التعديل على الطالب بنجاح", 200);
    }
    //===========================================================================================================================
    /**
     * method to soft delete student alraedy exist
     * @param  $student_id
     * @return /Illuminate\Http\JsonResponse
     */
    public function destroy($student_id)
    {
        $student = $this->studentservices->delete_student($student_id);

        // In case error messages are returned from the services section 
        if ($student instanceof \Illuminate\Http\JsonResponse) {
            return $student;
        }
            return $this->success_Response(null, "تمت عملية إضافة الطالب للأرشيف بنجاح", 200);
    }
    //========================================================================================================================
    /**
     * method to return all soft deleted students
     * @return /Illuminate\Http\JsonResponse if have an error
     */
    public function all_trashed_student()
    {
        $students = $this->studentservices->all_trashed_student();
        return $this->success_Response(StudentResources::collection($students), "تمت عملية الوصول لأرشيف الطلاب بنجاح", 200);
    }
    //========================================================================================================================
    /**
     * method to restore soft deleted student alraedy exist
     * @param   $student_id
     * @return /Illuminate\Http\JsonResponse
     */
    public function restore($student_id)
    {
        $restore = $this->studentservices->restore_student($student_id);

        // In case error messages are returned from the services section 
        if ($restore instanceof \Illuminate\Http\JsonResponse) {
            return $restore;
        }
            return $this->success_Response(null, "تمت عملية استعادة الطالب بنجاح", 200);
    }
    //========================================================================================================================
    /**
     * method to force delete on student that soft deleted before
     * @param   $student_id
     * @return /Illuminate\Http\JsonResponse
     */
    public function forceDelete($student_id)
    {
        $delete = $this->studentservices->forceDelete_student($student_id);

        // In case error messages are returned from the services section 
        if ($delete instanceof \Illuminate\Http\JsonResponse) {
            return $delete;
        }
            return $this->success_Response(null, "تمت عملية حذف الطالب بنجاح", 200);
    }
        
    //========================================================================================================================
}
