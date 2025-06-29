@extends('layout.reg-layout.reg-tab')

@section('content_title', 'Registered Tricycle Complaints')

@section('content')

    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="bg-white p-3 rounded-4 shadow">
        <div class="modal-header mb-3">
            <h5 class="modal-title" id="updateModalLabel">Update Incident</h5>
            <a href="{{ route('complaints.reg-inprocess') }}">
                <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </a>
        </div>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="">

            <form id="updateForm" method="POST"
                action="{{ route('complaints.reg-inprocess-update', $complaint->complaint_registered_ID) }}">
                @csrf
                @method('PUT')

                <!-- Form container for better alignment -->
                <div class="container-fluid">
                    <div class="row g-3">
                        <!-- Control No -->
                        <div class="col-md-6">
                            <label for="controlNo" class="form-label">Control No.</label>
                            <input type="text" class="form-control rounded shadow-sm" id="controlNo"
                                value="{{ str_pad($complaint->complaint_registered_ID, 3, '0', STR_PAD_LEFT) }}" readonly>
                        </div>

                        <!-- User -->
                        <div class="col-md-6">
                            <label for="user" class="form-label">User</label>
                            <input type="text" class="form-control rounded shadow-sm" id="user"
                                value="{{ $complaint->user->firstName }} {{ $complaint->user->lastName }}" readonly>
                        </div>

                        <!-- Driver -->
                        <div class="col-md-6">
                            <label for="driver" class="form-label">Driver</label>
                            <input type="text" class="form-control rounded shadow-sm" id="driver"
                                value="{{ $complaint->driver->driverName }}" readonly>
                        </div>

                        <!-- Date Submitted -->
                        <div class="col-md-6">
                            <label for="dateSubmitted" class="form-label">Date Submitted</label>
                            <input type="text" class="form-control rounded shadow-sm" id="dateSubmitted"
                                value="{{ \Carbon\Carbon::parse($complaint->dateSubmitted)->format('F j, Y g:ia') }}"
                                readonly>
                        </div>

                        <!-- Location -->
                        <div class="col-md-6">
                            <label for="location" class="form-label">Location</label>
                            <input type="text" class="form-control rounded shadow-sm" id="location" name="location"
                                value="{{ $complaint->location }}">
                        </div>

                        <!-- Violation -->
                        <div class="col-md-6">
                            <label for="violation" class="form-label">Violation:
                                {{ $complaint->violations->violationName }}</label>
                            {{-- <input type="text" class="form-control rounded shadow-sm" id="violation"
                                value="{{ $complaint->violations->violationName }}"> --}}
                            <select id="violation" class="form-control form-select form-select-sm" name="violationID">
                                <option value="{{ $complaint->violationID }}" selected>
                                    {{ $complaint->violations->violationName }}</option>
                                @foreach ($violations as $violation)
                                    <option value="{{ $violation->violationID }}">{{ $violation->violationName }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Summary -->
                        <div class="col-12">
                            <label for="summary" class="form-label">Summary of Incident</label>
                            <textarea class="form-control rounded shadow-sm" id="summary" rows="4" name="description" style="resize: none;">{{ $complaint->description }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Footer Buttons -->
                <div class="modal-footer mt-4">
                    <a class="me-2" href="{{ route('complaints.reg-inprocess') }}">
                        <button type="button" class="btn btn-secondary shadow-sm">Close</button>
                    </a>
                    <button type="button" class="btn btn-primary shadow-sm" id="submitUpdateButton"
                        onclick="confirmUpdate()">Update</button>
                </div>
            </form>
        </div>
    </div>


    <script>
        // document.getElementById('submitUpdateButton').addEventListener('click', function() {
        //     // Show the confirmation modal
        //     var confirmationModal = new bootstrap.Modal(document.getElementById('confirmationModal'));
        //     confirmationModal.show();
        // });

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
                    // Redirect to the route
                    // window.location.href = "{{ route('complaints.reg-inprocess') }}";
                    // Submit the form

                    document.getElementById('updateForm').submit();
                    // window.location.href = "{{ route('complaints.reg-inprocess') }}";
                }
            });
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection
