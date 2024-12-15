<?php

namespace App\Http\Controllers\ApiController;

use App\Services\ApiServices\TripService;
use App\Http\Controllers\ApiController\Controller;
use App\Http\Resources\TripResources;
use App\Http\Traits\ApiResponseTrait;
use App\Http\Requests\Trip_Request\Store_Trip_Request;
use App\Http\Requests\Trip_Request\Update_Trip_Request;
use App\Http\Requests\Trip_Request\Update_Status_Trip_Request;
use App\Http\Resources\StudentResources;

class TripController extends Controller
{
    //trait customize the methods for successful , failed , authentecation responses.
    use ApiResponseTrait;
    protected $Tripservices;
    /**
     * construct to inject Trip Services 
     * @param TripService $Tripservices
     */
    public function __construct(TripService $Tripservices)
    {
        //security middleware
        $this->middleware('security');
        $this->Tripservices = $Tripservices;
    }
    //===========================================================================================================================
    /**
     * method to view all Trips
     * @return /Illuminate\Http\JsonResponse
     * UserResources to customize the return responses.
     */
    public function index()
    {  
        $Trips = $this->Tripservices->get_all_Trips();
        return $this->success_Response(TripResources::collection($Trips), "تمت عملية الوصول للرحلات بنجاح", 200);
    }
    //===========================================================================================================================
    /**
     * method to store a new Trip
     * @param   Store_Trip_Request $request
     * @return /Illuminate\Http\JsonResponse
     */
    public function store(Store_Trip_Request $request)
    {
        $Trip = $this->Tripservices->create_Trip($request->validated());

        // In case error messages are returned from the services section 
        if ($Trip instanceof \Illuminate\Http\JsonResponse) {
            return $Trip;
        }
            return $this->success_Response(new TripResources($Trip), "تمت عملية إضافة الرحلة بنجاح", 201);
    }
    
    //===========================================================================================================================
    /**
     * method to show Trip alraedy exist
     * @param  $Trip_id
     * @return /Illuminate\Http\JsonResponse
     */
    public function show($Trip_id)
    {
        $Trip = $this->Tripservices->view_Trip($Trip_id);

        // In case error messages are returned from the services section 
        if ($Trip instanceof \Illuminate\Http\JsonResponse) {
            return $Trip;
        }
            return $this->success_Response(new TripResources($Trip), "تمت عملية عرض الرحلة بنجاح", 200);
    }
    //===========================================================================================================================
    /**
     * method to update Trip alraedy exist
     * @param  Update_Trip_Request $request
     * @param  $Trip_id
     * @return /Illuminate\Http\JsonResponse
     */
    public function update(Update_Trip_Request $request, $Trip_id)
    {
        $Trip = $this->Tripservices->update_Trip($request->validated(), $Trip_id);
        
        // In case error messages are returned from the services section 
        if ($Trip instanceof \Illuminate\Http\JsonResponse) {
            return $Trip;
        }
            return $this->success_Response(new TripResources($Trip), "تمت عملية التعديل على الرحلة بنجاح", 200);
    }
    //===========================================================================================================================
    /**
     * method to soft delete Trip alraedy exist
     * @param  $Trip_id
     * @return /Illuminate\Http\JsonResponse
     */
    public function destroy($Trip_id)
    {
        $Trip = $this->Tripservices->delete_Trip($Trip_id);

        // In case error messages are returned from the services section 
        if ($Trip instanceof \Illuminate\Http\JsonResponse) {
            return $Trip;
        }
            return $this->success_Response(null, "تمت عملية إضافة الرحلة للأرشيف بنجاح", 200);
    }
    //========================================================================================================================
    /**
     * method to return all soft deleted Trips
     * @return /Illuminate\Http\JsonResponse if have an error
     */
    public function all_trashed_Trip()
    {
        $Trips = $this->Tripservices->all_trashed_Trip();
        return $this->success_Response(TripResources::collection($Trips), "تمت عملية الوصول لأرشيف الرحلات بنجاح", 200);
    }
    //========================================================================================================================
    /**
     * method to restore soft deleted Trip alraedy exist
     * @param   $Trip_id
     * @return /Illuminate\Http\JsonResponse
     */
    public function restore($Trip_id)
    {
        $delete = $this->Tripservices->restore_Trip($Trip_id);

        // In case error messages are returned from the services section 
        if ($delete instanceof \Illuminate\Http\JsonResponse) {
            return $delete;
        }
            return $this->success_Response(null, "تمت عملية استعادة الرحلة بنجاح", 200);
    }
    //========================================================================================================================
    /**
     * method to force delete on Trip that soft deleted before
     * @param   $Trip_id
     * @return /Illuminate\Http\JsonResponse
     */
    public function forceDelete($Trip_id)
    {
        $delete = $this->Tripservices->forceDelete_Trip($Trip_id);

        // In case error messages are returned from the services section 
        if ($delete instanceof \Illuminate\Http\JsonResponse) {
            return $delete;
        }
            return $this->success_Response(null, "تمت عملية حذف الرحلة بنجاح", 200);
    }
        
    //========================================================================================================================

    


    

    // //===========================================================================================================================
    // /**
    //  * method to bind  trip with bus , student , supervisor , driver
    //  * @param   Store_Bind_Request $request
    //  * @return /Illuminate\Http\JsonResponse
    //  */
    // public function bind(Store_Bind_Request $request)
    // {
    //     $Trip = $this->Tripservices->bind($request->validated());

    //     // In case error messages are returned from the services section 
    //     if ($Trip instanceof \Illuminate\Http\JsonResponse) {
    //         return $Trip;
    //     }
    //         return $this->success_Response(new TripResources($Trip), "Trip created successfully.", 201);
    // }
    // //========================================================================================================================
    // /**
    //  * method to get all students sorte by distance
    //  * @param   $Trip_id
    //  * @param   $latitude
    //  * @param   $longitude
    //  * @return /Illuminate\Http\JsonResponse
    //  */
    // public function list_of_students($trip_id, $latitude, $longitude)
    // {
    //     $students = $this->Tripservices->list_of_students($trip_id, $latitude, $longitude);
    //     return $this->success_Response(StudentResources::collection($students), "all students successfully", 200);

    // }  
    // //========================================================================================================================
    // /**
    //  * method to update on trip status
    //  * @param   $Trip_id
    //  * @return /Illuminate\Http\JsonResponse
    //  */
    // public function update_trip_status(Update_Status_Trip_Request $request,$trip_id)
    // {
    //     $trip = $this->Tripservices->update_trip_status($request->validated(),$trip_id);

    //     // In case error messages are returned from the services section 
    //     if ($trip instanceof \Illuminate\Http\JsonResponse) {
    //         return $trip;
    //     }
    //     return $this->success_Response(new TripResources($trip), "trip status update successfully", 200);

    // }  
    // //========================================================================================================================
    //     /**
    //  * method to update on trip status
    //  * @param   $Trip_id
    //  * @return /Illuminate\Http\JsonResponse
    //  */
    // public function All_students_belong_to_specific_trip($trip_id)
    // {
    //     $students = $this->Tripservices->All_students_belong_to_specific_trip($trip_id);

    //     // In case error messages are returned from the services section 
    //     if ($students instanceof \Illuminate\Http\JsonResponse) {
    //         return $students;
    //     }
    //     return $this->success_Response(StudentResources::collection($students), "All students that belong to a specific trip fetching successfully", 200);

    // }  
    // //========================================================================================================================
}
