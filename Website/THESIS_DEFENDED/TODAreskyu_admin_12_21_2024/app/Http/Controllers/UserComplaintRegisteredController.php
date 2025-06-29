<?php

namespace App\Http\Controllers;

use App\Models\ComplaintRegistered;
use App\Models\Driver;
use App\Models\UserInfo;
use App\Models\Violation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class UserComplaintRegisteredController extends Controller
{
    public function storeComplaint(Request $request)
    {
        $request->validate([
            'userID' => 'required|exists:tbl_userinfo,userID',
            'driverID' => 'required|exists:tbl_driverinfo,driverID',
            'location' => 'required|string|max:255',
            'description' => 'required|string',
            'violationID' => 'required|exists:tbl_violation,violationID',
        ]);
        
        $violation = Violation::findOrFail($request->violationID);

        $complaint = ComplaintRegistered::create([
            'userID' => $request->userID,
            'driverID' => $request->driverID,
            'dateSubmitted' => now(),                  
            'location' => $request->location,
            'description' => $request->description,
            'status' => 'Pending',
            'id' => 1,
            'violationID' => $request->violationID,
            'violationPrice' => $violation->penalty,
        ]);

        return response()->json(['success' => true, 'message' => 'Complaint submitted successfully.'], 201);
    }
}
