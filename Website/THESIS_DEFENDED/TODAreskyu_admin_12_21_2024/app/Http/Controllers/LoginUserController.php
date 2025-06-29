<?php

namespace App\Http\Controllers;

use App\Models\UserInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class LoginUserController extends Controller
{
    public function login(Request $request)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Find the user by email
        $user = UserInfo::where('email', $request->email)->first();


        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'.$request->email,
            ], 404);
        }

        // Debugging
        

        // Check if the password matches
        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid password: ',
            ], 401);
        }

        // Return success response
        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'user' => $user,
        ], 200);
    }
}
