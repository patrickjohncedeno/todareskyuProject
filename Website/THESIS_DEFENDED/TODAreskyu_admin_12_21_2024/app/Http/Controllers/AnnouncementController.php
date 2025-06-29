<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    public function index(){
        $announcements = Announcement::get();
        return view('announcement.announcement-all', compact('announcements'));
    }

    public function create(){
        
        return view('announcement.create-announcement');
    }

    public function store(Request $request){
        $request->validate([
            'title' => 'required|min:2|max:100',
            'content' => 'required|min:5|max:100',
            'author' => 'required|min:2|max:100',
            'datePosted' => 'required|date',
            
        ]);

        Announcement::create([
            'title' => $request->title,
            'content' => $request->content,
            'author' => $request->author,
            'datePosted' => $request->datePosted,
            
        ]);

        return redirect()->route('announcement')->with('success','New Announcement has been created.');
    }

    public function editAnnouncement(Announcement $announcement){
        return view('announcement.edit-announcement', compact('announcement'));
    }

    public function updateAnnouncement(Request $request, Announcement $announcement){
        // dd($request);
        
        $request->validate([
            'title' => 'required|min:2|max:100',
            'content' => 'required|min:5|max:100',
            'author' => 'required|min:2|max:100',
            'datePosted' => 'required|date',
        ]);
        

        $announcement->update([
            'title' => $request->title,
            'content' => $request->content,
            'author' => $request->author,
            'datePosted' => $request->datePosted,
        ]);

        return redirect()->route('announcement')->with('success','Announcement has been updated.');
    }

    public function announcementDelete(Announcement $announcement){
        $announcement->delete();

        return redirect()->route('announcement')->with('success','Announcement removed.');
    }

    public function setAsActive(Announcement $announcement){
        $announcement->update([
            'status' => 'Active'
        ]);

        return redirect()->route('announcement')->with('success','Announcement '. $announcement->title . ' is now active.');
    }

    public function setAsInactive(Announcement $announcement){
        $announcement->update([
            'status' => 'Inactive'
        ]);

        return redirect()->route('announcement')->with('success','Announcement '. $announcement->title . ' is now inactive.');
    }
}
