<?php

namespace App\Http\Controllers\ApiController;

use App\Models\Trip;
use App\Http\Resources\TripResources;
use App\Http\Traits\ApiResponseTrait;
use App\Http\Resources\StudentResources;
use App\Services\ApiServices\TripService;
use App\Http\Controllers\ApiController\Controller;
use App\Http\Requests\Trip_Request\Store_Trip_Request;
use App\Http\Requests\Trip_Request\Update_Trip_Request;
use App\Http\Requests\Trip_Request\Update_Status_Trip_Request;

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
    public function show(Trip $Trip)
    {
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

    


    

    //========================================================================================================================
    /**
     * method to update on trip status
     * @param   $Trip_id
     * @return /Illuminate\Http\JsonResponse
     */
    public function update_trip_status(Update_Status_Trip_Request $request,$trip_id)
    {
        $trip = $this->Tripservices->update_trip_status($request->validated(),$trip_id);

        // In case error messages are returned from the services section 
        if ($trip instanceof \Illuminate\Http\JsonResponse) {
            return $trip;
        }
        return $this->success_Response(new TripResources($trip), "تمت عملية التعديل على حالة الرحلة بنجاح", 200);

    }  
    //========================================================================================================================
}
