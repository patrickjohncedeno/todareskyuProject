@extends('layout.shared')

@section('violation', 'active')

@section('title_head', 'Add Violation')

@section('content')

<div class="container bg-white px-4 py-2 mt-2 rounded-3 shadow">
<button type="button" class="btn p-0 mb-3 shadow-none border-0 back-button"
    onclick="window.location.href='{{ route('violations') }}'">
    <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" fill="currentColor" class="bi bi-arrow-left text-dark" viewBox="0 0 16 16">
        <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8"/>
    </svg>
</button>
        <form action="{{ route('violation.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label>
                    <h6>Violation Name:</h6>
                </label>
                <input type="text" name="violationName" class="form-control form-control-sm shadow-sm">
            </div>
            <div class="form-group">
                <label for="driverID">
                    <h6>Penalty: </h6>
                </label>
                <input type="number" name="penalty" step="0.01" min="0" class="form-control form-control-sm shadow-sm">
            </div>
            
            <br>
            <div class="text-end">
            <button type="submit" class="btn bg-gradient-blue text-white mb-2">Submit</button>
            </div>
  
        </form>



    </div>


    </div>
@endsection
{{-- <form action="{{ route('violation.store') }}" method="POST">
            @csrf
            <label>Violation: </label>
            <input type="text" name="violationName">
            <br>
            <label>Penalty: </label>
            <input type="number" name="penalty" step="0.01" min="0">
            <br>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form> --}}

