<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LocationController extends Controller
{
    // Get all regions
    public function getRegions()
    {
        $regions = DB::table('regions')
            ->select('id', 'region_name', 'region_code')
            ->orderBy('region_name')
            ->get();
        
        return response()->json($regions);
    }

    // Get provinces by region
    public function getProvinces($regionId)
    {
        $provinces = DB::table('provinces')
            ->select('id', 'province_name', 'province_code')
            ->where('region_id', $regionId)
            ->orderBy('province_name')
            ->get();
        
        return response()->json($provinces);
    }

    // Get cities by province
    public function getCities($provinceId)
    {
        $cities = DB::table('cities')
            ->select('id', 'city_name', 'city_code', 'city_type')
            ->where('province_id', $provinceId)
            ->orderBy('city_name')
            ->get();
        
        return response()->json($cities);
    }

    // Get barangays by city
    public function getBarangays($cityId)
    {
        $barangays = DB::table('barangays')
            ->select('id', 'barangay_name', 'barangay_code')
            ->where('city_id', $cityId)
            ->orderBy('barangay_name')
            ->get();
        
        return response()->json($barangays);
    }

    // Get complete address by barangay ID
    public function getCompleteAddress($barangayId)
    {
        $address = DB::table('vw_complete_addresses')
            ->where('barangay_id', $barangayId)
            ->first();
        
        return response()->json($address);
    }
}