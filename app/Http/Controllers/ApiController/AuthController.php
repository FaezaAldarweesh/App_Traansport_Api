<?php

namespace App\Http\Controllers\ApiController;

use App\Services\ApiServices\AuthService;
use App\Http\Controllers\ApiController\Controller;
use App\Http\Traits\ApiResponseTrait;
use App\Http\Resources\registerResource;
use App\Http\Requests\Auth_Requests\loginRequest;

class AuthController extends Controller
{
    //trait customize the methods for successful , failed , authentecation responses.
    use ApiResponseTrait;
    protected $authservices;    
    /**
     * construct to inject auth services
     * @param AuthService $authservices
     */
    public function __construct(AuthService $authservices)
    {
        $this->middleware('security');
        $this->authservices = $authservices;
        $this->middleware('auth:api', ['except' => ['login','register']]);
    }
    //===========================================================================================================================
    /**
     * function to login users
     * @param loginRequest $request
     * @return /Illuminate\Http\JsonResponse
     */
    public function login(loginRequest $request)
    {        
        $token = $this->authservices->login($request->validated());
        // In case error messages are returned from the services section 
        if ($token instanceof \Illuminate\Http\JsonResponse) {
            return $token;
        }
        return $this->api_Response(null,$token,"login has been successfully",200);
    }
//===========================================================================================================================
    /**
     * function to logout users
     * @return /Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        $this->authservices->logout();
        return $this->api_Response(null,null,"Successfully logged out",200);
    }
//===========================================================================================================================
    /**
     * function to refresh token
     * @return /Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        $result = $this->authservices->refresh();
        return $this->api_Response(new registerResource($result['user']),$result['token']," refresh has been successfully",201);
    }
//===========================================================================================================================
}
