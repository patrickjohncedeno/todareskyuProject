<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserNotification;

class UserNotificationController extends Controller
{
    public function getNotifications(Request $request)
    { 
        try {
            // Log the incoming request data
            \Log::info('Incoming request data: ', $request->all());

            // Retrieve user ID from query parameters
            $userID = $request->query('userID');

            // Ensure userID is not null
            if (!$userID) {
                return response()->json(['error' => 'User ID is required.'], 400);
            }

            // Retrieve notifications with related violation and complaint data
            $notifications = UserNotification::with([
                'registeredComplaint.violations',
                'registeredComplaint.driver', 
                'unregisteredComplaint.violations',
                
            ])
            ->whereHas('registeredComplaint', function ($query) use ($userID) {
                $query->where('userID', $userID);
            })
            ->orWhereHas('unregisteredComplaint', function ($query) use ($userID) {
                $query->where('userID', $userID);
            })
            ->get();

            // Format the response for each notification
            // Format the response for each notification
$response = $notifications->map(function ($notification) {
    $complaintData = null;
    $violationName = null;
    $tinPlate = null;

    // Check if it's a registered complaint
    if ($notification->complaint_registered_id) {
        $complaintData = $notification->registeredComplaint;
        $violationName = $complaintData->violations->violationName ?? null;
        $tinPlate = $complaintData->driver->tinPlate ?? null;
    }
    // Check if it's an unregistered complaint
    elseif ($notification->complaint_unregistered_id) {
        $complaintData = $notification->unregisteredComplaint;
        $violationName = $complaintData->violations->violationName ?? null;
        $tinPlate = $complaintData->plateNumber ?? null;
    }

    // Add meeting_date or denial_reason based on notification type
    return [
        'id' => $notification->id,
        'notification_type' => $notification->notification_type,
        'meeting_date' => $notification->notification_type === 'Meeting Set' ? $notification->meeting_date : null,
        'denial_reason' => $notification->notification_type === 'Denied' ? $notification->denial_reason : null,
        'readNotif' => $notification->readNotif,
        'violation_name' => $violationName,
        'tin_plate' => $tinPlate,
        'complaint_details' => $complaintData
    ];
});


            return response()->json($response);
        } catch (\Exception $e) {
            \Log::error('Error fetching notifications: ' . $e->getMessage());
            return response()->json(['error' => 'Server error occurred.'], 500);
        }
    }

    public function getUnreadNotificationCount(Request $request)
{
    // Validate the userID parameter
    $request->validate([
        'userID' => 'required|integer',
    ]);

    $userID = $request->userID;

    // Fetch unread notifications for the user
    $unreadCount = UserNotification::where('readNotif', 1)
        ->where(function ($query) use ($userID) {
            $query->whereHas('registeredComplaint', function ($q) use ($userID) {
                $q->where('userID', $userID);
            })
            ->orWhereHas('unregisteredComplaint', function ($q) use ($userID) {
                $q->where('userID', $userID);
            });
        })
        ->count();

    return response()->json([
        'unread_count' => $unreadCount,
    ]);
}


    public function markAsRead($id)
    {
        try {
            // Find the notification by its ID
            $notification = UserNotification::findOrFail($id);
            
            // Update the readNotif field to 0
            $notification->readNotif = 0;
            $notification->save();

            return response()->json([
                'message' => 'Notification marked as read successfully',
                'notification' => $notification
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to update notification',
                'message' => $e->getMessage()
            ], 400);
        }
    }

}
