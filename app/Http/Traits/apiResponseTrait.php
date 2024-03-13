<?php

namespace App\Http\Traits;

trait apiResponseTrait
{
    public function apiResponse($data,$status,$message){
        $arr = [
            'data'=>$data,
            'message'=>$message,
        ];

        return response()->json($arr,$status);
    }
    public function apiErrorResponse($data,$status,$message){
        $arr = [
            'data'=>[
                'errors'=>$data,
            ],
            'message'=>$message,
        ];

        return response()->json($arr,$status);
    }
}
