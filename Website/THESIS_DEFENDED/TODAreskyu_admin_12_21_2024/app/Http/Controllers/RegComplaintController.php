<?php

namespace App\Http\Controllers;

use App\Models\ComplaintRegistered;
use App\Models\Driver;
use App\Models\UserInfo;
use App\Models\Violation;
use App\Models\UserNotification;
use Illuminate\Http\Request;
use Route;


class RegComplaintController extends Controller
{
    public function index()
    {
        $regComplaints = ComplaintRegistered::where('status', 'Pending')->get();
        $pendingCount = ComplaintRegistered::where('status', 'Pending')->count();
        $inProcessCount = ComplaintRegistered::where('status', 'In Process')->count();
        $settledCount = ComplaintRegistered::where('status', 'Settled')->count();
        $deniedCount = ComplaintRegistered::where('status', 'Denied')->count();
        $unresolvedCount = ComplaintRegistered::where('status', 'Unresolved')->count();
        return response()->view('registered-complaints.reg-complaint-inqueue', compact('regComplaints', 'pendingCount', 'inProcessCount', 'settledCount', 'deniedCount', 'unresolvedCount'))
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache')
            ->header('Expires', 'Fri, 01 Jan 1990 00:00:00 GMT');
    }

    public function showDetails(ComplaintRegistered $complaint)
    {
        $complaint->load(['user', 'violations', 'driver']);
        $pendingCount = ComplaintRegistered::where('status', 'Pending')->count();
        $inProcessCount = ComplaintRegistered::where('status', 'In Process')->count();
        $settledCount = ComplaintRegistered::where('status', 'Settled')->count();
        $deniedCount = ComplaintRegistered::where('status', 'Denied')->count();
        $unresolvedCount = ComplaintRegistered::where('status', 'Unresolved')->count();
        return response()->view(
            'registered-complaints.reg-inqueue-details',
            compact('complaint', 'pendingCount', 'inProcessCount', 'settledCount', 'deniedCount', 'unresolvedCount')
        )->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache')
            ->header('Expires', 'Fri, 01 Jan 1990 00:00:00 GMT');
    }

    public function setMeeting(Request $request, ComplaintRegistered $complaint)
    {
        $request->validate([
            'meetingDate' => 'required|date'
        ]);

        $existingMeeting = ComplaintRegistered::where('meetingDate', $request->meetingDate)
            ->where('complaint_registered_ID', '!=', $complaint->complaint_registered_ID)
            ->first();

        if ($existingMeeting) {
            return redirect()->route('complaints.reg-inqueue-show', $complaint->complaint_registered_ID)->with('error', 'A meeting has already been scheduled on this date and time.');
        }

        $complaint->update([
            'meetingDate' => $request->meetingDate,
            'status' => 'In Process'
        ]);

        UserNotification::create([
            'complaint_registered_id' => $complaint->complaint_registered_ID,
            'notification_type' => 'Meeting Set',
            'meeting_date' => $request->meetingDate,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('complaints.reg-inqueue')->with('success', 'Meeting set successfully.');
    }

    public function reasonForDenying(Request $request, ComplaintRegistered $complaint)
    {
        $request->validate([
            'reasonForDenying' => 'required|string'
        ]);

        $complaint->update([
            'reasonForDenying' => $request->reasonForDenying,
            'status' => 'Denied'
        ]);

        UserNotification::create([
            'complaint_registered_id' => $complaint->complaint_registered_ID,
            'notification_type' => 'Denied',
            'denial_reason' => $request->reasonForDenying,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('complaints.reg-inqueue')->with('success', 'Set reason for denial successfully.');
    }

    public function inprocessComplaint()
    {
        $complaints = ComplaintRegistered::with(['user', 'driver', 'violations'])
            ->where('status', 'In Process')
            ->get();
        $violations = Violation::all();
        $pendingCount = ComplaintRegistered::where('status', 'Pending')->count();
        $inProcessCount = ComplaintRegistered::where('status', 'In Process')->count();
        $settledCount = ComplaintRegistered::where('status', 'Settled')->count();
        $deniedCount = ComplaintRegistered::where('status', 'Denied')->count();
        $unresolvedCount = ComplaintRegistered::where('status', 'Unresolved')->count();
        return response()->view('registered-complaints.reg-complaint-inprocess', compact('complaints', 'pendingCount', 'inProcessCount', 'settledCount', 'deniedCount', 'unresolvedCount', 'violations'))
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache')
            ->header('Expires', 'Fri, 01 Jan 1990 00:00:00 GMT');
    }

    public function inProcessEdit(ComplaintRegistered $complaint)
    {
 

        $complaint->load(['user', 'violations', 'driver']);

        $violations = Violation::all();

        // Count various complaint statuses
        $pendingCount = ComplaintRegistered::where('status', 'Pending')->count();
        $inProcessCount = ComplaintRegistered::where('status', 'In Process')->count();
        $settledCount = ComplaintRegistered::where('status', 'Settled')->count();
        $deniedCount = ComplaintRegistered::where('status', 'Denied')->count();
        $unresolvedCount = ComplaintRegistered::where('status', 'Unresolved')->count();

        // Pass the current state to the view
        return response()
            ->view('registered-complaints.edit-inprocess-registered', compact('complaint', 'pendingCount', 'inProcessCount', 'settledCount', 'deniedCount', 'unresolvedCount', 'violations'))
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache')
            ->header('Expires', 'Fri, 01 Jan 1990 00:00:00 GMT');
    }


    public function inProcessUpdate(Request $request, ComplaintRegistered $complaint)
    {
        // dd($request->all());
        $request->validate([
            'violationID' => 'required|exists:tbl_violation,violationID',
            'location' => 'required|string|max:255',
            'description' => 'required|string|max:500|min:2'
        ]);

        $violation = Violation::findOrFail($request->violationID);
        
        $complaint->update([
            'description' => $request->description,
            'location' => $request->location,
            'violationID' => $request->violationID,
            'violationPrice' => $violation->penalty,
        ]);

        // Redirect dynamically based on the state
        return redirect()->route('complaints.reg-inprocess')->with('success', 'Complaint resolution details entered successfully.');
    }


    public function showInprocess(ComplaintRegistered $complaint)
    {
        $complaint->load(['user', 'violations', 'driver']);
        $pendingCount = ComplaintRegistered::where('status', 'Pending')->count();
        $inProcessCount = ComplaintRegistered::where('status', 'In Process')->count();
        $settledCount = ComplaintRegistered::where('status', 'Settled')->count();
        $deniedCount = ComplaintRegistered::where('status', 'Denied')->count();
        $unresolvedCount = ComplaintRegistered::where('status', 'Unresolved')->count();
        return response()->view('registered-complaints.reg-inprocess-details', compact('complaint', 'pendingCount', 'inProcessCount', 'settledCount', 'deniedCount', 'unresolvedCount'))
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache')
            ->header('Expires', 'Fri, 01 Jan 1990 00:00:00 GMT');
    }

    public function setResolutionResolved(Request $request, ComplaintRegistered $complaint)
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
            'complaint_registered_id' => $complaint->complaint_registered_ID,
            'notification_type' => 'Resolved',
            'resolved' => $request->resolutionDetail, // Store resolution summary
            'resolution_date' => $request->dateResolve,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('complaints.reg-settled')->with('success', 'Complaint resolution details entered successfully.');
    }

    public function setResolutionUnresolved(Request $request, ComplaintRegistered $complaint)
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
            'complaint_registered_id' => $complaint->complaint_registered_ID,
            'notification_type' => 'Unresolved',
            'unresolved' => $request->resolutionDetail, // Store resolution summary
            'resolution_date' => $request->dateResolve,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('complaints.reg-unresolved')->with('success', 'Unresolved Complaint has been set.');
    }

    public function settled()
    {
        $regResolved = ComplaintRegistered::where('status', 'Settled')->get();
        $pendingCount = ComplaintRegistered::where('status', 'Pending')->count();
        $inProcessCount = ComplaintRegistered::where('status', 'In Process')->count();
        $settledCount = ComplaintRegistered::where('status', 'Settled')->count();
        $deniedCount = ComplaintRegistered::where('status', 'Denied')->count();
        $unresolvedCount = ComplaintRegistered::where('status', 'Unresolved')->count();
        return response()->view('registered-complaints.reg-resolve', compact('regResolved', 'pendingCount', 'inProcessCount', 'settledCount', 'deniedCount', 'unresolvedCount'))
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache')
            ->header('Expires', 'Fri, 01 Jan 1990 00:00:00 GMT');
    }

    public function showSettled(ComplaintRegistered $complaint)
    {
        $complaint->load(['user', 'violations', 'driver']);
        $pendingCount = ComplaintRegistered::where('status', 'Pending')->count();
        $inProcessCount = ComplaintRegistered::where('status', 'In Process')->count();
        $settledCount = ComplaintRegistered::where('status', 'Settled')->count();
        $deniedCount = ComplaintRegistered::where('status', 'Denied')->count();
        $unresolvedCount = ComplaintRegistered::where('status', 'Unresolved')->count();
        return response()->view('registered-complaints.reg-resolve-details', compact('complaint', 'pendingCount', 'inProcessCount', 'settledCount', 'deniedCount', 'unresolvedCount'))
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache')
            ->header('Expires', 'Fri, 01 Jan 1990 00:00:00 GMT');
    }

    public function showUnresolved(ComplaintRegistered $complaint)
    {
        $complaint->load(['user', 'violations', 'driver']);
        $pendingCount = ComplaintRegistered::where('status', 'Pending')->count();
        $inProcessCount = ComplaintRegistered::where('status', 'In Process')->count();
        $settledCount = ComplaintRegistered::where('status', 'Settled')->count();
        $deniedCount = ComplaintRegistered::where('status', 'Denied')->count();
        $unresolvedCount = ComplaintRegistered::where('status', 'Unresolved')->count();
        return response()->view('registered-complaints.reg-unresolve-details', compact('complaint', 'pendingCount', 'inProcessCount', 'settledCount', 'deniedCount', 'unresolvedCount'))
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache')
            ->header('Expires', 'Fri, 01 Jan 1990 00:00:00 GMT');
    }

    public function addRegComplaint()
    {
        $drivers = Driver::all();
        $violations = Violation::all();
        $users = UserInfo::all();

        return response()->view('registered-complaints.add-reg-complaint', compact('users', 'drivers', 'violations'))
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache')
            ->header('Expires', 'Fri, 01 Jan 1990 00:00:00 GMT');
    }

    public function insertRegisteredComplaint(Request $request)
    {
        $request->validate([
            'firstName' => 'required|max:255',
            'lastName' => 'required|max:255',
            'address' => 'required|max:255',
            'tinPlate' => 'required|integer|min:4',
            'violationID' => 'required|exists:tbl_violation,violationID',
            'location' => 'required|string|max:255',
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

        // Check if the MTOP exists
        $driver = Driver::where('tinPlate', $request->tinPlate)->first();

        if (!$driver) {
            return redirect()->back()->with('noMTOP', 'No matching tin plate from Tricycle Driver database.')->withInput();
        }

        $violation = Violation::findOrFail($request->violationID);

        ComplaintRegistered::create([
            'userID' => $complainant->userID,
            'driverID' => $driver->driverID,
            'violationID' => $request->violationID,
            'violationPrice' => $violation->penalty,
            'location' => $request->location,
            'description' => $request->description,
            'status' => 'Pending',
            'id' => auth()->id(),
            'dateSubmitted' => now()
        ]);

        return redirect()->route('complaints.reg-inqueue')->with('success', 'Complaint added successfully.');
    }

    public function unresolved()
    {
        $regUnresolved = ComplaintRegistered::where('status', 'Unresolved')->get();
        $pendingCount = ComplaintRegistered::where('status', 'Pending')->count();
        $inProcessCount = ComplaintRegistered::where('status', 'In Process')->count();
        $settledCount = ComplaintRegistered::where('status', 'Settled')->count();
        $deniedCount = ComplaintRegistered::where('status', 'Denied')->count();
        $unresolvedCount = ComplaintRegistered::where('status', 'Unresolved')->count();
        return response()->view('registered-complaints.reg-unresolve', compact('regUnresolved', 'pendingCount', 'inProcessCount', 'settledCount', 'deniedCount', 'unresolvedCount'))
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache')
            ->header('Expires', 'Fri, 01 Jan 1990 00:00:00 GMT');
    }

    public function denied()
    {
        $deniedComplaint = ComplaintRegistered::where('status', 'Denied')->get();
        $pendingCount = ComplaintRegistered::where('status', 'Pending')->count();
        $inProcessCount = ComplaintRegistered::where('status', 'In Process')->count();
        $settledCount = ComplaintRegistered::where('status', 'Settled')->count();
        $deniedCount = ComplaintRegistered::where('status', 'Denied')->count();
        $unresolvedCount = ComplaintRegistered::where('status', 'Unresolved')->count();
        return response()->view('registered-complaints.reg-denied', compact('deniedComplaint', 'pendingCount', 'inProcessCount', 'settledCount', 'deniedCount', 'unresolvedCount'))
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache')
            ->header('Expires', 'Fri, 01 Jan 1990 00:00:00 GMT');
    }


    public function showDeniedDetails(ComplaintRegistered $complaint)
    {
        $complaint->load(['user', 'violations', 'driver']);
        $pendingCount = ComplaintRegistered::where('status', 'Pending')->count();
        $inProcessCount = ComplaintRegistered::where('status', 'In Process')->count();
        $settledCount = ComplaintRegistered::where('status', 'Settled')->count();
        $deniedCount = ComplaintRegistered::where('status', 'Denied')->count();
        $unresolvedCount = ComplaintRegistered::where('status', 'Unresolved')->count();
        return response()->view(
            'registered-complaints.reg-denied-details',
            compact('complaint', 'pendingCount', 'inProcessCount', 'settledCount', 'deniedCount', 'unresolvedCount')
        )->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache')
            ->header('Expires', 'Fri, 01 Jan 1990 00:00:00 GMT');
    }

    public function addPaymentReceipt(Request $request, ComplaintRegistered $complaint)
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

        return redirect()->route('complaints.reg-settled-details', $complaint->complaint_registered_ID)->with('success', 'Violation payment receipt has been uploaded.');
    }

    public function deleteComplaint($complaintID)
    {
        $complaint = ComplaintRegistered::find($complaintID);
        if ($complaint) {
            $complaint->delete();
            return redirect()->route('complaints.reg-inprocess')->with('success', 'Complaint deleted successfully.');
        } else {
            return redirect()->route('complaints.reg-inprocess')->with('error', 'Complaint not found.');
        }
    }
}
