@extends('layout.shared')

@section('tricycle', 'active')

@section('title_head', 'Add TODA')

@section('content')
    <a href="{{ route('toda') }}"><button class="btn btn-primary btn-sm mb-2">Back</button></a>
    <div class="h3">Add TODA</div>

    <div class="row mb-4">
        <form action="{{ route('toda.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label>
                    <h6>TODA Name:</h6>
                </label>
                <input type="text" name="todaName" class="form-control">
            </div>
            <div class="form-group">
                <label>
                    <h6>Location:</h6>
                </label>
                <input type="text" name="location" class="form-control">
            </div>
            <div class="form-group">
                <label>
                    <h6>Contact Number:</h6>
                </label>
                <input type="number" name="contactNumber" class="form-control">
            </div>
            <div class="form-group  mb-2">
                <label>
                    <h6>President Name: </h6>
                </label>
                <input type="text" name="presidentName" class="form-control">
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
    </div>
@endsection
