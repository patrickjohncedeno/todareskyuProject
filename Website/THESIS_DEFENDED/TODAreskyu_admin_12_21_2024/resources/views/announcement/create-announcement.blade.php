@extends('layout.shared')

@section('announcement', 'active')

@section('title_head', 'Create New Announcement')

@section('content')
    <a href="{{ route('announcement') }}"><button class="btn btn-primary btn-sm mb-2">Back</button></a>
    <div class="h3">Create New Announcement</div>

    <div class="row mb-4">
        <form action="{{ route('announcement.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label>
                    <h6>Title: </h6>
                </label>
                <input type="text" name="title" class="form-control">
            </div>
            <div class="form-group">
                <label>
                    <h6>Content: </h6>
                </label>
                <textarea class="form-control" name="content" cols="30" rows="10"></textarea>
            </div>
            <div class="form-group">
                <label>
                    <h6>Author: </h6>
                </label>
                <input type="text" name="author" class="form-control">
            </div>
            <div class="form-group">
                <label>
                    <h6>Date Posted: </h6>
                </label>
                <input type="date" name="datePosted" class="form-control">
            </div>
            
            <button type="submit" class="btn btn-primary mt-2">Submit</button>
        </form>
    </div>
    </div>

@endsection
