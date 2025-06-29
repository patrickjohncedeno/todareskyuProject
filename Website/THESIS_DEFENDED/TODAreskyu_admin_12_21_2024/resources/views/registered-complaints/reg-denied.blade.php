@extends('layout.reg-layout.reg-tab')

@section('content_title', 'Registered Complaints')

@section('pending', $pendingCount)
@section('inProcess', $inProcessCount)
@section('settled', $settledCount)
@section('denied', $deniedCount)
@section('unresolved', $unresolvedCount)

@section('content')


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
    @if ($errors->any())
        <div class="mt-2 alert alert-danger alert-dismissible fade show" role="alert">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

   <!-- Add this CSS to ensure the border is removed from the icon cell -->

   <div class="rounded-4 shadow mb-5 overflow-hidden">
   <table class="table  m-0 table-hover rounded-3 align-middle">

    <thead class="border-dark">
        <tr class=" align-items-center " style="vertical-align:  middle;">
        <th scope="col">Control No.</th>
                <th scope="col">Complainant</th>
                <th scope="col">Driver</th>
                <th scope="col">Violation</th>
                <th scope="col">Resolution Date</th>
                <th scope="col">Reason for Denying</th>
                <th scope="col"></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($deniedComplaint as $complaint)
                <tr class="position-relative" role="button" onclick="window.location='{{route('complaints.reg-denied-details', $complaint->complaint_registered_ID)}}'">
                    <td scope="row">{{ str_pad($complaint->complaint_registered_ID, 3, '0', STR_PAD_LEFT) }}</td>
                    <td class="text-truncate" style="max-width: 200px;">{{ $complaint->user->firstName }} {{ $complaint->user->lastName }}</td>
                    <td class="text-truncate" style="max-width: 200px;">{{ $complaint->driver ? $complaint->driver->driverName : 'No Driver Assigned' }}</td>
                    <td class="text-truncate" style="max-width: 200px;">{{ $complaint->violations->violationName }}</td>
                    <td>{{ \Carbon\Carbon::parse($complaint->dateResolve)->format('F j, Y') }}</td>
                    <td class="text-truncate" style="max-width: 200px;">{{ $complaint->reasonForDenying }}</td>
                <td class="ms-2" style="border-left: none; width: 30px">
                    <!-- Floating icons inside a td with no border -->
                    {{-- <span class=" position-absolute top-50 end-0 translate-middle-y action-icons">
                    <a href="{{ route('complaints.reg-denied-edit', $complaint->complaint_registered_ID) }}" style="text-decoration: none;">
                            <i class="bi bi-pencil-square" style="color: black;"></i>
                        </a>
                        <form id="delete-form-{{ $complaint->complaint_registered_ID }}"
                                action="{{ route('complaint.reg-delete', $complaint->complaint_registered_ID) }}"
                                method="POST" style="display:none;">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm" type="submit">Delete</button>
                            </form>
                        <i class="bi bi-trash me-1"
                            onclick="confirmDelete('{{ $complaint->complaint_registered_ID }}', event)"></i>
                    </span> --}}
                </td>
                
            </tr>
        @endforeach
    </tbody>
</table>
</div>


    <div class="modal fade" id="updateModal" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">

            </div>

        </div>
    </div>
    </div>
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
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
    </div>

    </div>
    <script>
        let currentDeleteId = '';

        function openModal(controlNo, user, driver, dateSubmitted, location, violation, summary) {
            // Populate the modal with row details
            document.getElementById('controlNo').value = controlNo;
            document.getElementById('user').value = user;
            document.getElementById('driver').value = driver;
            document.getElementById('dateSubmitted').value = dateSubmitted;
            document.getElementById('location').value = location;
            document.getElementById('violation').value = violation;
            document.getElementById('summary').value = summary;
            // Show the modal
            var myModal = new bootstrap.Modal(document.getElementById('updateModal'));
            myModal.show();
        }

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









