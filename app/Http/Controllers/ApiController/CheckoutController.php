<?php

namespace App\Http\Controllers\ApiController;

use App\Http\Traits\ApiResponseTrait;
use App\Http\Resources\CheckoutResources;
use App\Services\ApiServices\CheckoutService;
use App\Http\Requests\Checkout_Request\Store_Checkout_Request;
use App\Http\Requests\Checkout_Request\Update_Checkout_Request;

class CheckoutController extends Controller
{
    //trait customize the methods for successful , failed , authentecation responses.
    use ApiResponseTrait;
    protected $checkoutservices;
    /**
     * construct to inject checkout Services 
     * @param CheckoutService $checkoutservices
     */
    public function __construct(CheckoutService $checkoutservices)
    {
        //security middleware
        $this->middleware('security');
        $this->checkoutservices = $checkoutservices;
    }
    //===========================================================================================================================
    /**
     * method to view all checkouts
     * @return /Illuminate\Http\JsonResponse
     * checkoutResources to customize the return responses.
     */
    public function index()
    {  
        $checkout = $this->checkoutservices->get_all_Checkout();
        return $this->success_Response(CheckoutResources::collection($checkout), "تم عملية الوصول للتفقد بنجاح", 200);
    }
    //===========================================================================================================================
    /**
     * method to store a new checkout
     * @param   Store_Checkout_Request $request
     * @return /Illuminate\Http\JsonResponse
     */
    public function store(Store_Checkout_Request $request)
    {
        $checkout = $this->checkoutservices->create_checkout($request->validated());
        return $this->success_Response(new CheckoutResources($checkout), "تمت عملية إضافة التفقد بنجاح", 201);
    }
    
    //===========================================================================================================================
    /**
     * method to show checkout alraedy exist
     * @param  $checkout_id
     * @return /Illuminate\Http\JsonResponse
     */
    public function show($checkout_id)
    {
        $checkout = $this->checkoutservices->view_checkout($checkout_id);

        // In case error messages are returned from the services section 
        if ($checkout instanceof \Illuminate\Http\JsonResponse) {
            return $checkout;
        }
            return $this->success_Response(new CheckoutResources($checkout), "تمت عملية عرض التفقد بنجاح", 200);
    }
    //===========================================================================================================================
    /**
     * method to update checkout alraedy exist
     * @param  Update_Checkout_Request $request
     * @param  $checkout_id
     * @return /Illuminate\Http\JsonResponse
     */
    public function update(Update_Checkout_Request $request, $checkout_id)
    {
        $checkout = $this->checkoutservices->update_checkout($request->validated(), $checkout_id);

        // In case error messages are returned from the services section 
        if ($checkout instanceof \Illuminate\Http\JsonResponse) {
            return $checkout;
        }
            return $this->success_Response(new CheckoutResources($checkout), "تمت عملية التعديل على التفقد بنجاح", 200);
    }
    //===========================================================================================================================
    /**
     * method to soft delete checkout alraedy exist
     * @param  $checkout_id
     * @return /Illuminate\Http\JsonResponse
     */
    public function destroy($checkout_id)
    {
        $checkout = $this->checkoutservices->delete_checkout($checkout_id);

        // In case error messages are returned from the services section 
        if ($checkout instanceof \Illuminate\Http\JsonResponse) {
            return $checkout;
        }
            return $this->success_Response(null, "تمت عملية إضافة التفقد للأرشيف بنجاح", 200);
    }
    //========================================================================================================================
    /**
     * method to return all soft deleted checkout
     * @return /Illuminate\Http\JsonResponse if have an error
     */
    public function all_trashed_checkout()
    {
        $checkout = $this->checkoutservices->all_trashed_checkout();
        return $this->success_Response(CheckoutResources::collection($checkout), "تمت عملية الوصول لأرشيف التفقد بنجاح", 200);
    }
    //========================================================================================================================
    /**
     * method to restore soft deleted checkout alraedy exist
     * @param   $checkout_id
     * @return /Illuminate\Http\JsonResponse
     */
    public function restore($checkout_id)
    {
        $restore = $this->checkoutservices->restore_checkout($checkout_id);

        // In case error messages are returned from the services section 
        if ($restore instanceof \Illuminate\Http\JsonResponse) {
            return $restore;
        }
            return $this->success_Response(null, "تمت عملية استعادة التفقد بنجاح", 200);
    }
    //========================================================================================================================
    /**
     * method to force delete on checkout that soft deleted before
     * @param   $checkout_id
     * @return /Illuminate\Http\JsonResponse
     */
    public function forceDelete($checkout_id)
    {
        $delete = $this->checkoutservices->forceDelete_checkout($checkout_id);

        // In case error messages are returned from the services section 
        if ($delete instanceof \Illuminate\Http\JsonResponse) {
            return $delete;
        }
            return $this->success_Response(null, "تمت عملية حذف التفقد بنجاح", 200);
    }
        
    //========================================================================================================================
}
