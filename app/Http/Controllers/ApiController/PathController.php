<?php

namespace App\Http\Controllers\ApiController;

use App\Services\ApiServices\PathService;
use App\Http\Controllers\ApiController\Controller;
use App\Http\Resources\PathResources;
use App\Http\Traits\ApiResponseTrait;
use App\Http\Requests\Path_Request\Store_Path_Request;
use App\Http\Requests\Path_Request\Update_Path_Request;

class PathController extends Controller
{
    //trait customize the methods for successful , failed , authentecation responses.
    use ApiResponseTrait;
    protected $pathservices;
    /**
     * construct to inject Path Services 
     * @param PathService $pathservices
     */
    public function __construct(PathService $pathservices)
    {
        //security middleware
        $this->middleware('security');
        $this->pathservices = $pathservices;
    }
    //===========================================================================================================================
    /**
     * method to view all paths 
     * @return /Illuminate\Http\JsonResponse
     * PathResources to customize the return responses.
     */
    public function index()
    {  
        $paths = $this->pathservices->get_all_Paths();
        return $this->success_Response(PathResources::collection($paths), "تمت عملية الوصول للمسارات بنجاح", 200);
    }
    //===========================================================================================================================
    /**
     * method to store a new Patth
     * @param   Store_Path_Request $request
     * @return /Illuminate\Http\JsonResponse
     */
    public function store(Store_Path_Request $request)
    {
        $path = $this->pathservices->create_Path($request->validated());
        return $this->success_Response(new PathResources($path), "تمت عملية إضافة المسار بنجاح", 201);
    }
    
    //===========================================================================================================================
    /**
     * method to show path alraedy exist
     * @param  $path_id
     * @return /Illuminate\Http\JsonResponse
     */
    public function show($path_id)
    {
        $path = $this->pathservices->view_path($path_id);

        // In case error messages are returned from the services section 
        if ($path instanceof \Illuminate\Http\JsonResponse) {
            return $path;
        }
            return $this->success_Response(new PathResources($path), "تمت عملية عرض المسار بنجاح", 200);
    }
    //===========================================================================================================================
    /**
     * method to update path alraedy exist
     * @param  Update_Path_Request $request
     * @param  $path_id
     * @return /Illuminate\Http\JsonResponse
     */
    public function update(Update_Path_Request $request, $path_id)
    {
        $path = $this->pathservices->update_Path($request->validated(), $path_id);

        // In case error messages are returned from the services section 
        if ($path instanceof \Illuminate\Http\JsonResponse) {
            return $path;
        }
            return $this->success_Response(new PathResources($path), "تمت عملية التعديل على المسار بنجاح", 200);
    }
    //===========================================================================================================================
    /**
     * method to soft delete path alraedy exist
     * @param  $path_id
     * @return /Illuminate\Http\JsonResponse
     */
    public function destroy($path_id)
    {
        $path = $this->pathservices->delete_path($path_id);

        // In case error messages are returned from the services section 
        if ($path instanceof \Illuminate\Http\JsonResponse) {
            return $path;
        }
            return $this->success_Response(null, "تمت عملية إضافة المسار للأرشيف بنجاح", 200);
    }
    //========================================================================================================================
    /**
     * method to return all soft deleted paths
     * @return /Illuminate\Http\JsonResponse if have an error
     */
    public function all_trashed_path()
    {
        $paths = $this->pathservices->all_trashed_path();
        return $this->success_Response(PathResources::collection($paths), "تمت عملية الوصول لأرشيف المسارات بنجاح", 200);
    }
    //========================================================================================================================
    /**
     * method to restore soft deleted path alraedy exist
     * @param   $path_id
     * @return /Illuminate\Http\JsonResponse
     */
    public function restore($path_id)
    {
        $restore = $this->pathservices->restore_path($path_id);

        // In case error messages are returned from the services section 
        if ($restore instanceof \Illuminate\Http\JsonResponse) {
            return $restore;
        }
            return $this->success_Response(null, "تمت عملية استعادة المسار بنجاح", 200);
    }
    //========================================================================================================================
    /**
     * method to force delete on path that soft deleted before
     * @param   $path_id
     * @return /Illuminate\Http\JsonResponse
     */
    public function forceDelete($path_id)
    {
        $delete = $this->pathservices->forceDelete_path($path_id);

        // In case error messages are returned from the services section 
        if ($delete instanceof \Illuminate\Http\JsonResponse) {
            return $delete;
        }
            return $this->success_Response(null, "تمت عملية حذف المسار بنجاح", 200);
    }
        
    //========================================================================================================================
}
