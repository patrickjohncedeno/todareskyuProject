<?php

namespace App\Http\Controllers;

use App\Models\Violation;
use Illuminate\Http\Request;

class ViolationController extends Controller
{
    //
    public function violationAll(){
        $violations = Violation::all();

        return view('violation.violation-all', compact('violations'));
    }

    public function violationCreate(){
        return view('violation.create-violation');
    }

    public function violationAdd(Request $request){
        $request->validate([
            'violationName' => 'required|min:3|unique:tbl_violation,violationName',
            'penalty' => 'required|numeric|regex:/^\d+(\.\d{1,2})?$/',
        ]);

        Violation::create([
            'violationName' => $request->violationName,
            'penalty' => $request->penalty,
        ]);

        return redirect()->route('violations')->with('success', 'Violation created successfully.');
    }

    public function violationDelete(Violation $violationID)
    {
        $violationID->delete();

        return redirect()->route('violations')->with('success', 'Violation deleted successfully.');
    }

    public function violationEdit(Violation $violation)
    {
        return view('violation.update-violation', compact('violation'));
    }

    public function violationUpdate(Request $request, Violation $violation){
        $request->validate([
            'violationName' => 'required|min:3',
            'penalty' => 'required|numeric|regex:/^\d+(\.\d{1,2})?$/'
        ]);

        $violation->update([
            'violationName' => $request->violationName,
            'penalty' => $request->penalty,
        ]);

        return redirect()->route('violations')->with('success', 'Violation updated successfully.');
    }

    

}
