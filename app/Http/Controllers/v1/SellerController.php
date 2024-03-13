<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Traits\apiResponseTrait;
use App\Models\Seller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SellerController extends Controller
{
    use apiResponseTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Seller $seller)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Seller $seller)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Seller $seller)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Seller $seller)
    {
        //
    }



    public function __construct()
    {
        $this->middleware('jwt.verify:Seller-api', ['except' => ['login', 'register']]);
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
        if (!$token = auth()->guard('seller-api')->attempt($validator->validated())) {
            return $this->apiErrorResponse('This credentials not match our records', 401, 'Error');
        }
        return $this->apiResponse($this->createNewToken($token), 200, "Seller successfully loged in");
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100|unique:sellers',
            'password' => 'required|string|min:8',
        ]);
        if ($validator->fails()) {
            return $this->apiErrorResponse($validator->errors(), 400, 'Error');
        }
        $Seller = Seller::create(array_merge(
            $validator->validated(),
            ['password' => bcrypt($request->password)]
        ));
        return $this->apiResponse($Seller, 201, "Seller successfully registered");
    }


    public function logout()
    {
        auth()->logout();
        return $this->apiResponse('ok', 200, 'Seller successfully signed out');
    }

    public function refresh()
    {
        return $this->apiResponse($this->createNewToken(auth()->refresh()), 200, 'New token successfully created');
    }

    public function profile()
    {
        return $this->apiResponse(auth()->guard('seller-api')->user(), 200, 'Success');
    }

    protected function createNewToken($token)
    {
        return [
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60 * 24,
            'Seller' => auth()->user()
        ];
    }


}
