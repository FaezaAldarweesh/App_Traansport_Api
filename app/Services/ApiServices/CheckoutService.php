<?php

namespace App\Services\ApiServices;

use App\Models\Trip;
use App\Models\Checkout;
use Illuminate\Support\Facades\Log;
use App\Http\Traits\ApiResponseTrait;

class CheckoutService {
    //trait customize the methods for successful , failed , authentecation responses.
    use ApiResponseTrait;
    /**
     * method to view all checkouts 
     * @return /Illuminate\Http\JsonResponse if have an error
     */
    public function get_all_Checkout(){
        try {
            $Checkouts = Checkout::all();
            return $Checkouts;
        } catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('Something went wrong with fetche Checkouts', 400);}
    }
    //========================================================================================================================
    /**
     * method to store a new checkout
     * @param   $data
     * @return /Illuminate\Http\JsonResponse ig have an error
     */
    public function create_Checkout($data) {
        try {
            $Checkout = new Checkout(); 
            
            $Checkout->trip_id = $data['trip_id'];
            $Checkout->student_id = $data['student_id'];
            $Checkout->checkout = $data['checkout'];
            $Checkout->note = $data['note'];
            
            $Checkout->save();

            return $Checkout;
        } catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('Something went wrong with create checkout', 400);}
    }    
    //========================================================================================================================
    /**
     * method to update checkout alraedy exist
     * @param  $data
     * @param  $checkout_id
     * @return /Illuminate\Http\JsonResponse if have an error
     */
    public function update_checkout($data, $id){
        try {
            $checkout = Checkout::find($id);
            if(!$checkout){
                throw new \Exception('التفقد المطلوب غير موجود');
            }

            $checkout->trip_id = $data['trip_id'] ?? $checkout->trip_id;
            $checkout->student_id = $data['student_id'] ?? $checkout->student_id;  
            $checkout->checkout = $data['checkout'] ?? $checkout->checkout;
            $checkout->note = $data['note'] ?? $checkout->note;

            $checkout->save(); 
            return $checkout;

        } catch (\Exception $e) { Log::error($e->getMessage()); return $this->failed_Response($e->getMessage(), 404);
        }catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('Something went wrong with view Checkout', 400);}
    }
    //========================================================================================================================
    /**
     * method to show checkout alraedy exist
     * @param  $checkout_id
     * @return /Illuminate\Http\JsonResponse if have an error
     */
    public function view_checkout($checkout_id) {
        try {    
            $checkout = Checkout::find($checkout_id);
            if(!$checkout){
                throw new \Exception('التفقد المطلوب غير موجود');
            }

            return $checkout;
        } catch (\Exception $e) { Log::error($e->getMessage()); return $this->failed_Response($e->getMessage(), 404);
        } catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('حدث خطأ أثناء محاولة عرض التفقد', 400);}
    }
    //========================================================================================================================
    /**
     * method to soft delete Checkout alraedy exist
     * @param  $Checkout_id
     * @return /Illuminate\Http\JsonResponse if have an error
     */
    public function delete_Checkout($Checkout_id)
    {
        try {  
            $Checkout = Checkout::find($Checkout_id);
            if(!$Checkout){
                throw new \Exception('التفقد المطلوب غير موجود');
            }

            $Checkout->delete();
            return true;
        }catch (\Exception $e) { Log::error($e->getMessage()); return $this->failed_Response($e->getMessage(), 400);
        } catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('Something went wrong with deleting Checkout', 400);}
    }
    //========================================================================================================================
    /**
     * method to return all soft delete Checkout
     * @return /Illuminate\Http\JsonResponse if have an error
     */
    public function all_trashed_Checkout()
    {
        try {  
            $Checkout = Checkout::onlyTrashed()->get();
            return $Checkout;
        } catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('Something went wrong with view trashed Checkout', 400);}
    }
    //========================================================================================================================
    /**
     * method to restore soft delete Checkout alraedy exist
     * @param   $Checkout_id
     * @return /Illuminate\Http\JsonResponse if have an error
     */
    public function restore_Checkout($checkout_id)
    {
        try {
            $checkout = Checkout::onlyTrashed()->find($checkout_id);
            if(!$checkout){
                throw new \Exception('التفقد المطلوب غير موجود');
            }

            $checkout->restore();
            return true;
    
        } catch (\Exception $e) {Log::error($e->getMessage());return $this->failed_Response($e->getMessage(), 400);
        } catch (\Throwable $th) {Log::error($th->getMessage());return $this->failed_Response($th->getMessage(), 400);
        }
    }
    //========================================================================================================================
    /**
     * method to force delete on checkout that soft deleted before
     * @param   $checkout_id
     * @return /Illuminate\Http\JsonResponse if have an error
     */
    public function forceDelete_checkout($checkout_id)
    {   
        try {
            $checkout = Checkout::onlyTrashed()->find($checkout_id);
            if(!$checkout){
                throw new \Exception('التفقد المطلوب غير موجود');
            }
            return $checkout->forceDelete();
            
        }catch (\Exception $e) { Log::error($e->getMessage()); return $this->failed_Response($e->getMessage(), 400);   
        } catch (\Throwable $th) { Log::error($th->getMessage()); return $this->failed_Response('Something went wrong with deleting checkout', 400);}
    }
    //========================================================================================================================
}
