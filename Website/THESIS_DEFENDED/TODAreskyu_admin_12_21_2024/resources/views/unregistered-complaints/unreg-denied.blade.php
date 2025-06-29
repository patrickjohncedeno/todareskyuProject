@extends('layout.unreg-layout.unreg-tab')

@section('content_title', 'Unregistered Complaints')

@section('pending', $pendingCount)
@section('inProcess', $inProcessCount)
@section('settled', $settledCount)
@section('denied', $deniedCount)
@section('unresolve', $unresolvedCount)

@section('content')

    <style>
        .panel-table .panel-body {
            padding: 0;
        }

        .panel-table .panel-body .table-bordered {
            border-style: none;
            margin: 0;
        }

        .panel-table .panel-body .table-bordered>thead>tr>th:first-of-type {
            text-align: center;
            width: 100px;
        }

        .panel-table .panel-body .table-bordered>thead>tr>th:last-of-type,
        .panel-table .panel-body .table-bordered>tbody>tr>td:last-of-type {
            border-right: 0px;
        }

        .panel-table .panel-body .table-bordered>thead>tr>th:first-of-type,
        .panel-table .panel-body .table-bordered>tbody>tr>td:first-of-type {
            border-left: 0px;
        }

        .panel-table .panel-body .table-bordered>tbody>tr:first-of-type>td {
            border-bottom: 0px;
        }

        .panel-table .panel-body .table-bordered>thead>tr:first-of-type>th {
            border-top: 0px;
        }

        .panel-table .panel-footer .pagination {
            margin: 0;
        }

        /*
                                        used to vertically center elements, may need modification if you're not using default sizes.
                                        */
        .panel-table .panel-footer .col {
            line-height: 34px;
            height: 34px;
        }

        .panel-table .panel-heading .col h3 {
            line-height: 30px;
            height: 30px;
        }

        .panel-table .panel-body .table-bordered>tbody>tr>td {
            line-height: 34px;
        }

        /* Show icons on row hover */
        tr:hover .action-icons {
            visibility: visible;
        }

        /* Initially hide the icons */
        .action-icons {
            visibility: hidden;
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.5.0/font/bootstrap-icons.min.css">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
    <!-- Tab panes -->
    @if (session('success'))
        <div class=" mt-2 alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- inprocess tab   -->

    
   <div class="rounded-4 mb-5 overflow-hidden shadow">
   <table class="table m-0 table-hover rounded-3 align-middle">
        <thead class="border-dark">
            <tr>
              

<th scope="col">Control No.</th>
                    <th scope="col">Complainant</th>
                    <th scope="col">Tricycle Description</th>
                    <th scope="col">Violation</th>
                    <th scope="col">Resolution Date</th>
                    <th scope="col">Reason for Denying</th>
                    <th scope="col"></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($deniedComplaint as $complaint)
                    <tr class="position-relative" role="button" onclick="window.location='{{route('complaints.unreg-denied-details',$complaint->complaint_unregistered_ID)}}'">
                        <td scope="row">{{ str_pad($complaint->complaint_unregistered_ID, 3, '0', STR_PAD_LEFT) }}</td>
                         <td class="text-truncate" style="max-width: 150px;">{{ $complaint->user->firstName }} {{ $complaint->user->lastName }}</td>
                         <td class="text-truncate" style="max-width: 150px;">{{ $complaint->tricycleColor }}, {{ $complaint->tricycleDescription }}, {{ $complaint->plateNumber }}</td>
                         <td class="text-truncate" style="max-width: 200px;">{{ $complaint->violations->violationName }}</td>
                        <td>{{ \Carbon\Carbon::parse($complaint->dateResolve)->format('F j, Y') }}</td>
                         <td class="text-truncate" style="max-width: 150px;">{{ $complaint->reasonForDenying }}</td>
                        <td>
                        <!-- Floating icons -->
                        <span class=" translate-middle-y action-icons">
                            <a href="{{ route('complaints.unreg-denied-edit', $complaint->complaint_unregistered_ID) }}"
                                style="text-decoration: none;">
                                <i class="bi bi-pencil-square" style="color: black;"></i>
                            </a>
                            {{-- <i class="bi bi-trash" onclick="confirmDelete('001'); event.stopPropagation();"></i> --}}
                            <form id="delete-form-{{ $complaint->complaint_unregistered_ID }}"
                                action="{{ route('complaint.unreg-delete', $complaint->complaint_unregistered_ID) }}"
                                method="POST" style="display:none;">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm" type="submit">Delete</button>
                            </form>
                            <i class="bi bi-trash"
                                onclick="confirmDelete('{{ $complaint->complaint_unregistered_ID }}', event)"></i>
                        </span>
                    </td>
                </tr>
            @endforeach

        </tbody>
    </table>
    </div>
    {{-- <div class="row">
            @foreach ($complaints as $complaint)
                <div class="col-3">
                    <div id="complaintCard001"
                        class="card border-0 dashboard-card bg-white shadow mb-3 rounded-3 text-start"
                        style="max-width: 18rem;"
                        onclick="toggleComplaintDetails('complaintCard001', 'complaintDetails001')">
                        <div class="card-header bg-white border-0 fw-bolder">Complaint No. {{ $complaint->complaintID }}
                            <hr class="dark-horizontal mb-0 mt-1">
                        </div>

                        <div class="card-body text-center" onclick="window.location.href=''">
                            <div class="mb-3">
                                <p class="card-text h6 m-0">{{ $complaint->user->firstName }}
                                    {{ $complaint->user->lastName }}</p>
                                <small class="text-muted">Complainant</small>
                            </div>
                            <div>
                                <p class="card-text h6 m-0">{{ $complaint->violations->violationName }}</p>
                                <small class="text-muted">Alleged Violation</small>
                            </div>
                        </div>
                        <div class="card-footer bg-white border-0 text-center">
                            <small class="text-muted">Date Submitted: </small>
                            <p class="card-text h6 m-0">
                                {{ \Carbon\Carbon::parse($complaint->dateSubmitted)->format('F j, Y g:ia') }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div> --}}
    <div class="modal fade" id="updateModal" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateModalLabel">Update Incident</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="updateForm">
                        <div class="mb-3">
                            <label for="controlNo" class="form-label">Control No.</label>
                            <input type="text" class="form-control" id="controlNo" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="user" class="form-label">User</label>
                            <input type="text" class="form-control" id="user" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="driver" class="form-label">Driver</label>
                            <input type="text" class="form-control" id="driver" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="dateSubmitted" class="form-label">Date Submitted</label>
                            <input type="text" class="form-control" id="dateSubmitted" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="location" class="form-label">Location</label>
                            <input type="text" class="form-control" id="location" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="violation" class="form-label">Violation</label>
                            <input type="text" class="form-control" id="violation" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="summary" class="form-label">Summary of Incident</label>
                            <textarea class="form-control" id="summary" rows="3"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="submitUpdate()">Update</button>
                </div>
            </div>
        </div>
    </div>



    {{-- <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this row?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteButton">Yes</button>
                </div>
            </div>
        </div>
    </div> --}}



    <script>
        // let currentDeleteId = '';

        // function openModal(controlNo, user, driver, dateSubmitted, location, violation, summary) {
        //     // Populate the modal with row details
        //     document.getElementById('controlNo').value = controlNo;
        //     document.getElementById('user').value = user;
        //     document.getElementById('driver').value = driver;
        //     document.getElementById('dateSubmitted').value = dateSubmitted;
        //     document.getElementById('location').value = location;
        //     document.getElementById('violation').value = violation;
        //     document.getElementById('summary').value = summary;
        //     // Show the modal
        //     var myModal = new bootstrap.Modal(document.getElementById('updateModal'));
        //     myModal.show();
        // }

        function submitUpdate() {
            const summary = document.getElementById('summary').value;
            alert('Incident updated with summary: ' + summary);
            // Here, you would add your update logic
            var myModal = bootstrap.Modal.getInstance(document.getElementById('updateModal'));
            myModal.hide();
        }

        function confirmDelete(controlNo) {
            currentDeleteId = controlNo;
            var myModal = new bootstrap.Modal(document.getElementById('deleteModal'));
            myModal.show();
        }

        document.getElementById('confirmDeleteButton').addEventListener('click', function() {
            if (confirm('Are you sure you want to delete?')) {
                alert('Row ' + currentDeleteId + ' deleted.');
                // Here, you would add your delete logic
                var myModal = bootstrap.Modal.getInstance(document.getElementById('deleteModal'));
                myModal.hide();
            }
        });

        function confirmDelete(complaintID, event) {
            event.stopPropagation(); 
            event.preventDefault();
            Swal.fire({
                title: 'Delete complaint?',
                text: "This action cannot be undone.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Delete'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + complaintID).submit();
                }
            });
        }
    </script>
@endsection




