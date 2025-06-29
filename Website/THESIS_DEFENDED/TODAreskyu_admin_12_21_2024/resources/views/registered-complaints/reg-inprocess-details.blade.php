@extends('layout.reg-layout.reg-tab')

@section('content_title', 'Registered Complaints')

@section('pending', $pendingCount)
@section('inProcess', $inProcessCount)
@section('settled', $settledCount)
@section('denied', $deniedCount)
@section('unresolved', $unresolvedCount)

@section('content')
    <!-- Main Content -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css">

    <!-- Complaint Details -->
    <div id="complaintDetails001" class="mt-3 shadow rounded-4">
    <div class="card border-0 rounded-4 card-body">
            <!-- Modal Header -->
            <div class="border-0 d-flex flex-column">
                <div class="container-fluid d-flex justify-content-between align-items-center" style="list-style-type: circle;">
                    <p class="mb-0 fw-semibold">Control No. {{ str_pad($complaint->complaint_registered_ID, 3, '0', STR_PAD_LEFT) }}</p>
                    <button type="button" class="btn p-0 mb-3 shadow-none border-0 close-button"
                        onclick="window.location.href='{{ route('complaints.reg-inprocess') }}'">
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
    <div class="d-flex">
    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-person-vcard-fill me-1" viewBox="0 0 16 16">
  <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm9 1.5a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 0-1h-4a.5.5 0 0 0-.5.5M9 8a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 0-1h-4A.5.5 0 0 0 9 8m1 2.5a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 0-1h-3a.5.5 0 0 0-.5.5m-1 2C9 10.567 7.21 9 5 9c-2.086 0-3.8 1.398-3.984 3.181A1 1 0 0 0 2 13h6.96q.04-.245.04-.5M7 6a2 2 0 1 0-4 0 2 2 0 0 0 4 0"/>
</svg>
               
 <h5>     <i class="fa-solid fa-steering-wheel"></i>Driver's Information</h5>
    </div>
            
                    <div class="card-text" style="flex: 1;">
                        <p class="fs-6 mb-1 mt-2"><span class="fw-semibold">Driver:</span>
                            {{ $complaint->driver->driverName }}</p>
                        <p class=" mb-1"><span class="fw-semibold">Contact Number:</span>
                            {{ $complaint->driver->driverPhoneNum }}</p>
                        <p class=""><span class="fw-semibold">TODA:</span> {{ $complaint->driver->toda->todaName }}</p>
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
                <div class="col-6">
                    <h5><i class="fa-solid fa-gavel me-1"></i>Penalty Details</h5>
                    <div class="card-text" style="flex: 1;">
                    <p class="fs-6 mb-1 mt-3"><span class="fw-semibold">Violation:</span> {{ $complaint->violations->violationName }}</p>
                    <p class="fs-6 mb-1"><span class="fw-semibold">Penalty Amount:</span> Php {{ $complaint->violationPrice }}</p>  
                </div>
                </div>
            </div>

            <hr class="my-2">
            <!-- Footer Buttons -->
            <div class=" text-center text-center d-flex justify-content-between px-2 py-1">
                <div></div>
                <div>
                    <button type="button" class="btn bg-gradient-blue text-white fs-6 p-2" data-bs-toggle="modal"
                        data-bs-target="#resolveModal">Resolve</button>
                    <button type="button" class="btn bg-gradient-red text-white fs-6 p-2" data-bs-toggle="modal"
                        data-bs-target="#unresolveModal">Unresolved</button>
                </div>
            </div>
        </div>
    </div>

     <!-- resolved modal -->
     <div class="modal fade" id="resolveModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addModalLabel">Resolve Complaint</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addForm" action="{{route('complaints.reg-setResolutionResolved', $complaint->complaint_registered_ID)}}" method="POST">
                        @csrf
                        @method("PUT")
                        <div class="mb-3">
                            <label for="messageText" class="form-label">Resolution Summary</label>
                            <textarea class="form-control" name="resolutionDetail" id="messageText" rows="4" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="penalty" class="form-label">Resolution Date</label>
                            <input type="datetime-local" class="form-control" name="dateResolve" id="penalty" required>
                        </div>
                        <button type="submit" class="btn bg-gradient-blue text-white fs-6 p-2">Submit</button>
                    </form>
                </div>
                <div class="modal-footer">

                    
                </div>
            </div>
        </div>
    </div>

    <!-- unresolved modal -->
    <div class="modal fade" id="unresolveModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addModalLabel">Unresolved Complaint</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addFormUnresolved" action="{{route('complaints.reg-setResolutionUnresolved', $complaint->complaint_registered_ID)}}" method="POST">
                        @csrf
                        @method("PUT")
                        <div class="mb-3">
                            <label for="messageText" class="form-label">Resolution Summary</label>
                            <textarea class="form-control" name="resolutionDetail" id="messageText" rows="4" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="penalty" class="form-label">Resolution Date</label>
                            <input type="datetime-local" class="form-control" name="dateResolve" id="penalty" required>
                        </div>
                        
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="submitAddFormUnresolved()">Submit</button>
                </div>
            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function submitAddFormUnresolved() {
        Swal.fire({
            title: 'Are you sure?',
            text: "Do you want to make this complaint unresolved?",
            icon: 'info',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Submit'
            
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('addFormUnresolved').submit();
            }
        });
    }
</script>
@endsection
