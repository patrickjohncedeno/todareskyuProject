@extends('layout.shared')

@section('tricycle', 'active')

@section('title_head', 'Add Driver')

@section('content')
<div class="container bg-white px-4 py-2 mt-3 rounded-3 shadow">
<button type="button" class="btn p-0 mb-3 shadow-none border-0 back-button"
    onclick="window.location.href='{{ route('drivers') }}'">
    <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" fill="currentColor" class="bi bi-arrow-left text-dark" viewBox="0 0 16 16">
        <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8"/>
    </svg>
</button>
    <div class="h3">Add Driver</div>

        <form action="{{ route('drivers.add') }}" method="POST">
            @csrf
            <div class="form-group">
                <label>
                    <h6>Driver Name: </h6>
                </label>
                <input type="text" name="driverName" class="form-control form-control-sm shadow-sm">
            </div>
            <div class="form-group">
                <label>
                    <h6>Contact Number: </h6>
                </label>
                <input type="tel" name="contactNumber" class="form-control form-control-sm shadow-sm" pattern="\d{11}"
                    title="Contact number should be 11 digits" maxlength="11">
            </div>
            <div class="form-group">
                <label>
                    <h6>Plate Number: </h6>
                </label>
                <input type="text" name="plateNumber" class="form-control form-control-sm shadow-sm" maxlength="6">
            </div>
            <div class="form-group">
                <label>
                    <h6>TIN Plate: </h6>
                </label>
                <input type="text" name="tinPlate" class="form-control form-control-sm shadow-sm" max="4">
            </div>
            <div class="form-group">
                <label>
                    <h6>TODA: </h6>
                </label>
                <select name="todaID" class="form-control form-control-sm shadow-sm">
                    @foreach ($todas as $toda)
                        <option value="{{ $toda->todaID }}">{{ $toda->todaName }}</option>
                    @endforeach
                </select>
            </div>
            <div class="text-end">
            <button type="submit" class="btn bg-gradient-blue mt-3 mb-2 text-white">Create</button>
            </div>
       
        </form>
    </div>
    </div>
@endsection
