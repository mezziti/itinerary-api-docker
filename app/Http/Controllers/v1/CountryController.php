<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Traits\apiResponseTrait;
use App\Models\Country;

class CountryController extends Controller
{
    use apiResponseTrait;

    public function index()
    {
        return $this->apiResponse(Country::all(), 200, 'ok');
    }
}
