<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use App\Models\ComplaintRegistered;
use App\Models\ComplaintUnregistered;
use App\Models\Driver;
use App\Models\UserInfo;
use App\Models\Violation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ComplaintController extends Controller
{
    public function index()
    {
        $complaints = Complaint::with(['user', 'driver', 'violations'])->orderByRaw("CASE 
            WHEN status = 'Pending' THEN 1
            WHEN status = 'Success' THEN 2
            WHEN status = 'Denied' THEN 3
            ELSE 4
        END")->paginate(15);
        return view('complaints.complaint-all', compact('complaints'));
    }

    public function inqueueComplaint()
    {
        $registeredComplaints = ComplaintRegistered::with(['user', 'driver', 'violations'])
            ->where('status', 'Pending')
            ->get()
            ->map(function ($complaint) {
                return [
                    'id' => $complaint->complaint_registered_ID,
                    'user' => $complaint->user,
                    'dateSubmitted' => $complaint->dateSubmitted,
                    'driver' => $complaint->driver,
                    'violation' => $complaint->violations,
                    'type' => 'Registered',
                ];
            });

        $unregisteredComplaints = ComplaintUnregistered::with(['user', 'violations'])
            ->where('status', 'Pending')
            ->get()
            ->map(function ($complaint) {
                return [
                    'id' => $complaint->complaint_unregistered_ID,
                    'user' => $complaint->user,
                    'dateSubmitted' => $complaint->dateSubmitted,
                    'violation' => $complaint->violations,
                    'type' => 'Colorum',
                ];
            });

        $complaints = $registeredComplaints->merge($unregisteredComplaints);

        return view('complaints.complaint-inqueue', compact('complaints'));
    }


    public function inqueueDetails($type, $id)
    {
        if ($type === 'registered') {
            $inqueue = ComplaintRegistered::with(['user', 'violations', 'driver'])->find($id);
            return view('complaints.registered-inqueue-details', compact('inqueue'));
        } else {
            $inqueue = ComplaintUnregistered::with(['user', 'violations'])->find($id);
            return view('complaints.unregistered-inqueue-details', compact('inqueue'));
        }

        if (!$inqueue) {
            return redirect()->route('complaints.inqueue')->with('error', 'Complaint not found.');
        }

        
    }

    public function inprocessComplaint()
    {
        $complaints = Complaint::with(['user', 'driver', 'violations'])
            ->where('status', 'In Process')
            ->get();

        return view('complaints.complaint-inprocess', compact('complaints'));
    }

    public function show(Complaint $complaint)
    {
        $complaint->load('user', 'violations');
        return view('complaints.complaint-review', compact('complaint'));
    }

    public function create()
    {
        $users = UserInfo::all();
        $drivers = Driver::all();
        $violations = Violation::all();

        return view('complaints.create-complaint', compact('users', 'drivers', 'violations'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'userID' => 'required|exists:tbl_userinfo,userID',
            'driverID' => 'required|exists:tbl_driverinfo,driverID',
            'location' => 'required|string|max:255',
            'description' => 'required|string',
            'violationID' => 'required|exists:tbl_violation,violationID',
        ]);

        $complaint = Complaint::create([
            'userID' => $request->userID,
            'driverID' => $request->driverID,
            'dateSubmitted' => now(),
            'location' => $request->location,
            'description' => $request->description,
            'status' => 'Pending',
            'id' => auth()->id(),
            'violationID' => $request->violationID
        ]);


        return redirect()->route('complaints.index')->with('success', 'Complaint created successfully.');
    }

    public function complaintEdit(Complaint $complaint)
    {
        $users = UserInfo::all();
        $drivers = Driver::all();
        $violations = Violation::all();
        return view('complaints.update-complaint', compact('complaint', 'users', 'drivers', 'violations'));
    }

    public function complaintUpdate(Request $request, Complaint $complaint)
    {
        $request->validate([
            'userID' => 'required|exists:tbl_userinfo,userID',
            'driverID' => 'required|exists:tbl_driverinfo,driverID',
            'location' => 'required|string|max:255',
            'description' => 'required|string',
            'violationID' => 'required|exists:tbl_violation,violationID',
        ]);

        $complaint->update([
            'userID' => $request->userID,
            'driverID' => $request->driverID,
            'location' => $request->location,
            'description' => $request->description,
            'status' => 'Pending',
            'id' => auth()->id(),
            'violationID' => $request->violationID
        ]);


        return redirect()->route('complaints.index')->with('success', 'Complaint updated successfully.');
    }

    public function delete(Complaint $complaint)
    {
        $complaint->delete();

        return redirect()->route('complaints.index')->with('success', 'Complaint deleted successfully.');
    }

    public function filterByStatus($status)
    {
        $complaints = Complaint::where('status', $status)->with(['user', 'driver', 'violations'])->paginate(5);
        return view('complaints.complaint-all', compact('complaints'));
    }

    public function denied(Complaint $complaint)
    {
        $complaint->update([
            'resolutionDetail' => 'Driver is not fined for violation',
            'status' => 'Denied',
            'dateResolve' => now()
        ]);
        // Driver fined for violation
        return view('complaints.complaint-review', compact('complaint'));
    }

    public function accept(Complaint $complaint)
    {
        $complaint->update([
            'resolutionDetail' => 'Driver is fined for violation',
            'status' => 'Success',
            'dateResolve' => now()
        ]);

        return view('complaints.complaint-review', compact('complaint'));
    }
}
