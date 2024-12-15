<?php

namespace App\Http\Controllers\ApiController;

use App\Services\ApiServices\BusService;
use App\Http\Resources\BusResources;
use App\Http\Controllers\ApiController\Controller;
use App\Http\Traits\ApiResponseTrait;
use App\Http\Requests\Bus_Request\Store_Bus_Request;
use App\Http\Requests\Bus_Request\Update_Bus_Request;

class BusController extends Controller
{
    //trait customize the methods for successful , failed , authentecation responses.
    use ApiResponseTrait;
    protected $busservices;
    /**
     * construct to inject bus Services 
     * @param BusService $busservices
     */
    public function __construct(BusService $busservices)
    {
        //security middleware
        $this->middleware('security');
        $this->busservices = $busservices;
    }
    //===========================================================================================================================
    /**
     * method to view all buses
     * @return /Illuminate\Http\JsonResponse
     * busResources to customize the return responses.
     */
    public function index()
    {  
        $bus = $this->busservices->get_all_Bus();
        return $this->success_Response(BusResources::collection($bus), "تمت عملية الوصول للباصات بنجاح", 200);
    }
    //===========================================================================================================================
    /**
     * method to store a new bus
     * @param   Store_Bus_Request $request
     * @return /Illuminate\Http\JsonResponse
     */
    public function store(Store_Bus_Request $request)
    {
        $bus = $this->busservices->create_bus($request->validated());
        
        // In case error messages are returned from the services section 
        if ($bus instanceof \Illuminate\Http\JsonResponse) {
            return $bus;
        }
        return $this->success_Response(new BusResources($bus), "تمت عملية إضافة الباص بنجاح", 201);
    }
    
    //===========================================================================================================================
    /**
     * method to show bus alraedy exist
     * @param  $bus_id
     * @return /Illuminate\Http\JsonResponse
     */
    public function show($bus_id)
    {
        $bus = $this->busservices->view_bus($bus_id);

        // In case error messages are returned from the services section 
        if ($bus instanceof \Illuminate\Http\JsonResponse) {
            return $bus;
        }
            return $this->success_Response(new BusResources($bus), "تمت عملية عرض الباص بنجاح", 200);
    }
    //===========================================================================================================================
    /**
     * method to update bus alraedy exist
     * @param  Update_Bus_Request $request
     * @param  $bus_id
     * @return /Illuminate\Http\JsonResponse
     */
    public function update(Update_Bus_Request $request, $bus_id)
    {
        $bus = $this->busservices->update_bus($request->validated(), $bus_id);

        // In case error messages are returned from the services section 
        if ($bus instanceof \Illuminate\Http\JsonResponse) {
            return $bus;
        }
            return $this->success_Response(new BusResources($bus), "تمت عملية التعديل على الباص بنجاح", 200);
    }
    //===========================================================================================================================
    /**
     * method to soft delete bus alraedy exist
     * @param  $bus_id
     * @return /Illuminate\Http\JsonResponse
     */
    public function destroy($bus_id)
    {
        $bus = $this->busservices->delete_bus($bus_id);

        // In case error messages are returned from the services section 
        if ($bus instanceof \Illuminate\Http\JsonResponse) {
            return $bus;
        }
            return $this->success_Response(null, "تمت عملية إضافة الباص للأرشيف بنجاح", 200);
    }
    //========================================================================================================================
    /**
     * method to return all soft deleted bus
     * @return /Illuminate\Http\JsonResponse if have an error
     */
    public function all_trashed_bus()
    {
        $bus = $this->busservices->all_trashed_bus();
        return $this->success_Response(BusResources::collection($bus), "تمت عملية الوصول لأرشيف الباصات بنجاح", 200);
    }
    //========================================================================================================================
    /**
     * method to restore soft deleted bus alraedy exist
     * @param   $bus_id
     * @return /Illuminate\Http\JsonResponse
     */
    public function restore($bus_id)
    {
        $restore = $this->busservices->restore_bus($bus_id);

        // In case error messages are returned from the services section 
        if ($restore instanceof \Illuminate\Http\JsonResponse) {
            return $restore;
        }
            return $this->success_Response(null, "تمت عملية استعادة الباص بنجاح", 200);
    }
    //========================================================================================================================
    /**
     * method to force delete on bus that soft deleted before
     * @param   $bus_id
     * @return /Illuminate\Http\JsonResponse
     */
    public function forceDelete($bus_id)
    {
        $delete = $this->busservices->forceDelete_bus($bus_id);

        // In case error messages are returned from the services section 
        if ($delete instanceof \Illuminate\Http\JsonResponse) {
            return $delete;
        }
            return $this->success_Response(null, "تمت عملية حذف الباص بنجاح", 200);
    }
        
    //========================================================================================================================
}
