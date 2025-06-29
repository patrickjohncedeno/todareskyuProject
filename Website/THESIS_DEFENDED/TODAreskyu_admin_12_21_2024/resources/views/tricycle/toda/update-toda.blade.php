@extends('layout.shared')

@section('tricycle', 'active')

@section('title_head', 'Edit TODA')

@section('content')
    <a href="{{ route('toda') }}"><button class="btn btn-primary btn-sm mb-2">Back</button></a>
    <div class="h3">Update TODA</div>

    <div class="row mb-4">
        <form action="{{ route('toda.update', $toda->todaID) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label>
                    <h6>TODA Name:</h6>
                </label>
                <input type="text" name="todaName" class="form-control" value="{{ $toda->todaName }}">
            </div>
            <div class="form-group">
                <label>
                    <h6>Location:</h6>
                </label>
                <input type="text" name="location" class="form-control" value="{{ $toda->location }}">
            </div>
            <div class="form-group">
                <label>
                    <h6>Contact Number:</h6>
                </label>
                <input type="number" name="contactNumber" class="form-control" value="{{ $toda->contactNumber }}">
            </div>
            <div class="form-group mb-2">
                <label>
                    <h6>President Name: </h6>
                </label>
                <input type="text" name="presidentName" class="form-control" value="{{ $toda->presidentName }}">
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>
    </div>
@endsection
