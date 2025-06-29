<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use App\Models\Violation;
use Illuminate\Http\Request;

class SearchViolationID extends Controller
{
    // Method to retrieve violationID of the selected violation
    public function getViolationID(Request $request)
    {
        // Validate that the violation name or ID is provided from the frontend
        $request->validate([
            'selectedViolation' => 'required|string'
        ]);

        // Find the violation based on the name provided by the frontend
        $violation = Violation::where('violationName', $request->selectedViolation)->first();

        if ($violation) {
            // Return the violationID if found
            return response()->json([
                'violationID' => $violation->violationID
            ], 200);
        }

        // Return an error response if no violation is found
        return response()->json([
            'error' => 'Violation not found for: ' . $request->selectedViolation,
        ], 404);
    }
}
