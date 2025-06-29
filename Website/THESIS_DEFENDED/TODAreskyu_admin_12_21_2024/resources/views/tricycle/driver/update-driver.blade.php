@extends('layout.shared')

@section('violation', 'active')

@section('title_head', 'Update Driver')

@section('content')

<div class="container bg-white px-4 py-2 mt-3 rounded-3 shadow">
<button type="button" class="btn p-0 mb-3 shadow-none border-0 back-button"
    onclick="window.location.href='{{ route('drivers') }}'">
    <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" fill="currentColor" class="bi bi-arrow-left text-dark" viewBox="0 0 16 16">
        <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8"/>
    </svg>
</button>
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="row mb-4">
        <form action="{{ route('driver.update', $driver->driverID) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label>
                    <h6>Driver Name: </h6>
                </label>
                <input type="text" name="driverName" class="form-control form-control-sm shadow-sm" value="{{ $driver->driverName }}">
            </div>
            <div class="form-group">
                <label>
                    <h6>Contact Number: </h6>
                </label>
                <input type="tel" name="contactNumber" class="form-control form-control-sm shadow-sm" pattern="\d{11}" title="Contact number should be 11 digits" maxlength="11" value="{{ $driver->contactNumber }}">
            </div>
            <div class="form-group">
                <label>
                    <h6>Plate Number: </h6>
                </label>
                <input type="text" name="plateNumber" class="form-control form-control-sm shadow-sm" value="{{ $driver->plateNumber }}" maxlength="6">
            </div>
            <div class="form-group">
                <label>
                    <h6>TIN Plate: </h6>
                </label>
                <input type="text" name="tinPlate" class="form-control form-control-sm shadow-sm" value="{{ $driver->tinPlate }}">
            </div>
            <div class="form-group">
                <label>
                    <h6>TODA: </h6>
                </label>
                <select name="todaID" class="form-control form-control-sm shadow-sm">
                    @foreach ($todas as $toda)
                        <option value="{{ $toda->todaID }}" {{ $toda->todaID == $driver->todaID ? 'selected' : '' }}>
                            {{ $toda->todaName }}</option>
                    @endforeach
                </select>
            </div>
            
            <div class="text-end">
            <button type="submit" class="btn bg-gradient-blue mt-3 text-white">Update</button>
            </div>
       
        </form>
    </div>
    </div>
@endsection
