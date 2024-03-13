<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Traits\apiResponseTrait;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class AuthController extends Controller
{

    use apiResponseTrait;
    public function __construct()
    {
        $this->middleware('jwt.verify', ['except' => ['login', 'register']]);
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
        if (!$token = auth()->attempt($validator->validated())) {
            return $this->apiErrorResponse('This credentials not match our records', 401, 'Error');
        }
        return $this->apiResponse($this->createNewToken($token), 200, "User successfully loged in");
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name'    => 'required|string|between:2,100',
            'last_name'     => 'required|string|between:2,100',
            'phone'         => 'required|string|between:10,10',
            'email'         => 'required|string|email|max:100|unique:users',
            'password'      => 'required|string|min:8',
            'country_id'    => 'exists:countries,id',
        ]);
        if ($validator->fails()) {
            return $this->apiErrorResponse($validator->errors(), 400, 'Error');
        }
        User::create(array_merge(
            $validator->validated(),
            ['password' => bcrypt($request->password)]
        ));
        $token = auth()->attempt($validator->validated());
        return $this->apiResponse($this->createNewToken($token), 201, "user successfully registered and loged in");
    }


    public function logout()
    {
        auth()->logout();
        return $this->apiResponse('ok', 200, 'User successfully signed out');
    }

    public function refresh()
    {
        return $this->apiResponse($this->createNewToken(auth()->refresh()), 200, 'New token successfully created');
    }

    public function profile()
    {
        return $this->apiResponse(auth()->user(), 200, 'Success');
    }

    protected function createNewToken($token)
    {
        return [
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60 * 24,
            'user' => auth()->user()
        ];
    }
}
