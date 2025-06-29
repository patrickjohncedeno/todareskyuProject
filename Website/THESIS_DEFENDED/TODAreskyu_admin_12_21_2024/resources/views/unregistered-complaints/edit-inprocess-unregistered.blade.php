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
            <a href="{{ route('complaints.unreg-inprocess') }}">
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
                action="{{ route('complaints.unreg-inprocess-update', $complaint->complaint_unregistered_ID) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Form container for better alignment -->
                <div class="container-fluid">
                    <div class="row g-3">
                        <!-- Control No -->
                        <div class="col-md-6">
                            <label for="controlNo" class="form-label">Control No.</label>
                            <input type="text" class="form-control rounded shadow-sm" id="controlNo"
                                value="{{ str_pad($complaint->complaint_unregistered_ID, 3, '0', STR_PAD_LEFT) }}" readonly>
                        </div>

                        <!-- User -->
                        <div class="col-md-6">
                            <label for="user" class="form-label">User</label>
                            <input type="text" class="form-control rounded shadow-sm" id="user"
                                value="{{ $complaint->user->firstName }} {{ $complaint->user->lastName }}" readonly>
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
                                value="{{ $complaint->violations->violationName }}" > --}}
                            <select id="violation" class="form-control form-select form-select-sm" name="violationID">
                                <option value="{{ $complaint->violationID }}" selected>
                                    {{ $complaint->violations->violationName }}</option>
                                @foreach ($violations as $violation)
                                    <option value="{{ $violation->violationID }}">{{ $violation->violationName }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Tricycle Information --}}
                        {{-- Plate Number --}}
                        <div class="col-md-6">
                            <label for="plateNumber" class="form-label">Plate Number</label>
                            <input type="text" class="form-control rounded shadow-sm" id="plateNumber" name="plateNumber"
                                value="{{ $complaint->plateNumber }}">
                        </div>
                        {{-- Tricycle Color --}}
                        <div class="col-md-6">
                            <label for="tricycleColor" class="form-label">Tricycle Color</label>
                            <input type="text" class="form-control rounded shadow-sm" id="tricycleColor"
                                name="tricycleColor" value="{{ $complaint->tricycleColor }}">
                        </div>
                        {{-- Tricycle Description --}}
                        <div class="col-md-6">
                            <label for="tricycleDescription" class="form-label">Tricycle Description</label>
                            <input type="text" class="form-control rounded shadow-sm" id="tricycleDescription"
                                name="tricycleDescription" value="{{ $complaint->tricycleDescription }}">
                        </div>
                        {{-- Evidence Photo --}}
                        <div class="col-md-12">
                            <label for="evidencePhoto" class="form-label">Evidence Photo</label>
                            <input type="file" class="form-control rounded shadow-sm" id="evidencePhoto"
                                name="evidencePhoto" accept="image/*" onchange="previewImage(event)">
                            <div class="d-flex justify-content-center align-items-center mt-3" style="gap: 20px;">
                                {{-- Current Photo Preview --}}
                                @if ($complaint->evidencePhoto)
                                    <div class="text-center">
                                        <label>Evidence Photo:</label>
                                        <div class="d-flex justify-content-center align-items-center"
                                            style="width: 200px; height: 200px; border: 1px solid #ddd; border-radius: 5px; overflow: hidden;">
                                            <img src="{{ asset('storage/' . $complaint->evidencePhoto) }}"
                                                alt="Current Evidence Photo" id="currentPhoto"
                                                style="max-width: 100%; max-height: 100%; object-fit: contain;">
                                        </div>
                                    </div>
                                @endif

                                {{-- Preview of New Upload --}}
                                <div id="previewContainer" class="text-center" style="display: none;">
                                    <label>New Evidence Preview:</label>
                                    <div class="d-flex justify-content-center align-items-center"
                                        style="width: 200px; height: 200px; border: 1px solid #ddd; border-radius: 5px; overflow: hidden;">
                                        <img id="newPhotoPreview"
                                            style="max-width: 100%; max-height: 100%; object-fit: contain;">
                                    </div>
                                </div>
                            </div>
                        </div>


                        <!-- Summary -->
                        <div class="col-12">
                            <label for="summary" class="form-label">Summary of Incident</label>
                            <textarea class="form-control rounded shadow-sm" id="summary" rows="4" name="description"
                                style="resize: none;">{{ $complaint->description }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Footer Buttons -->
                <div class="modal-footer mt-4">
                    <a class="me-2" href="{{ route('complaints.unreg-inprocess') }}">
                        <button type="button" class="btn btn-secondary shadow-sm">Close</button>
                    </a>
                    <button type="button" class="btn btn-primary shadow-sm" id="submitUpdateButton"
                        onclick="confirmUpdate()">Update</button>
                </div>
            </form>
        </div>
    </div>


    <script>
        function previewImage(event) {
            const file = event.target.files[0];
            const previewContainer = document.getElementById('previewContainer');
            const newPhotoPreview = document.getElementById('newPhotoPreview');

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewContainer.style.display = 'block'; // Show the preview container
                    newPhotoPreview.src = e.target.result; // Set the image source
                };
                reader.readAsDataURL(file);
            } else {
                previewContainer.style.display = 'none'; // Hide the preview container if no file
            }
        }

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

                    // Submit the form
                    document.getElementById('updateForm').submit();
                }
            });
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection
