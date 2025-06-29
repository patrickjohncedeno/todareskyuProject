<?php

namespace App\Http\Controllers;

use App\Models\ComplaintUnregistered;
use App\Models\Driver;
use App\Models\UserInfo;
use App\Models\Violation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class UserComplaintUnregisteredController extends Controller
{
    public function storeComplaint(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'userID' => 'required|exists:tbl_userinfo,userID',
            'location' => 'required|string|max:255',
            'description' => 'required|string',
            'violationID' => 'required|exists:tbl_violation,violationID',
            'plateNumber' => 'required|string|max:20',
            'tricycleDescription' => 'required|string|max:255',
            'tricycleColor' => 'required|string|max:50',
            'evidencePhoto' => 'nullable|file|mimes:jpg,jpeg,png|max:10000', // Optional file validation
        ]);

        // Handle file upload if an evidence photo is provided
        $filePath = null;
        if ($request->hasFile('evidencePhoto')) {
            $filePath = $request->file('evidencePhoto')->store('evidence-photos', 'public');
        }
        $violation = Violation::findOrFail($request->violationID);

        // Create the complaint record with all fields
        $complaint = ComplaintUnregistered::create([
            'userID' => $request->userID,
            'driverID' => $request->driverID,
            'dateSubmitted' => now(),
            'violationPrice' => $violation->penalty,
            'id' => 1,
            'location' => $request->location,
            'description' => $request->description,
            'status' => 'Pending',
            'violationID' => $request->violationID,
            'plateNumber' => $request->plateNumber,
            'tricycleDescription' => $request->tricycleDescription,
            'tricycleColor' => $request->tricycleColor,
            'evidencePhoto' => $filePath, // Add the file path if an image was uploaded
            'paymentReceipt' => 'await',
        ]);

        return response()->json(['success' => true, 'message' => 'Complaint submitted successfully.'], 201);
    }
}