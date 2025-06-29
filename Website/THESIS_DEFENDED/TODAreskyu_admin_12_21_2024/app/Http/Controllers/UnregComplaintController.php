<?php

namespace App\Http\Controllers;

use App\Models\ComplaintUnregistered;
use App\Models\UserInfo;
use App\Models\Violation;
use Illuminate\Http\Request;
use App\Models\UserNotification;
use Illuminate\Support\Facades\Storage;

class UnregComplaintController extends Controller
{
    public function index()
    {
        $unregComplaints = ComplaintUnregistered::where('status', 'Pending')->get();

        $pendingCount = ComplaintUnregistered::where('status', 'Pending')->count();
        $inProcessCount = ComplaintUnregistered::where('status', 'In Process')->count();
        $settledCount = ComplaintUnregistered::where('status', 'Settled')->count();
        $deniedCount = ComplaintUnregistered::where('status', 'Denied')->count();
        $unresolvedCount = ComplaintUnregistered::where('status', 'Unresolved')->count();

        return response()->view('unregistered-complaints.unreg-complaint-inqueue', compact('unregComplaints', 'pendingCount', 'inProcessCount', 'settledCount', 'deniedCount', 'unresolvedCount'))
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache')
            ->header('Expires', 'Fri, 01 Jan 1990 00:00:00 GMT');
    }

    public function showDetails(ComplaintUnregistered $complaint)
    {
        $complaint->load(['user', 'violations']);

        $pendingCount = ComplaintUnregistered::where('status', 'Pending')->count();
        $inProcessCount = ComplaintUnregistered::where('status', 'In Process')->count();
        $settledCount = ComplaintUnregistered::where('status', 'Settled')->count();
        $deniedCount = ComplaintUnregistered::where('status', 'Denied')->count();
        $unresolvedCount = ComplaintUnregistered::where('status', 'Unresolved')->count();

        return response()->view('unregistered-complaints.unreg-inqueue-details', compact('complaint', 'pendingCount', 'inProcessCount', 'settledCount', 'deniedCount', 'unresolvedCount'))
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache')
            ->header('Expires', 'Fri, 01 Jan 1990 00:00:00 GMT');
    }

    public function setMeeting(Request $request, ComplaintUnregistered $complaint)
    {
        $request->validate([
            'meetingDate' => 'required|date'
        ]);

        $complaint->update([
            'meetingDate' => $request->meetingDate,
            'status' => 'In Process'
        ]);
        // Insert into user_notifications
        UserNotification::create([
            'complaint_unregistered_id' => $complaint->complaint_unregistered_ID,
            'notification_type' => 'Meeting Set',
            'meeting_date' => $request->meetingDate,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('complaints.unreg-inprocess')->with('success', 'Set meeting successfully.');
    }

    public function reasonForDenying(Request $request, ComplaintUnregistered $complaint)
    {
        $request->validate([
            'reasonForDenying' => 'required|string'
        ]);

        $complaint->update([
            'reasonForDenying' => $request->reasonForDenying,
            'status' => 'Denied'
        ]);
        UserNotification::create([
            'complaint_unregistered_id' => $complaint->complaint_unregistered_ID,
            'notification_type' => 'Denied',
            'denial_reason' => $request->reasonForDenying,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('complaints.unreg-inqueue')->with('success', 'Set reason for denial successfully.');
    }

    public function inprocessComplaint()
    {
        $complaints = ComplaintUnregistered::with(['user', 'violations'])
            ->where('status', 'In Process')
            ->get();

        $pendingCount = ComplaintUnregistered::where('status', 'Pending')->count();
        $inProcessCount = ComplaintUnregistered::where('status', 'In Process')->count();
        $settledCount = ComplaintUnregistered::where('status', 'Settled')->count();
        $deniedCount = ComplaintUnregistered::where('status', 'Denied')->count();
        $unresolvedCount = ComplaintUnregistered::where('status', 'Unresolved')->count();

        return response()->view('unregistered-complaints.unreg-complaint-inprocess', compact('complaints', 'pendingCount', 'inProcessCount', 'settledCount', 'deniedCount', 'unresolvedCount'))
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache')
            ->header('Expires', 'Fri, 01 Jan 1990 00:00:00 GMT');
    }

    public function inProcessEdit(ComplaintUnRegistered $complaint)
    {

        $complaint->load(['user', 'violations']);
        $violations = Violation::all();


        // Count various complaint statuses
        $pendingCount = ComplaintUnRegistered::where('status', 'Pending')->count();
        $inProcessCount = ComplaintUnRegistered::where('status', 'In Process')->count();
        $settledCount = ComplaintUnRegistered::where('status', 'Settled')->count();
        $deniedCount = ComplaintUnRegistered::where('status', 'Denied')->count();
        $unresolvedCount = ComplaintUnRegistered::where('status', 'Unresolved')->count();

        // Pass the current state to the view
        return response()
            ->view('unregistered-complaints.edit-inprocess-unregistered', compact('complaint', 'pendingCount', 'inProcessCount', 'settledCount', 'deniedCount', 'unresolvedCount', 'violations'))
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache')
            ->header('Expires', 'Fri, 01 Jan 1990 00:00:00 GMT');
    }


    public function inProcessUpdate(Request $request, ComplaintUnRegistered $complaint)
    {
        $request->validate([
            'evidencePhoto' => 'image|mimes:jpeg,png,jpg,gif|max:4096|nullable',
            'violationID' => 'required|exists:tbl_violation,violationID',
            'location' => 'required|string|max:255',
            'plateNumber' => 'required|string|min:6',
            'tricycleColor' => 'required|string|max:10',
            'tricycleDescription' => 'required|string|max:255',
            'description' => 'required|string|max:500|min:2'
        ]);

        $violation = Violation::findOrFail($request->violationID);

        $updateData = [
            'violationID' => $request->violationID,
            'location' => $request->location,
            'plateNumber' => $request->plateNumber,
            'tricycleColor' => $request->tricycleColor,
            'tricycleDescription' => $request->tricycleDescription,
            'description' => $request->description,
            'violationPrice' => $violation->penalty,
        ];

        // Handle evidencePhoto if provided
        if ($request->hasFile('evidencePhoto')) {
            $evidencePath = $request->file('evidencePhoto')->store('evidence_photos', 'public');
            $updateData['evidencePhoto'] = $evidencePath;

            // Optionally, delete the old evidence photo if it exists
            if ($complaint->evidencePhoto) {
                Storage::disk('public')->delete($complaint->evidencePhoto);
            }
        }

        $complaint->update($updateData);

        return redirect()->route('complaints.unreg-inprocess')->with('success', 'Complaint updated successfully.');
    }


    public function showInprocess(ComplaintUnregistered $complaint)
    {
        $complaint->load(['user', 'violations']);

        $pendingCount = ComplaintUnregistered::where('status', 'Pending')->count();
        $inProcessCount = ComplaintUnregistered::where('status', 'In Process')->count();
        $settledCount = ComplaintUnregistered::where('status', 'Settled')->count();
        $deniedCount = ComplaintUnregistered::where('status', 'Denied')->count();
        $unresolvedCount = ComplaintUnregistered::where('status', 'Unresolved')->count();

        return response()->view('unregistered-complaints.unreg-inprocess-details', compact('complaint', 'pendingCount', 'inProcessCount', 'settledCount', 'deniedCount', 'unresolvedCount'))
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache')
            ->header('Expires', 'Fri, 01 Jan 1990 00:00:00 GMT');
    }

    public function setResolution(Request $request, ComplaintUnregistered $complaint)
    {
        $request->validate([
            'resolutionDetail' => 'required|string',
            'dateResolve' => 'required|date'
        ]);

        $complaint->update([
            'resolutionDetail' => $request->resolutionDetail,
            'dateResolve' => $request->dateResolve,
            'status' => 'Settled'
        ]);

        UserNotification::create([
            'complaint_unregistered_id' => $complaint->complaint_unregistered_ID,
            'notification_type' => 'Resolved',
            'resolved' => $request->resolutionDetail, // Store resolution summary
            'resolution_date' => $request->dateResolve,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('complaints.unreg-inprocess')->with('success', 'Set meeting successfully.');
    }

    public function settled()
    {
        $unregResolved = ComplaintUnregistered::where('status', 'Settled')->get();
        $pendingCount = ComplaintUnregistered::where('status', 'Pending')->count();
        $inProcessCount = ComplaintUnregistered::where('status', 'In Process')->count();
        $settledCount = ComplaintUnregistered::where('status', 'Settled')->count();
        $deniedCount = ComplaintUnregistered::where('status', 'Denied')->count();
        $unresolvedCount = ComplaintUnregistered::where('status', 'Unresolved')->count();
        return response()->view('unregistered-complaints.unreg-resolve', compact('unregResolved', 'pendingCount', 'inProcessCount', 'settledCount', 'deniedCount', 'unresolvedCount'))
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache')
            ->header('Expires', 'Fri, 01 Jan 1990 00:00:00 GMT');
    }

    public function showSettled(ComplaintUnregistered $complaint)
    {
        $complaint->load(['user', 'violations']);
        $pendingCount = ComplaintUnregistered::where('status', 'Pending')->count();
        $inProcessCount = ComplaintUnregistered::where('status', 'In Process')->count();
        $settledCount = ComplaintUnregistered::where('status', 'Settled')->count();
        $deniedCount = ComplaintUnregistered::where('status', 'Denied')->count();
        $unresolvedCount = ComplaintUnregistered::where('status', 'Unresolved')->count();
        return response()->view('unregistered-complaints.unreg-resolve-details', compact('complaint', 'pendingCount', 'inProcessCount', 'settledCount', 'deniedCount', 'unresolvedCount'))
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache')
            ->header('Expires', 'Fri, 01 Jan 1990 00:00:00 GMT');
    }

    public function addUnregComplaint()
    {
        $violations = Violation::all();
        $users = UserInfo::all();

        return response()->view('unregistered-complaints.add-unreg-complaint', compact('users', 'violations'))
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache')
            ->header('Expires', 'Fri, 01 Jan 1990 00:00:00 GMT');
    }


    public function insertUnregisteredComplaint(Request $request)
    {
        $request->validate([
            'firstName' => 'required|max:255',
            'lastName' => 'required|max:255',
            'address' => 'required|max:255',
            'evidencePhoto' => 'image|mimes:jpeg,png,jpg,gif|max:4096',
            'violationID' => 'required|exists:tbl_violation,violationID',
            'location' => 'required|string|max:255',
            'plateNumber' => 'required|string|min:6',
            'tricycleColor' => 'required|string|max:10',
            'tricycleDescription' => 'required|string|max:255',
            'description' => 'required|string'

        ]);



        // Check if the User exists
        $complainant = UserInfo::where('firstName', $request->firstName)
            ->where('lastName', $request->lastName)
            ->where('address', $request->address)
            ->first();

        if (!$complainant) {
            return redirect()->back()->with('error', 'No match user, please create one')->withInput();
        }


        $evidencePath = null;
        if ($request->hasFile('evidencePhoto')) {
            $evidencePath = $request->file('evidencePhoto')->store('evidence_photos', 'public');
        }

        $violation = Violation::findOrFail($request->violationID);

        ComplaintUnregistered::create([
            'userID' => $complainant->userID,
            'violationID' => $request->violationID,
            'violationPrice' => $violation->penalty,
            'evidencePhoto' => $evidencePath,
            'location' => $request->location,
            'description' => $request->description,
            'status' => 'Pending',
            'id' => auth()->id(),
            // 'id' => 1,
            'dateSubmitted' => now(),
            'plateNumber' => $request->plateNumber,
            'tricycleColor' => $request->tricycleColor,
            'tricycleDescription' => $request->tricycleDescription,
        ]);

        return redirect()->route('complaints.unreg-inqueue')->with('success', 'Complaint added successfully.');
    }

    public function unresolved()
    {
        $unregUnresolved = ComplaintUnregistered::where('status', 'Unresolved')->get();
        $pendingCount = ComplaintUnregistered::where('status', 'Pending')->count();
        $inProcessCount = ComplaintUnregistered::where('status', 'In Process')->count();
        $settledCount = ComplaintUnregistered::where('status', 'Settled')->count();
        $deniedCount = ComplaintUnregistered::where('status', 'Denied')->count();
        $unresolvedCount = ComplaintUnregistered::where('status', 'Unresolved')->count();

        return response()->view('unregistered-complaints.unreg-unresolve', compact('unregUnresolved', 'pendingCount', 'inProcessCount', 'settledCount', 'deniedCount', 'unresolvedCount'))
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache')
            ->header('Expires', 'Fri, 01 Jan 1990 00:00:00 GMT');
    }

    public function denied()
    {
        $deniedComplaint = ComplaintUnregistered::where('status', 'Denied')->get();
        $pendingCount = ComplaintUnregistered::where('status', 'Pending')->count();
        $inProcessCount = ComplaintUnregistered::where('status', 'In Process')->count();
        $settledCount = ComplaintUnregistered::where('status', 'Settled')->count();
        $deniedCount = ComplaintUnregistered::where('status', 'Denied')->count();
        $unresolvedCount = ComplaintUnregistered::where('status', 'Unresolved')->count();

        return response()->view('unregistered-complaints.unreg-denied', compact('deniedComplaint', 'pendingCount', 'inProcessCount', 'settledCount', 'deniedCount', 'unresolvedCount'))
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache')
            ->header('Expires', 'Fri, 01 Jan 1990 00:00:00 GMT');
    }

    public function setResolutionUnresolved(Request $request, ComplaintUnregistered $complaint)
    {
        $request->validate([
            'resolutionDetail' => 'required|string',
            'dateResolve' => 'required|date'
        ]);

        $complaint->update([
            'resolutionDetail' => $request->resolutionDetail,
            'dateResolve' => $request->dateResolve,
            'status' => 'Unresolved'
        ]);
        UserNotification::create([
            'complaint_unregistered_id' => $complaint->complaint_unregistered_ID,
            'notification_type' => 'Unresolved',
            'unresolved' => $request->resolutionDetail, // Store resolution summary
            'resolution_date' => $request->dateResolve,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('complaints.unreg-inprocess')->with('success', 'Unresolved Complaint has been set.');
    }


    public function showUnresolved(ComplaintUnregistered $complaint)
    {
        $complaint->load(['user', 'violations']);
        $pendingCount = ComplaintUnregistered::where('status', 'Pending')->count();
        $inProcessCount = ComplaintUnregistered::where('status', 'In Process')->count();
        $settledCount = ComplaintUnregistered::where('status', 'Settled')->count();
        $deniedCount = ComplaintUnregistered::where('status', 'Denied')->count();
        $unresolvedCount = ComplaintUnregistered::where('status', 'Unresolved')->count();
        return response()->view('unregistered-complaints.unreg-unresolve-details', compact('complaint', 'pendingCount', 'inProcessCount', 'settledCount', 'deniedCount', 'unresolvedCount'))
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache')
            ->header('Expires', 'Fri, 01 Jan 1990 00:00:00 GMT');
    }

    public function showDeniedDetails(ComplaintUnregistered $complaint)
    {
        $complaint->load(['user', 'violations']);
        $pendingCount = ComplaintUnregistered::where('status', 'Pending')->count();
        $inProcessCount = ComplaintUnregistered::where('status', 'In Process')->count();
        $settledCount = ComplaintUnregistered::where('status', 'Settled')->count();
        $deniedCount = ComplaintUnregistered::where('status', 'Denied')->count();
        $unresolvedCount = ComplaintUnregistered::where('status', 'Unresolved')->count();
        return response()->view(
            'unregistered-complaints.unreg-denied-details',
            compact('complaint', 'pendingCount', 'inProcessCount', 'settledCount', 'deniedCount', 'unresolvedCount')
        )->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache')
            ->header('Expires', 'Fri, 01 Jan 1990 00:00:00 GMT');
    }


    public function addPaymentReceipt(Request $request, ComplaintUnregistered $complaint)
    {

        $request->validate([
            'paymentReceipt' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:4096'
        ]);

        $paymentReceiptpath = null;
        if ($request->hasFile('paymentReceipt')) {
            $paymentReceiptpath = $request->file('paymentReceipt')->store('payment_receipts', 'public');
        }

        $complaint->update([
            'paymentReceipt' => $paymentReceiptpath
        ]);

        return redirect()->route('complaints.unreg-settled-details', $complaint->complaint_unregistered_ID)->with('success', 'Violation payment receipt has been uploaded.');
    }

    public function deleteComplaint($complaintID)
    {
        $complaint = ComplaintUnregistered::find($complaintID);
        if ($complaint) {
            $complaint->delete();
            return redirect()->route('complaints.unreg-inprocess')->with('success', 'Complaint deleted successfully.');
        } else {
            return redirect()->route('complaints.unreg-inprocess')->with('error', 'Complaint not found.');
        }
    }
}
