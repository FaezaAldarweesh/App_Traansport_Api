<?php

namespace App\Http\Controllers\ApiController;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\UserResources;
use App\Http\Traits\ApiResponseTrait;
use App\Http\Resources\UserShowResources;
use App\Services\ApiServices\UserService;
use App\Http\Controllers\ApiController\Controller;
use App\Http\Requests\User_Rqeuests\Store_User_Request;
use App\Http\Requests\User_Rqeuests\Update_User_Request;

class UserController extends Controller
{
    //trait customize the methods for successful , failed , authentecation responses.
    use ApiResponseTrait;
    protected $userservices;
    /**
     * construct to inject User Services 
     * @param UserService $userservices
     */
    public function __construct(UserService $userservices)
    {
        //security middleware
        $this->middleware('security');
        $this->userservices = $userservices;
    }
    //===========================================================================================================================
    /**
     * method to view all users with a filter on role
     * @param   Request $request
     * @return /Illuminate\Http\JsonResponse
     * UserResources to customize the return responses.
     */
    public function index(Request $request)
    {  
        $users = $this->userservices->get_all_Users($request->input('role'));
        return $this->success_Response(UserResources::collection($users), "تمت عملية الوصول للمستخدميين بنجاح", 200);
    }
    //===========================================================================================================================
    /**
     * method to store a new user
     * @param   Store_User_Request $request
     * @return /Illuminate\Http\JsonResponse
     */
    public function store(Store_User_Request $request)
    {
        $user = $this->userservices->create_User($request->validated());
        return $this->success_Response(new UserResources($user), "تمت عملية إضافة مستخدم جديد بنجاح", 201);
    }
    
    //===========================================================================================================================
    /**
     * method to show user alraedy exist
     * @param  $user_id
     * @return /Illuminate\Http\JsonResponse
     */
    public function show(User $user)
    {
        $user = User::where('id' , '=' , $user->id)->with('students')->first();
        return $this->success_Response(new UserShowResources($user), "تمت عملية عرض المستخدم بنجاح", 200);
    }
    //===========================================================================================================================
    /**
     * method to update user alraedy exist
     * @param  Update_User_Request $request
     * @param  $user_id
     * @return /Illuminate\Http\JsonResponse
     */
    public function update(Update_User_Request $request, User $user)
    {
        $user = $this->userservices->update_User($request->validated(), $user); 
       return $this->success_Response(new UserResources($user), "تمت عملية التعديل على المستخدم بنجاح", 200);
    }
    //===========================================================================================================================
    /**
     * method to soft delete user alraedy exist
     * @param  $user_id
     * @return /Illuminate\Http\JsonResponse
     */
    public function destroy(User $user)
    {
        $user = $this->userservices->delete_user($user);
        // In case error messages are returned from the services section 
        if ($user instanceof \Illuminate\Http\JsonResponse) {
            return $user;
        }
            return $this->success_Response(null, "تمت عملية إضافة المستخدم إلى الأرشيف بنجاح", 200);
    }
    //========================================================================================================================
    /**
     * method to return all soft deleted users
     * @return /Illuminate\Http\JsonResponse if have an error
     */
    public function all_trashed_user()
    {
        $users = $this->userservices->all_trashed_user();
        return $this->success_Response(UserResources::collection($users), "تمت عملية الوصول إلى أرشيف المستخدميين بنجاح", 200);
    }
    //========================================================================================================================
    /**
     * method to restore soft deleted user alraedy exist
     * @param   $user_id
     * @return /Illuminate\Http\JsonResponse
     */
    public function restore($user_id)
    {
        $delete = $this->userservices->restore_user($user_id);

        // In case error messages are returned from the services section 
        if ($delete instanceof \Illuminate\Http\JsonResponse) {
            return $delete;
        }
            return $this->success_Response(null, "تمت عملية استعادة المستخدم بنجاح", 200);
    }
    //========================================================================================================================
    /**
     * method to force delete on user that soft deleted before
     * @param   $user_id
     * @return /Illuminate\Http\JsonResponse
     */
    public function forceDelete($user_id)
    {
        $delete = $this->userservices->forceDelete_user($user_id);

        // In case error messages are returned from the services section 
        if ($delete instanceof \Illuminate\Http\JsonResponse) {
            return $delete;
        }
            return $this->success_Response(null, "تمت عملية حذف المستخدم بنجاح", 200);
    }
        
    //========================================================================================================================
}
