<?php
namespace App\Http\Controllers;


use App\Models\Announcement;
use Illuminate\Http\Request;

class UserAnnouncementController extends Controller
{
    /**
     * Fetch all announcements.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllAnnouncements()
    {
        try {
            $announcements = Announcement::where('status', 'active')
                ->orderBy('datePosted', 'desc')
                ->get();
    
            return response()->json([
                'success' => true,
                'data' => $announcements,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch announcements.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    
}
