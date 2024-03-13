<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Traits\apiResponseTrait;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class AdminController extends Controller
{

    use apiResponseTrait;
    public function __construct()
    {
        $this->middleware('jwt.verify:admin-api', ['except' => ['login', 'register']]);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);
        if ($validator->fails()) {
            return $this->apiErrorResponse($validator->errors(), 400, 'Error');
        }
        if (!$token = auth()->guard('admin-api')->attempt($validator->validated())) {
            return $this->apiErrorResponse('This credentials not match our records', 401, 'Error');
        }
        return $this->apiResponse($this->createNewToken($token), 200, "Admin successfully loged in");
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100|unique:admins',
            'password' => 'required|string|min:8',
        ]);
        if ($validator->fails()) {
            return $this->apiErrorResponse($validator->errors(), 400, 'Error');
        }
        Admin::create(array_merge(
            $validator->validated(),
            ['password' => bcrypt($request->password)]
        ));
        $token = auth()->guard('admin-api')->attempt($validator->validated());
        return $this->apiResponse($this->createNewToken($token), 201, "admin successfully registered and loged in");
    }


    public function logout()
    {
        auth()->logout();
        return $this->apiResponse('ok', 200, 'admin successfully signed out');
    }

    public function refresh()
    {
        return $this->apiResponse($this->createNewToken(auth()->refresh()), 201, 'New token successfully created');
    }

    public function profile()
    {
        return $this->apiResponse(auth()->guard('admin-api')->user(), 200, 'Success');
    }

    protected function createNewToken($token)
    {
        return [
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60 * 24,
            'admin' => auth()->guard('admin-api')->user()
        ];
    }
}
