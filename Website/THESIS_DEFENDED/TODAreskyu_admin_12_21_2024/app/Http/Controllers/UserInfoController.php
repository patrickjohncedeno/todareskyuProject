<?php

namespace App\Http\Controllers;

use App\Models\UserInfo;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;


class UserInfoController extends Controller
{
    public function index()
    {
        $users = UserInfo::paginate(15);
        return view('user.user-all', compact('users'));
    }

    public function addUser()
    {
        return view('user.add-user');
    }

    public function insertUser(Request $request)
    {
        $request->validate([
            'firstName' => 'required',
            'lastName' => 'required',
            'address' => 'required',
            'phoneNumber' => 'required',
            'age' => 'required|integer',
            'validID' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10096',

        ]);

        $validIDpath = null;

        if ($request->hasFile('validID')) {
            $validIDpath = $request->file('validID')->store('valid_ids', 'public');
        }

        UserInfo::create([
            'firstName' => $request->firstName,
            'lastName' => $request->lastName,
            'address' => $request->address,
            'phoneNumber' => $request->phoneNumber,
            'age' => $request->age,
            'validID' => $validIDpath,


        ]);

        return redirect()->route('userinfo')->with('success', 'User added successfully.');
    }


    public function editUser(UserInfo $user)
    {
        return view('user.user-update', compact('user'));
    }

    public function updateUser(Request $request, UserInfo $user)
    {
        $request->validate([
            'firstName' => 'required',
            'lastName' => 'required',
            'address' => 'required',
            'phoneNumber' => 'required',
            'age' => 'required|integer',
            'validID' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:4096'
        ]);

        $validIDpath = $user->validID;

        if ($request->hasFile('validID')) {
            $validIDpath = $request->file('validID')->store('valid_ids', 'public');
        }

        $user->update([
            'firstName' => $request->firstName,
            'lastName' => $request->lastName,
            'address' => $request->address,
            'phoneNumber' => $request->phoneNumber,
            'age' => $request->age,
            'validID' => $validIDpath,
            'verified' => $request->has('verified') ? 1 : 0
        ]);

        return redirect()->route('userinfo')->with('success', 'User updated successfully.');
    }


    public function deleteUser(UserInfo $userInfo)
    {
        // $userID->delete();

        // return redirect()->route('userinfo')->with('success', 'User deleted successfully.'); 
        if ($userInfo->registeredComplaints()->exists() || $userInfo->unregisteredComplaints()->exists()) {

            return redirect()->route('userinfo')->with('error', 'User cannot be deleted as there are associated complaints.');
        }

        $userInfo->delete();

        return redirect()->route('userinfo')->with('success', 'User deleted successfully.');
    }

    public function getUserInfo($userID)
    {
        try {
            $user = UserInfo::select('firstName', 'lastName', 'email', 'address', 'age', 'phoneNumber')
                ->where('firstName', $userID)
                ->first();

            if (!$user) {
                return response()->json(['error' => 'User not found'], 404);
            }

            return response()->json(['user' => $user], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred'], 500);
        }
    }

    //Search filter
    public function search(Request $request)
    {
        $query = $request->input('query'); // Retrieve the search query

        // Search for matching records in first name, last name, or their combination
        $users = UserInfo::where('firstName', 'LIKE', "%{$query}%")
            ->orWhere('lastName', 'LIKE', "%{$query}%")
            ->orWhereRaw("CONCAT(firstName, ' ', lastName) LIKE ?", ["%{$query}%"]) // Full name search
            ->paginate(15); 

        return view('user.user-all', compact('users', 'query'));
    }
}
