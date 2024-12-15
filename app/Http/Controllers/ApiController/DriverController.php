<?php

namespace App\Http\Controllers\ApiController;

use App\Services\ApiServices\DriverService;
use App\Http\Controllers\ApiController\Controller;
use App\Http\Traits\ApiResponseTrait;
use App\Http\Resources\DriverResources;
use App\Http\Requests\Driver_Request\Store_Driver_Request;
use App\Http\Requests\Driver_Request\Update_Driver_Request;

class DriverController extends Controller
{
    //trait customize the methods for successful , failed , authentecation responses.
    use ApiResponseTrait;
    protected $driverservices;
    /**
     * construct to inject driver Services 
     * @param DriverService $drivers
     */
    public function __construct(DriverService $driverservices)
    {
        //security middleware
        $this->middleware('security');
        $this->driverservices = $driverservices;
    }
    //===========================================================================================================================
    /**
     * method to view all drivers 
     * @return /Illuminate\Http\JsonResponse
     * driverResources to customize the return responses.
     */
    public function index()
    {  
        $drivers = $this->driverservices->get_all_Drivers();
        return $this->success_Response(DriverResources::collection($drivers),"تمت عملية الوصول للسائقين بنجا", 200);
    }
    //===========================================================================================================================
    /**
     * method to store a new driver
     * @param   Store_Driver_Request $request
     * @return /Illuminate\Http\JsonResponse
     */
    public function store(Store_Driver_Request $request)
    {
        $driver = $this->driverservices->create_Driver($request->validated());
        return $this->success_Response(new DriverResources($driver), "تمت عملية إضافة السائق بنجاح", 201);
    }
    
    //===========================================================================================================================
    /**
     * method to show drivers alraedy exist
     * @param  $drivers_id
     * @return /Illuminate\Http\JsonResponse
     */
    public function show($drivers_id)
    {
        $drivers = $this->driverservices->view_Driver($drivers_id);

        // In case error messages are returned from the services section 
        if ($drivers instanceof \Illuminate\Http\JsonResponse) {
            return $drivers;
        }
            return $this->success_Response(new DriverResources($drivers), "تمت عملية عرض السائق بنجاح", 200);
    }
    //===========================================================================================================================
    /**
     * method to update driver alraedy exist
     * @param  Update_Driver_Request $request
     * @param  $driverroom_id
     * @return /Illuminate\Http\JsonResponse
     */
    public function update(Update_Driver_Request $request, $driverRoom_id)
    {
        $driver = $this->driverservices->update_Driver($request->validated(), $driverRoom_id);

        // In case error messages are returned from the services section 
        if ($driver instanceof \Illuminate\Http\JsonResponse) {
            return $driver;
        }
            return $this->success_Response(new DriverResources($driver), "تمت عملية التعديل على السائق بنجاح", 200);
    }    
    //===========================================================================================================================
    /**
     * method to soft delete driver alraedy exist
     * @param  $driver_id
     * @return /Illuminate\Http\JsonResponse
     */
    public function destroy($driver_id)
    {
        $driver = $this->driverservices->delete_driver($driver_id);

        // In case error messages are returned from the services section 
        if ($driver instanceof \Illuminate\Http\JsonResponse) {
            return $driver;
        }
            return $this->success_Response(null, "تمت عملية إضافة السائق للأرشيف بنجاح", 200);
    }
    //========================================================================================================================
    /**
     * method to return all soft deleted drivers
     * @return /Illuminate\Http\JsonResponse if have an error
     */
    public function all_trashed_driver()
    {
        $drivers = $this->driverservices->all_trashed_driver();
        return $this->success_Response(DriverResources::collection($drivers), "تمت عملية الوصول لأرشيف السائقين بنجاح", 200);
    }
    //========================================================================================================================
    /**
     * method to restore soft deleted driver alraedy exist
     * @param   $driver_id
     * @return /Illuminate\Http\JsonResponse
     */
    public function restore($driver_id)
    {
        $restore = $this->driverservices->restore_driver($driver_id);

        // In case error messages are returned from the services section 
        if ($restore instanceof \Illuminate\Http\JsonResponse) {
            return $restore;
        }
            return $this->success_Response(null, "تمت عملية استعادة السائق بنجاح", 200);
    }
    //========================================================================================================================
    /**
     * method to force delete on driver that soft deleted before
     * @param   $driver_id
     * @return /Illuminate\Http\JsonResponse
     */
    public function forceDelete($driver_id)
    {
        $delete = $this->driverservices->forceDelete_driver($driver_id);

        // In case error messages are returned from the services section 
        if ($delete instanceof \Illuminate\Http\JsonResponse) {
            return $delete;
        }
            return $this->success_Response(null, "تمت عملية حذف السائق بنجاح", 200);
    }
        
    //========================================================================================================================
}
