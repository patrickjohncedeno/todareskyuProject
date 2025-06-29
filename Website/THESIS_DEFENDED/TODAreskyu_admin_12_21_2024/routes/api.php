<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\ComplaintRegistered;
use App\Models\ComplaintUnregistered;
use App\Models\UserNotification;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//signUpUser
use App\Http\Controllers\UserInfoController;
Route::post('register', [UserInfoController::class, 'register']);

//loginUser
use App\Http\Controllers\LoginUserController;
Route::post('/login', [LoginUserController::class, 'login']);

//searchDriverForComplaintForm
use App\Http\Controllers\DriverSearch;
Route::get('/driver/{tinPlate}', [DriverSearch::class, 'getDriverByTinPlate']);

//forUserComplaintSubmission
use App\Http\Controllers\UserComplaintRegisteredController;
Route::post('/submit-complaint', [UserComplaintRegisteredController::class, 'storeComplaint']);

use App\Http\Controllers\UserComplaintUnregisteredController;
Route::post('/submit-complaint-unreg', [UserComplaintUnregisteredController::class, 'storeComplaint']);

//forViolationIDsearch
use App\Http\Controllers\SearchViolationID;
Route::post('/get-violation-id', [SearchViolationID::class, 'getViolationID']);




Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


use App\Http\Controllers\AuthControllerForUsers;

Route::post('/register-email', [AuthControllerForUsers::class, 'register']); // Step 1
Route::post('/verify-code', [AuthControllerForUsers::class, 'verifyCode']); // Step 2
Route::post('/complete-profile', [AuthControllerForUsers::class, 'completeProfile']); // Step 3
Route::post('/upload', [AuthControllerForUsers::class, 'upload']);

Route::get('/getComplaintCounts', function (Request $request) {
    $userID = $request->query('userID');

    // Count complaints based on status for registered complaints
    $inProcessCountRegistered = ComplaintRegistered::where('userID', $userID)
        ->where('status', 'pending') // Adjust status as per actual values
        ->count();

    $underReviewCountRegistered = ComplaintRegistered::where('userID', $userID)
        ->where('status', 'in process')
        ->count();

    $closedCountRegistered = ComplaintRegistered::where('userID', $userID)
        ->where('status', 'settled')
        ->count();

    // Count complaints based on status for unregistered complaints
    $inProcessCountUnregistered = ComplaintUnregistered::where('userID', $userID)
        ->where('status', 'pending')
        ->count();

    $underReviewCountUnregistered = ComplaintUnregistered::where('userID', $userID)
        ->where('status', 'in process')
        ->count();

    $closedCountUnregistered = ComplaintUnregistered::where('userID', $userID)
        ->where('status', 'settled')
        ->count();

    // Total counts
    $inProcessCount = $inProcessCountRegistered + $inProcessCountUnregistered;
    $underReviewCount = $underReviewCountRegistered + $underReviewCountUnregistered;
    $closedCount = $closedCountRegistered + $closedCountUnregistered;

    return response()->json([
        'inProcess' => $inProcessCount,
        'underReview' => $underReviewCount,
        'closed' => $closedCount,
    ]);
});


use App\Http\Controllers\UserNotificationController;

Route::get('/getNotification', [UserNotificationController::class, 'getNotifications']);
Route::get('/notifications', [UserNotificationController::class, 'getUnreadNotificationCount']);
Route::put('/notifications/{id}/mark-as-read', [UserNotificationController::class, 'markAsRead']);

Route::get('/userinfo/{userID}', [UserInfoController::class, 'getUserInfo']);

use App\Http\Controllers\AddressController;

Route::get('/municipalities', [AddressController::class, 'getMunicipalities']);
Route::post('/barangays', [AddressController::class, 'getBarangays']);

use App\Http\Controllers\UserGetComplaint;

Route::get('/getUserComplaints/{userID}', [UserGetComplaint::class, 'getUserComplaints']);



use App\Http\Controllers\UserAnnouncementController;

Route::get('/announcements', [UserAnnouncementController::class, 'getAllAnnouncements']);



Route::get('/test', function () {
    return response()->json(['message' => 'API is working']);
});



