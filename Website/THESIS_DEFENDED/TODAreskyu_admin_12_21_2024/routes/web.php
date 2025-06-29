<?php

use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RegComplaintController;
use App\Http\Controllers\UnregComplaintController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\TodaController;
use App\Http\Controllers\UserInfoController;
use App\Http\Controllers\ViolationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//login
Route::get('/', [AuthController::class, 'login'])->name('login');
Route::post('/', [AuthController::class, 'authenticate']);

//signup
Route::get('/register', [AuthController::class, 'register'])->name('register');
Route::post('/register', [AuthController::class, 'store']);

//logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');





//complaint
Route::middleware(['auth'])->group(function () {
    //dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('index');
    Route::get('/getViolationsData', [DashboardController::class, 'getViolationsData']);
    Route::get('/filtered', [DashboardController::class, 'filter'])->name('filter');

    //Old complaint form
    Route::get('/complaints/create', [ComplaintController::class, 'create'])->name('complaints.create');
    Route::post('/complaints', [ComplaintController::class, 'store'])->name('complaints.store');
    Route::get('/complaints', [ComplaintController::class, 'index'])->name('complaints.index');

    //REGISTERED COMPLAINTS
    Route::get('/complaints/registered/inqueue', [RegComplaintController::class, 'index'])->name('complaints.reg-inqueue');
    Route::get('/complaints/registered/inqueue/{complaint}', [RegComplaintController::class, 'showDetails'])->name('complaints.reg-inqueue-show');
    Route::put('/complaints/registered/inqueue/{complaint}', [RegComplaintController::class, 'setMeeting'])->name('complaints.reg-setMeeting');
    Route::put('/complaints/registered/inqueue/{complaint}/deny', [RegComplaintController::class, 'reasonForDenying'])->name('complaints.reg-deny');
    Route::get('/complaints/registered/inprocess', [RegComplaintController::class, 'inprocessComplaint'])->name('complaints.reg-inprocess');
    Route::get('/complaints/registered/inprocess/{complaint}', [RegComplaintController::class, 'showInprocess'])->name('complaints.reg-inprocess-show');
    Route::put('/complaints/registered/inprocess/{complaint}/resolve', [RegComplaintController::class, 'setResolutionResolved'])->name('complaints.reg-setResolutionResolved');
    Route::put('/complaints/registered/inprocess/{complaint}/unresolve', [RegComplaintController::class, 'setResolutionUnresolved'])->name('complaints.reg-setResolutionUnresolved');
    Route::get('/complaints/registered/settled', [RegComplaintController::class, 'settled'])->name('complaints.reg-settled');
    Route::get('/complaints/registered/settled/{complaint}', [RegComplaintController::class, 'showSettled'])->name('complaints.reg-settled-details');
    Route::get('/complaints/registered/add', [RegComplaintController::class, 'addRegComplaint'])->name('complaints.reg-add');
    Route::post('/complaints/registered/add', [RegComplaintController::class, 'insertRegisteredComplaint'])->name('insert-registered');
    Route::get('/complaints/registered/unresolved', [RegComplaintController::class, 'unresolved'])->name('complaints.reg-unresolved');
    Route::get('/complaints/registered/unresolved/{complaint}', [RegComplaintController::class, 'showUnresolved'])->name('complaints.reg-unresolved-details');
    Route::get('/complaints/registered/denied', [RegComplaintController::class, 'denied'])->name('complaints.reg-denied');
    Route::get('/complaints/registered/denied/{complaint}', [RegComplaintController::class, 'showDeniedDetails'])->name('complaints.reg-denied-details');
    Route::put('/complaints/registered/settled/{complaint}/add-receipt', [RegComplaintController::class, 'addPaymentReceipt'])->name('complaints.reg-addPaymentReceipt');
    Route::delete('/complaints/registered/{complaint}', [RegComplaintController::class, 'deleteComplaint'])->name('complaint.reg-delete');
    //update routes
    Route::get('/complaints/registered/inprocess/{complaint}/edit', [RegComplaintController::class, 'inProcessEdit'])->name('complaints.reg-inprocess-edit');
    Route::put('/complaints/registered/inprocess/{complaint}/edit', [RegComplaintController::class, 'inProcessUpdate'])->name('complaints.reg-inprocess-update');
    // Route::get('/complaints/registered/settled/{complaint}/edit', [RegComplaintController::class, 'inProcessEdit'])->name('complaints.reg-settled-edit');
    // Route::put('/complaints/registered/settled/{complaint}/edit', [RegComplaintController::class, 'inProcessUpdate'])->name('complaints.reg-settled-update');
    // Route::get('/complaints/registered/unresolved/{complaint}/edit', [RegComplaintController::class, 'inProcessEdit'])->name('complaints.reg-unresolved-edit');
    // Route::put('/complaints/registered/unresolved/{complaint}/edit', [RegComplaintController::class, 'inProcessUpdate'])->name('complaints.reg-unresolved-update');
    // Route::get('/complaints/registered/denied/{complaint}/edit', [RegComplaintController::class, 'inProcessEdit'])->name('complaints.reg-denied-edit');
    // Route::put('/complaints/registered/denied/{complaint}/edit', [RegComplaintController::class, 'inProcessUpdate'])->name('complaints.reg-denied-update');
    //UNREGISTERED COMPLAINTS
    Route::get('/complaints/colorum/inqueue', [UnregComplaintController::class, 'index'])->name('complaints.unreg-inqueue');
    Route::get('/complaints/colorum/inqueue/{complaint}', [UnregComplaintController::class, 'showDetails'])->name('complaints.unreg-inqueue-show');
    Route::put('/complaints/colorum/inqueue/{complaint}', [UnregComplaintController::class, 'setMeeting'])->name('complaints.unreg-setMeeting');
    Route::put('/complaints/colorum/inqueue/{complaint}/deny', [UnregComplaintController::class, 'reasonForDenying'])->name('complaints.unreg-deny');
    Route::get('/complaints/colorum/inprocess', [UnregComplaintController::class, 'inprocessComplaint'])->name('complaints.unreg-inprocess');
    Route::get('/complaints/colorum/inprocess/{complaint}', [UnregComplaintController::class, 'showInprocess'])->name('complaints.unreg-inprocess-show');
    Route::put('/complaints/colorum/inprocess/{complaint}/resolve', [UnregComplaintController::class, 'setResolution'])->name('complaints.unreg-setResolution');
    Route::get('/complaints/colorum/settled', [UnregComplaintController::class, 'settled'])->name('complaints.unreg-settled');
    Route::get('/complaints/colorum/settled/{complaint}', [UnregComplaintController::class, 'showSettled'])->name('complaints.unreg-settled-details');
    Route::get('/complaints/colorum/add', [UnregComplaintController::class, 'addUnregComplaint'])->name('complaints.unreg-add');
    Route::post('/complaints/colorum/add', [UnregComplaintController::class, 'insertUnregisteredComplaint'])->name('insert-unregistered');
    Route::get('/complaints/colorum/unresolved', [UnregComplaintController::class, 'unresolved'])->name('complaints.unreg-unresolved');
    Route::get('/complaints/colorum/denied', [UnregComplaintController::class, 'denied'])->name('complaints.unreg-denied');
    Route::put('/complaints/colorum/inprocess/{complaint}/unresolve', [UnregComplaintController::class, 'setResolutionUnresolved'])->name('complaints.unreg-setResolutionUnresolved');
    Route::get('/complaints/colorum/unresolved/{complaint}', [UnregComplaintController::class, 'showUnresolved'])->name('complaints.unreg-unresolved-details');
    Route::get('/complaints/colorum/denied/{complaint}', [UnregComplaintController::class, 'showDeniedDetails'])->name('complaints.unreg-denied-details');
    // Route::put('/complaints/colorum/settled/{complaint}/add-receipt', [UnregComplaintController::class, 'addPaymentReceipt'])->name('complaints.unreg-addPaymentReceipt');
    Route::put('/complaints/colorum/settled/{complaint}/add-receipt', [UnregComplaintController::class, 'addPaymentReceipt'])->name('complaints.unreg-addPaymentReceipt');
    Route::get('/complaints/colorum/inprocess/{complaint}/edit', [UnregComplaintController::class, 'inProcessEdit'])->name('complaints.unreg-inprocess-edit');
    Route::put('/complaints/colorum/inprocess/{complaint}/edit', [UnregComplaintController::class, 'inProcessUpdate'])->name('complaints.unreg-inprocess-update');
    Route::get('/complaints/colorum/settled/{complaint}/edit', [UnregComplaintController::class, 'inProcessEdit'])->name('complaints.unreg-settled-edit');
    Route::put('/complaints/colorum/settled/{complaint}/edit', [UnregComplaintController::class, 'inProcessUpdate'])->name('complaints.unreg-settled-update');
    Route::get('/complaints/colorum/unresolved/{complaint}/edit', [UnregComplaintController::class, 'inProcessEdit'])->name('complaints.unreg-unresolved-edit');
    Route::put('/complaints/colorum/unresolved/{complaint}/edit', [UnregComplaintController::class, 'inProcessUpdate'])->name('complaints.unreg-unresolved-update');
    Route::get('/complaints/colorum/denied/{complaint}/edit', [UnregComplaintController::class, 'inProcessEdit'])->name('complaints.unreg-denied-edit');
    Route::put('/complaints/colorum/denied/{complaint}/edit', [UnregComplaintController::class, 'inProcessUpdate'])->name('complaints.unreg-denied-update');
    Route::delete('/complaints/colorum/{complaint}', [UnregComplaintController::class, 'deleteComplaint'])->name('complaint.unreg-delete');

    Route::get('/complaints/{complaint}', [ComplaintController::class, 'show'])->name('complaints.show');
    Route::delete('/complaints/{complaint}', [ComplaintController::class, 'delete'])->name('complaint.delete');
    Route::get('/complaints/{complaint}/edit', [ComplaintController::class, 'complaintEdit'])->name('complaints.edit');
    Route::put('/complaints/{complaint}', [ComplaintController::class, 'complaintUpdate'])->name('complaints.update');
    Route::get('/complaints/status/{status}', [ComplaintController::class, 'filterByStatus'])->name('complaints.status');
    Route::put('/complaints/{complaint}/denied', [ComplaintController::class, 'denied'])->name('complaints.denied');
    Route::put('/complaints/{complaint}/accept', [ComplaintController::class, 'accept'])->name('complaints.accept');

    //users
    Route::get('/users', [UserInfoController::class, 'index'])->name('userinfo');
    Route::delete('/users/{userInfo}', [UserInfoController::class, 'deleteUser'])->name('user.delete');
    Route::get('/users/add', [UserInfoController::class, 'addUser'])->name('user.add');
    Route::post('/users/add', [UserInfoController::class, 'insertUser'])->name('user.insert');
    Route::get('/users/edit/{user}', [UserInfoController::class, 'editUser'])->name('user.edit');
    Route::put('/users/edit/{user}', [UserInfoController::class, 'updateUser'])->name('user.update');
    Route::get('/users/search', [UserInfoController::class, 'search'])->name('userinfo.search');


    //violations
    Route::get('/violation', [ViolationController::class, 'violationAll'])->name('violations');
    Route::get('/violation/create', [ViolationController::class, 'violationCreate'])->name('violation.store');
    Route::post('/violation/create', [ViolationController::class, 'violationAdd']);
    Route::delete('/violation/{violationID}', [ViolationController::class, 'violationDelete'])->name('violation.delete');
    Route::get('/violations/{violation}/edit', [ViolationController::class, 'violationEdit'])->name('violation.edit');
    Route::put('/violations/{violation}', [ViolationController::class, 'violationUpdate'])->name('violation.update');

    //driver
    Route::get('/driver', [DriverController::class, 'index'])->name('drivers');
    Route::get('/driver/search', [DriverController::class, 'searchDriver'])->name('driver.search');
    Route::get('/driver/create', [DriverController::class, 'create'])->name('drivers.create');
    Route::post('/driver/create', [DriverController::class, 'addDriver'])->name('drivers.add');
    Route::delete('/driver/{driverID}', [DriverController::class, 'driverDelete'])->name('driver.delete');
    Route::get('/driver/{driver}/edit', [DriverController::class, 'editDriver'])->name('driver.edit');
    Route::put('/driver/{driver}', [DriverController::class, 'updateDriver'])->name('driver.update');
    route::get('/driver/{id}/qrcode', [DriverController::class, 'showQrCode'])->name('driver.qrcode');
    Route::get('/drivers/{id}/qrcode/download', [DriverController::class, 'downloadQrCode']);



    //toda
    Route::get('/toda', [TodaController::class, 'index'])->name('toda');
    Route::get('/toda/create', [TodaController::class, 'create'])->name('toda.create');
    Route::post('/toda/create', [TodaController::class, 'store'])->name('toda.store');
    Route::delete('/toda/{toda}', [TodaController::class, 'todaDelete'])->name('toda.delete');
    Route::get('/toda/{toda}/edit', [TodaController::class, 'editToda'])->name('toda.edit');
    Route::put('/toda/{toda}', [TodaController::class, 'updateToda'])->name('toda.update');


    //announcement
    Route::get('/announcement', [AnnouncementController::class, 'index'])->name('announcement');
    Route::get('/announcement/create', [AnnouncementController::class, 'create'])->name('announcement.create');
    Route::post('/announcement/create', [AnnouncementController::class, 'store'])->name('announcement.store');
    Route::delete('/announcement/{announcement}', [AnnouncementController::class, 'announcementDelete'])->name('announcement.delete');
    Route::get('/announcement/{announcement}/edit', [AnnouncementController::class, 'editAnnouncement'])->name('announcement.edit');
    Route::put('/announcement/{announcement}', [AnnouncementController::class, 'updateAnnouncement'])->name('announcement.update');
    Route::put('/announcement/{announcement}/set-active', [AnnouncementController::class, 'setAsActive'])->name('announcement.active');
    Route::put('/announcement/{announcement}/set-inactive', [AnnouncementController::class, 'setAsInactive'])->name('announcement.inactive');
});