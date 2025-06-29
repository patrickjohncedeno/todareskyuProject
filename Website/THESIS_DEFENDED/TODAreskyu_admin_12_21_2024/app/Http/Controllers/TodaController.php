<?php

namespace App\Http\Controllers;

use App\Models\Toda;
use Illuminate\Http\Request;

class TodaController extends Controller
{
    public function index()
    {
        $todas = Toda::paginate(15);
        return view('tricycle.toda.toda-all', compact('todas'));
    }



    public function create()
    {
        return view('tricycle.toda.create-toda');
    }

    public function store(Request $request)
    {
        $request->validate([
            'todaName' => 'required|min:1|max:100',
            'location' => 'required|min:1|max:100',
            'contactNumber' => 'required|min:11',
            'presidentName' => 'required|min:1|max:100'
        ]);

        Toda::create([
            'todaName' => $request->todaName,
            'location' => $request->location,
            'contactNumber' => $request->contactNumber,
            'presidentName' => $request->presidentName
        ]);


        return redirect()->route('toda')->with('success', 'New TODA has been created.');
    }

    public function todaDelete(Toda $toda)
    {
        $todaName = $toda->todaName;

        $toda->delete();

        return redirect()->route('toda')->with('success', "$todaName has been deleted.");
    }

    public function editToda(Toda $toda)
    {
        return view('tricycle.toda.update-toda', compact('toda'));
    }

    public function updateToda(Request $request, Toda $toda)
    {
        $request->validate([
            'todaName' => 'required|min:1|max:100',
            'location' => 'required|min:1|max:100',
            'contactNumber' => 'required|min:11',
            'presidentName' => 'required|min:1|max:100'
        ]);

        $toda->update([
            'todaName' => $request->todaName,
            'location' => $request->location,
            'contactNumber' => $request->contactNumber,
            'presidentName' => $request->presidentName
        ]);

        $todaName = $toda->todaName;

        return redirect()->route('toda')->with('success', "$todaName has been updated.");
    }
}
