@extends('layout.layout')

@section('title', 'Update')

@section('content')

    <a href="{{ route('violations') }}"><button class="btn btn-primary btn-sm">Back</button></a>
    <div class="h3">Update Violation</div>

    <div class="row mb-4">
        <form action="{{ route('violation.update', $violation->violationID) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label>
                    <h6>Violation Name:</h6>
                </label>
                <input type="text" name="violationName" class="form-control" value="{{$violation->violationName}}">
            </div>
            <div class="form-group">
                <label for="driverID">
                    <h6>Penalty: </h6>
                </label>
                <input type="number" name="penalty" step="0.01" min="0" class="form-control" value="{{$violation->penalty}}">
            </div>
            
            <br>
            <button type="submit" class="btn btn-primary">Update</button>
        </form>



    </div>


    </div>
@endsection


