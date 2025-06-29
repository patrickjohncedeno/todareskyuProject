@extends('layout.unreg-layout.unreg-tab')

@section('content_title', 'Unregistered Complaints')

@section('pending', $pendingCount)
@section('inProcess', $inProcessCount)
@section('settled', $settledCount)
@section('denied', $deniedCount)
@section('unresolve', $unresolvedCount)

@section('content')
     <!-- Main Content -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css">

<!-- Complaint Details -->
<div id="complaintDetails001" class="mt-3 shadow rounded-4">
<div class="card border-0 rounded-4 card-body">
        <!-- Modal Header -->
        <div class="border-0 d-flex flex-column">
            <div class="container-fluid d-flex justify-content-between align-items-center" style="list-style-type: circle;">
                <p class="mb-0 fw-semibold">Control No. {{ str_pad($complaint->complaint_unregistered_ID, 3, '0', STR_PAD_LEFT) }}</p>
                <button type="button" class="btn p-0 mb-3 shadow-none border-0 close-button"
                    onclick="window.location.href='{{ route('complaints.unreg-inqueue') }}'">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-x-lg" viewBox="0 0 16 16">
                        <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8z"/>
                    </svg>
                </button>
            </div>
            <h5 class="text-decoration-underline text-center fw-bold">Alleged Violation: {{ $complaint->violations->violationName }}</h5>
        </div>
        <hr class="my-2">
 <!-- Example of col-6 Layout -->
 <div class="row">
            <!-- Column 1 -->
            <div class="col-6 ">
                <div class="d-flex">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-person-fill" viewBox="0 0 16 16">
            <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6"/>
            </svg>
                <h5>Complainant's Information</h5>
                </div>
            
                <div class="d-flex flex-row align-items-center">
                    <div class=" align-self-center bg-transparent">
                        <img src="{{ asset('storage/' . $complaint->user->validID) }}" 
                            class="img-fluid rounded-4 overflow-hidden bg-transparent" 
                            alt="ID Image"
                            style="width: 120px; height: auto; object-fit: cover;">
                    </div>
                    <div class="card-text mb-4" style="flex: 1;">
                        <p class="fs-6 mb-1 ms-2 mt-2"><span class="fw-semibold">Complainant:</span>
                            {{ $complaint->user->firstName }} {{ $complaint->user->lastName }}</p>
                        <p class="ms-2 mb-1"><span class="fw-semibold">Contact Number:</span>
                            {{ $complaint->user->phoneNumber }}</p>
                        <p class="ms-2 mb-1"><span class="fw-semibold">Home Address:</span> {{ $complaint->user->address }}</p>
                    </div>
                </div>
            </div>

           <!-- Column 2 -->
           <div class="col-6">
<div class="d-flex mb-3">
<svg class="align-self-center" width="24" height="24" xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 16 16">
                        <image href="{{asset('images/trike-removebg-preview.png')}}" x="0" y="0" width="16px" height="16px"/>
                    </svg>
<h5 class="mb-0"></i>Tricycle's Information</h5>
</div>
        
                <div class="card-text" style="flex: 1;">
                    <p class=" mb-1  mt-2"><span class="fw-semibold">Tricycle Color:</span>
                        {{ $complaint->tricycleColor }}</p>
                    <p class="mb-1"><span class="fw-semibold">Tricycle Description:</span>
                        {{ $complaint->tricycleDescription }}</p>
                    <p class=" mb-1"><span class="fw-semibold">Plate Number:</span> {{ $complaint->plateNumber }}</p>
                    <div class="d-flex">
                    <p class=" mb-1"><span class="fw-semibold">Evidence Photo:</span> </p>
                    <img src="{{ asset('storage/' . $complaint->evidencePhoto) }}" alt="Hatdog Image" style="width: 200px; height: auto;">
                    </div>
                    
                </div>
            </div>


        <!-- Example for Incident Information -->
        <div class="row mt-4">
            <div class="col-6">
                <h5><i class="fa-solid fa-triangle-exclamation"></i>
                Incident Information</h5>
                <div class="card-text" style="flex: 1;">
                <p class="fs-6 mb-1 mt-3"><span class="fw-semibold ">Place of Incident:</span> {{ $complaint->location }}</p>
                <p class="fs-6 mb-1 "><span class="fw-semibold ">Summary of Incident:</span> {{ $complaint->description }}</p>
                <p class="fs-6 mb-1 "><span class="fw-semibold ">Date Submitted:</span>
                    {{ \Carbon\Carbon::parse($complaint->dateSubmitted)->format('F j, Y g:ia') }}</p>
            </div>
            </div>
            </div>

            <!-- Modal Footer -->
            <div class="border-top align-items-center d-flex justify-content-end px-2 py-1">

                <button type="button" class="btn bg-gradient-blue text-white fs-6 p-2 m-1" data-bs-toggle="modal"
                    data-bs-target="#acceptModal">Accept</button>
                <button type="button" class="btn bg-gradient-red text-white fs-6 p-2 m-1" data-bs-toggle="modal"
                    data-bs-target="#denyModal">Deny</button>

            </div>
        </div>

        <!-- accept modal -->
        <div class="modal fade" id="acceptModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addModalLabel">Accept Complaint</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="addForm"
                            action="{{ route('complaints.unreg-setMeeting', $complaint->complaint_unregistered_ID) }}"
                            method="POST">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label for="violationName" class="form-label">Set a meeting date</label>
                                <input type="datetime-local" name="meetingDate" id="violationName">
                            </div>
                            <button type="submit" class="btn bg-gradient-blue text-white fs-6 p-2">Submit</button>
                        </form>
                    </div>
                    <div class="modal-footer">

                        {{-- <button type="button" class="btn bg-gradient-blue text-white fs-6 p-2"
                            onclick="submitAddForm()">Submit</button> --}}
                    </div>
                </div>
            </div>
        </div>

        <!-- accept modal -->
        <div class="modal fade" id="denyModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addModalLabel">Deny Complaint</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="addForm"
                            action="{{ route('complaints.unreg-deny', $complaint->complaint_unregistered_ID) }}"
                            method="POST">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label for="violationName" class="form-label">Reason of Denial</label>
                                <input type="text" class="form-control" name="reasonForDenying" id="violationName"
                                    required>
                                <button type="submit" class="btn bg-gradient-blue text-white fs-6 p-2">Submit</button>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">

                        {{-- <button type="button" class="btn bg-gradient-blue text-white fs-6 p-2"
                            onclick="submitAddForm()">Submit</button> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
