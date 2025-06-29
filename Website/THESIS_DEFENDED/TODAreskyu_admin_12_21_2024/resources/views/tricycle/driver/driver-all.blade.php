@extends('layout.shared')

@section('tricycle', 'active')

@section('title_head', 'Tricycle Drivers')

@section('content')


    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.5.0/font/bootstrap-icons.min.css">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>

    <ul class="nav mb-3 d-flex align-items-center rounded-4 shadow-sm py-1 bg-white overflow-hidden border-bottom">
        <li class="nav-item ms-1">
            <a class="nav-link active" aria-current="page" href="{{ route('drivers') }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="rgba(237,92,89,255)"
                    class="bi bi-person-fill" viewBox="0 0 16 16">
                    <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6" />
                </svg>
                <span>Drivers</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('toda') }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24s" fill="currentColor"
                    class="bi bi-bookmarks" viewBox="0 0 16 16">
                    <path
                        d="M2 4a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v11.5a.5.5 0 0 1-.777.416L7 13.101l-4.223 2.815A.5.5 0 0 1 2 15.5zm2-1a1 1 0 0 0-1 1v10.566l3.723-2.482a.5.5 0 0 1 .554 0L11 14.566V4a1 1 0 0 0-1-1z" />
                    <path
                        d="M4.268 1H12a1 1 0 0 1 1 1v11.768l.223.148A.5.5 0 0 0 14 13.5V2a2 2 0 0 0-2-2H6a2 2 0 0 0-1.732 1" />
                </svg>
                <span>TODA</span>
            </a>
        </li>
    </ul>
    @if (session('success'))
        <div class=" mt-2 alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <form action="{{ route('driver.search') }}" method="GET" class="mb-3">
        @csrf
        <div class="input-group">
            <input type="text" name="query" class="form-control" placeholder="Search drivers..." value="{{ old('query', $query ?? '') }}">
            <button type="submit" class="btn btn-primary">Search</button>
        </div>
    </form>

    <div class="shadow rounded-4 mb-5 overflow-hidden">

        <table class="table table-hover align-middle m-0">
            <thead>
                <tr>
                    <th class="">ID</th>
                    <th class="">Name</th>
                    <th class="">Phone Number</th>
                    <th class="">Plate Number</th>
                    <th class="">TIN Plate</th>
                    <th class="">QR Code</th>
                    <th class="">Toda</th>
                    <th class=""></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($drivers as $driver)
                    <tr class="position-relative" role="button">
                        <td class="">{{ $driver->driverID }}</td>
                        <td class="">{{ $driver->driverName }}</td>
                        <td class="">{{ $driver->driverPhoneNum }}</td>
                        <td class="">{{ $driver->plateNumber }}</td>
                        <td class="">{{ $driver->tinPlate }}</td>
                        <td class=""><a href="{{ route('driver.qrcode', $driver->driverID) }}">Generate QR Code</a>
                        </td>

                        <td class=""><span class="me-4">{{ $driver->toda->todaName }}</span>
                        </td>


                        <td class="" style=" width: 50px">
                            <span class="position-absolute translate-middle-y action-icons">
                                <a href="{{ route('driver.edit', $driver->driverID) }}"><i class="bi bi-pencil-square"
                                        style="color: black;"></i></a>



                                <form id="delete-form-{{ $driver->driverID }}"
                                    action="{{ route('driver.delete', $driver->driverID) }}" method="POST"
                                    style="display:none;">
                                    @csrf
                                    @method('DELETE')
                                </form>

                                <i class="bi bi-trash"
                                    onclick="confirmDelete('{{ addslashes($driver->driverName) }}', '{{ $driver->driverID }}')"></i>
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <a href="{{ route('drivers.create') }}">
        <button type="button"
            class="btn bg-gradient-blue text-white position-fixed bottom-0 mb-4 p-1 end-0 me-4 rounded-circle shadow-lg"
            data-bs-toggle="tooltip" data-bs-placement="left" title="Create New Driver">
            <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" fill="currentColor" class="bi bi-plus-lg "
                viewBox="0 0 16 16">
                <path fill-rule="evenodd"
                    d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2" />
            </svg>
        </button></a>

    </div>

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

        function confirmDelete(driverName, driverID) {
            Swal.fire({
                title: 'Delete driver named <br><strong>' + driverName + '?',
                text: "This action cannot be undone.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + driverID).submit();
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
