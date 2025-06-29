@extends('layout.shared')

@section('violation', 'active')

@section('title_head', 'Violations')

@section('content')


    @if (session('success'))
        <div class=" mt-2 alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.5.0/font/bootstrap-icons.min.css">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
    <div class="rounded-4 shadow mb-5 overflow-hidden">
        <table class="table table-hover align-middle m-0 rounded-3">
            <thead>
                <tr style="vertical-align:  middle;">
                    <th scope="col">Violations</th>
                    <th scope="col">Penalty</th>
                    <th scope="col">Created At</th>
                    <th scope="col">Updated At</th>
                    <th scope="col"></th>
                </tr>

            </thead>
            <tbody>
                @foreach ($violations as $violation)
                    <tr class="position-relative" role="button">
                        <td class="">{{ $violation->violationName }}</td>
                        <td class="">Php {{ $violation->penalty }}</td>
                        <td class="">{{ \Carbon\Carbon::parse($violation->created_at)->format('F j, Y g:ia') }}
                        </td>
                        <td class="">{{ \Carbon\Carbon::parse($violation->updated_at)->format('F j, Y g:ia') }}

                        </td>


                        <!-- Floating icons -->


                        <td class="" style=" width: 50px">
                            <span class="position-absolute translate-middle-y action-icons">
                                <a href="{{ route('violation.edit', $violation->violationID) }}"><i
                                        class="bi bi-pencil-square" style="color: black;"></i></a>



                                <form id="delete-form-{{ $violation->violationID }}"
                                    action="{{ route('violation.delete', $violation->violationID) }}" method="POST"
                                    style="display:none;">
                                    @csrf
                                    @method('DELETE')
                                </form>

                                <i class="bi bi-trash" onclick="confirmDelete('{{ $violation->violationID }}')"></i>
                            </span>
                        </td>
                    </tr>
                @endforeach

            </tbody>
        </table>
    </div>
    <a href="{{ route('violation.store') }}">
        <button type="button"
            class="btn bg-gradient-blue text-white position-fixed bottom-0 mb-4 p-1 end-0 me-4 rounded-circle shadow-lg"
            data-bs-toggle="tooltip" data-bs-placement="left" title="Add Violation">
            <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" fill="currentColor" class="bi bi-plus-lg "
                viewBox="0 0 16 16">
                <path fill-rule="evenodd"
                    d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2" />
            </svg>
        </button>
    </a>

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
            event.stopPropagation();
            event.preventDefault();
            Swal.fire({
                title: 'Delete Violation?',
                text: "This action cannot be undone.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Delete'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + controlNo).submit();
                }
            });
        }

        document.getElementById('confirmDeleteButton').addEventListener('click', function() {
            if (confirm('Are you sure you want to delete?')) {
                alert('Row ' + currentDeleteId + ' deleted.');
                // Here, you would add your delete logic
                var myModal = bootstrap.Modal.getInstance(document.getElementById('deleteModal'));
                myModal.hide();
            }
        });
    </script>
@endsection
