<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ComplaintRegistered;
use App\Models\ComplaintUnregistered;
use App\Models\Violation;

class UserGetComplaint extends Controller
{
    // Fetch all complaints of the user
    public function getUserComplaints($userID)
    {
        try {
            // Fetch registered and unregistered complaints
            $registeredComplaints = ComplaintRegistered::where('userID', $userID)->get();
            $unregisteredComplaints = ComplaintUnregistered::where('userID', $userID)->get();
            
            // Combine both collections into one
            $allComplaints = $registeredComplaints->concat($unregisteredComplaints);

            // Return the merged complaints
            return response()->json([
                'complaints' => $allComplaints->map(function ($complaint) {
                    return [
                        'id' => $complaint->complaint_registered_ID ?? $complaint->complaint_unregistered_ID,
                        'status' => $complaint->status,
                        'dateReported' => $complaint->dateSubmitted,
                        'meetingDate' => $complaint->meetingDate,
                        'reasonForDenying' => $complaint->reasonForDenying,
                        'resolutionDetail' => $complaint->resolutionDetail,
                        'violationName' => $complaint->violations->violationName ?? null,  // Include violation name
                    ];
                }),
            ]);
        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Error fetching complaints: ' . $e->getMessage());
            return response()->json(['message' => 'Failed to fetch complaints'], 500);
        }
    }
}

