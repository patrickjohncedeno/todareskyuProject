<?php

namespace App\Http\Controllers;

use App\Models\RefCityMun;
use App\Models\RefBrgy;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    // Get all municipalities
    public function getMunicipalities()
    {
        $municipalities = RefCityMun::select('citymunCode', 'citymunDesc')->get();

        return response()->json($municipalities);
    }

    // Get barangays based on municipality
    public function getBarangays(Request $request)
    {
        $request->validate([
            'citymunCode' => 'required|string',
        ]);

        $barangays = RefBrgy::where('citymunCode', $request->citymunCode)
            ->select('brgyCode', 'brgyDesc')
            ->get();

        return response()->json($barangays);
    }
}
