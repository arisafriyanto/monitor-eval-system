<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravolt\Indonesia\Models\City;
use Laravolt\Indonesia\Models\District;

class RegionController extends Controller
{
    public function getCities($provinceCode)
    {
        $cities = City::where('province_code', $provinceCode)->pluck('name', 'code');
        return response()->json($cities);
    }

    public function getDistricts($cityCode)
    {
        $districts = District::where('city_code', $cityCode)->pluck('name', 'code');
        return response()->json($districts);
    }
}
