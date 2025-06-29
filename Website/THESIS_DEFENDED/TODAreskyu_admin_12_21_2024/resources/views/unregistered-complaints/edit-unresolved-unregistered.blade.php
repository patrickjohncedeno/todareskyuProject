@extends('layout.unreg-layout.unreg-tab')

@section('content_title', 'Unregistered Tricycle Complaints')

@section('content')

<!-- SweetAlert2 CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="bg-white p-3 rounded-4 shadow">
        <div class="modal-header mb-3">
            <h5 class="modal-title" id="updateModalLabel">Update Incident</h5>
            <a href="{{ route('complaints.unreg-unresolved') }}">
                <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </a>
        </div>
        <div class="">
            <form id="updateForm" method="POST"
                action="{{ route('complaints.unreg-unresolved-update', $complaint->complaint_unregistered_ID) }}">
                @csrf
                @method('PUT')

                <!-- Form container for better alignment -->
                <div class="container-fluid">
                    <div class="row g-3">
                        <!-- Control No -->
                        <div class="col-md-6">
                            <label for="controlNo" class="form-label">Control No.</label>
                            <input type="text" class="form-control rounded shadow-sm" id="controlNo"
                                value="{{ str_pad($complaint->complaintunregistered_ID, 3, '0', STR_PAD_LEFT) }}">
                        </div>

                        <!-- User -->
                        <div class="col-md-6">
                            <label for="user" class="form-label">User</label>
                            <input type="text" class="form-control rounded shadow-sm" id="user"
                                value="{{ $complaint->user->firstName }} {{ $complaint->user->lastName }}">
                        </div>


                        <!-- Date Submitted -->
                        <div class="col-md-6">
                            <label for="dateSubmitted" class="form-label">Date Submitted</label>
                            <input type="text" class="form-control rounded shadow-sm" id="dateSubmitted"
                                value="{{ \Carbon\Carbon::parse($complaint->dateSubmitted)->format('F j, Y g:ia') }}" >
                        </div>

                        <!-- Location -->
                        <div class="col-md-6">
                            <label for="location" class="form-label">Location</label>
                            <input type="text" class="form-control rounded shadow-sm" id="location"
                                value="{{ $complaint->location }}">
                        </div>

                        <!-- Violation -->
                        <div class="col-md-6">
                            <label for="violation" class="form-label">Violation</label>
                            <input type="text" class="form-control rounded shadow-sm" id="violation"
                                value="{{ $complaint->violations->violationName }}" >
                        </div>

                        <!-- Summary -->
                        <div class="col-12">
                            <label for="summary" class="form-label">Summary of Incident</label>
                            <textarea class="form-control rounded shadow-sm" id="summary" rows="4" name="description"
                                style="resize: none;">{{ $complaint->description }}</textarea>
                        </div>
                        <div class="col-12">
                            <label for="summary" class="form-label">Unresolved Detail</label>
                            <textarea class="form-control rounded shadow-sm" id="summary" rows="4" name="description"
                                style="resize: none;">{{ $complaint->resolutionDetail }}</textarea>
                        </div>

                    </div>
                </div>

                <!-- Footer Buttons -->
                <div class="modal-footer mt-4">
                    <a class="me-2" href="{{ route('complaints.unreg-unresolved') }}">
                        <button type="button" class="btn btn-secondary shadow-sm">Close</button>
                    </a>
                    <button type="button" class="btn btn-primary shadow-sm" id="submitUpdateButton" onclick="confirmUpdate()">Update</button>
                </div>
            </form>
        </div>
    </div>


    <script>
        document.getElementById('submitUpdateButton').addEventListener('click', function () {
            // Show the confirmation modal
            var confirmationModal = new bootstrap.Modal(document.getElementById('confirmationModal'));
            confirmationModal.show();
        });
  
        function confirmUpdate() {
        Swal.fire({
            title: 'Confirm Update',
            text: 'Do you want to update this record?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, update!',
            cancelButtonText: 'Cancel',
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
        }).then((result) => {
            if (result.isConfirmed) {
                // Submit the form
                document.getElementById('updateForm').submit();
            }
        });
    }
    </script>
          <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection

