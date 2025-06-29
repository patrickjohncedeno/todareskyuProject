<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use App\Models\UserInfo;
use App\Models\VerificationCode;
use App\Mail\VerificationEmail; 
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;




class AuthControllerForUsers extends Controller
{
    // Step 1: User Registration
    public function register(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:tbl_userinfo,email',
            'password' => 'required|min:8|confirmed',
            
        ]);
    
        // Create the user with email and password only
        $user = UserInfo::create([
            'email' => $request->email, // Store the email
            'password' => Hash::make($request->password),
            'firstName' => 'await',
            'lastName' => 'await',
            'phoneNumber' => 'await',
            'address' => 'await',
            'age' => '0',
            //'frontGovtID' => 'await',
           //'backGovtID' => 'await',
            'validID' => 'await',
        ]);
    
        // Generate and store verification code
        $verificationCode = rand(100000, 999999); // 6 digit code
        VerificationCode::create([
            'user_id' => $user->userID,
            'verification_code' => $verificationCode,
            'expires_at' => Carbon::now()->addMinutes(15),
        ]);
    
        // Send verification email
        Mail::to($user->email)->send(new VerificationEmail($verificationCode));
    
        return response()->json(['message' => 'Verification code sent to your email']);
    }
    

    // Step 2: Verify Email Code
    public function verifyCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'code' => 'required|digits:6',
        ]);
    
        $user = UserInfo::where('email', $request->email)->first();
    
        // Case 1: Invalid email address
        if (!$user) {
            return response()->json(['message' => 'Invalid email address'], 404);
        }
    
        // Cast the verification code to int for comparison
        $enteredCode = (int) $request->code;
    
        // Check if the verification code is valid and not expired
        $verificationCode = VerificationCode::where('user_id', $user->userID)
            ->where('verification_code', $enteredCode)
            ->where('expires_at', '>', Carbon::now())
            ->first();
    
        // Case 2: Code is invalid or doesn't match
        if (!$verificationCode) {
            // Check if the code exists but is expired
            $expiredCode = VerificationCode::where('user_id', $user->userID)
                ->where('verification_code', $enteredCode)
                ->where('expires_at', '<=', Carbon::now())
                ->first();
    
            if ($expiredCode) {
                return response()->json(['message' => 'Verification code expired'], 400);
            }
    
            // Case 3: Code is just invalid
            $user->delete();
            return response()->json(['message' => 'Invalid verification code'], 400);
        }
    
        // Code is valid, delete it
        $verificationCode->delete();
        
    
        return response()->json(['success' => true, 'message' => 'Email verified, proceed to add personal information']);
    }


    public function upload(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            //'front_id' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            //'back_id' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'validID' => 'required|image|mimes:jpeg,png,jpg,gif|max:10048',
            'email' => 'required|email', // Ensure email is also validated
        ]);
    
        try {
            // Store the uploaded images
            //$frontIdPath = $request->file('front_id')->store('uploads/id_images', 'public');
            //$backIdPath = $request->file('back_id')->store('uploads/id_images', 'public');\
            $validIDPath = $request->file('validID')->store('valid_ids', 'public');
    
            // Ensure both files were stored successfully
            //if (!$frontIdPath || !$backIdPath) {
            //    return response()->json(['message' => 'Failed to store images'], 500);
            //}

            if (!$validIDPath) {
                  return response()->json(['message' => 'Failed to store images'], 500);
                }
    
            // Find the user by email
            $user = UserInfo::where('email', $request->email)->first();
    
            if (!$user) {
                Log::error('User not found for email: ' . $request->email);
                return response()->json(['message' => 'User not found'], 404);
            }
    
            // Log the paths being updated
            Log::info('Updating user images:', [
                //'front_id_image' => $frontIdPath,
                //'back_id_image' => $backIdPath,
                'validID' => $validIDPath,
            ]);
    
            // Update the user's image paths in the database
            $updateSuccess = $user->update([
                //'frontGovtID' => 'storage/' . $frontIdPath,
                //'backGovtID' => 'storage/' . $backIdPath,
                'validID' => $validIDPath,
            ]);
    
            if ($updateSuccess) {
                Log::info('User updated successfully:', $user->toArray());
                return response()->json([
                    'message' => 'Images uploaded successfully',
                    //'frontgovtid_url' => asset('storage/' . $frontIdPath),
                    //'backgovtid_url' => asset('storage/' . $backIdPath),
                    'validid_url' => asset('storage/' . $validIDPath),
                ]);
            } else {
                Log::error('Failed to update user images for email: ' . $request->email);
                return response()->json(['message' => 'Failed to update images'], 500);
            }
        } catch (\Exception $e) {
            // Log the exception message
            Log::error('Error updating user images: ' . $e->getMessage());
            return response()->json(['message' => 'An error occurred while uploading images', 'error' => $e->getMessage()], 500);
        }
    }
    





    // Step 3: Complete Profile
    public function completeProfile(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'firstName' => 'required|string',
        'lastName' => 'required|string',
        'address' => 'required|string',
        'phoneNumber' => 'required|string',
        'age' => 'required|integer|min:1',
    ]);

    $user = UserInfo::where('email', $request->email)->first();

    if (!$user) {
        return response()->json(['message' => 'Invalid email address'], 404);
    }

    // Update the user profile
    $user->update([
        'firstName' => $request->firstName,
        'lastName' => $request->lastName,
        'address' => $request->address,
        'phoneNumber' => $request->phoneNumber,
        'age' => $request->age,
    ]);

    return response()->json(['message' => 'Profile completed successfully']);
    }
}
