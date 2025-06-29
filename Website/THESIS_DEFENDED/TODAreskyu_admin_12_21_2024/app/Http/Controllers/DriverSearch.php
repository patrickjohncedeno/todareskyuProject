<?php
namespace App\Http\Controllers;

use App\Models\Driver;
use Illuminate\Http\Request;

class DriverSearch extends Controller
{
    public function getDriverByTinPlate($tinPlate)
    {
        // Fetch driver and the related Toda's name
        $driver = Driver::with('toda')->where('tinPlate', $tinPlate)->first();

        if ($driver) { 
            // Check if the related Toda exists
            if ($driver->toda) {
                // Append the todaName to the response
                $driver->todaName = $driver->toda->todaName;
            } else {
                $driver->todaName = 'N/A'; // Handle case if TODA is not found
            }
            
            // Return the driver data along with the todaName
            return response()->json($driver);
        }

        return response()->json(['error' => 'Driver not found.'], 404);
    }
}
