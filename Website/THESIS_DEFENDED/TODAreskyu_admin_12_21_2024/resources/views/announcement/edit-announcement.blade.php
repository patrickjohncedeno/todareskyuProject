@extends('layout.shared')

@section('announcement', 'active')

@section('title_head', 'Edit Announcement')

@section('content')
@if (session('error'))
        <div class=" mt-2 alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <a href="{{ route('announcement') }}"><button class="btn btn-primary btn-sm mb-2">Back</button></a>
    
    <div class="h3">Edit Announcement</div>
    

    <div class="row mb-4">

        <form action="{{route('announcement.update', $announcement->announcementID)}}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label>
                    <h6>Title: </h6>
                </label>
                <input type="text" name="title" class="form-control" value="{{ $announcement->title }}">
            </div>
            <div class="form-group">
                <label>
                    <h6>Content: </h6>
                </label>
                <textarea name="content" id="" cols="30" rows="10" class="form-control">{{ $announcement->content }}</textarea>
            </div>
            <div class="form-group">
                <label>
                    <h6>Author: </h6>
                </label>
                <input type="text" name="author" class="form-control" value="{{ $announcement->author }}">
            </div>
            <div class="form-group">
                <label>
                    <h6>Date Posted: </h6>
                </label>
                <input type="date" name="datePosted" class="form-control"
                    value="{{ $announcement->datePosted ? $announcement->datePosted->format('Y-m-d') : '' }}">
            </div>
            

            <button type="submit" class="btn btn-primary mt-2">Update</button>
        </form>
    </div>
    </div>
@endsection
