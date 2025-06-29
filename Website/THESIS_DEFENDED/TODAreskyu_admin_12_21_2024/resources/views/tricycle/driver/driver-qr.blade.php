@extends('layout.shared')

@section('tricycle', 'active')

@section('title_head', 'QR Code Generator')

@section('content')
<div class="container bg-white px-4 py-2 mt-3 rounded-3 shadow">
<button type="button" class="btn p-0 mb-3 shadow-none border-0 back-button"
    onclick="window.location.href='{{ route('drivers') }}'">
    <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" fill="currentColor" class="bi bi-arrow-left text-dark" viewBox="0 0 16 16">
        <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8"/>
    </svg>
</button>
    <h4>QR Code for Driver: {{ $driver->driverName }}</h4>
    <h4>Driver Tin Plate: {{ $driver->tinPlate }}</h4>
    <div class="text-center">
        {!! $qrCode !!}
    </div>
<div class="text-end">
<a href="{{ url('/drivers/' . $driver->driverID . '/qrcode/download') }}" class="btn bg-gradient-blue mb-2 mt-4 text-white">Download QR Code</a>
</div>
    
    </div>  
@endsection
