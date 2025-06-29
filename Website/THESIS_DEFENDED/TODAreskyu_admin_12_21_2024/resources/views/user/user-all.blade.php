@extends('layout.shared')

@section('commuter', 'active')

@section('title_head', 'Commuters List')


@section('content')


    @if (session('success'))
        <div class=" mt-2 alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if (session('error'))
        <div class=" mt-2 alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.5.0/font/bootstrap-icons.min.css">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
    <!-- Search Bar -->
    <form action="{{ route('userinfo.search') }}" method="GET" class="mb-3">
        @csrf
        <div class="input-group">
            <input type="text" name="query" class="form-control" placeholder="Search commuters..."
                value="{{ old('query', $query ?? '') }}">
            <button type="submit" class="btn btn-primary">Search</button>
        </div> 
    </form>

    <div class="shadow rounded-4 overflow-hidden mb-4">

        <table class="table m-0 table-hover rounded-3 align-middle">
            <thead class="border-dark">
                <tr style="vertical-align:  middle;">
                    <th class=" ">User ID</th>
                    <th class="">First Name</th>
                    <th class="">Last Name</th>
                    <th class="">Email</th>
                    <th class="">Address</th>
                    <th class="">Age</th>
                    <th class="">Phone Number</th>
                    <th class="">Verification Status</th>
                    <th class=""></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td class="">{{ $user->userID }}</td>
                        <td class="">{{ $user->firstName }}</td>
                        <td class="">{{ $user->lastName }}</td>
                        <td class="">{{ $user->email ? $user->email : 'Do not have email.' }}</td>
                        <td class="">{{ $user->address }}</td>
                        <td class="">{{ $user->age }}</td>
                        <td class="">{{ $user->phoneNumber }}</td>
                        <td class="pe-0" style="max-width: 25px">
                            @if ($user->verified === 1)
                                <span class="badge bg-success rounded-pill">Verified</span>
                            @else
                                @if (!empty($user->validID))
                                    <span class="badge bg-info rounded-pill text-light">Valid ID Uploaded</span>
                                @endif
                                <span class="badge bg-warning rounded-pill text-dark">Not Verified</span>
                            @endif
                        </td>

                        <td class="" style=" width: 50px">
                            <span class="position-absolute translate-middle-y action-icons">
                                <a href="{{ route('user.edit', $user->userID) }}"><i class="bi bi-pencil-square"
                                        style="color: black;"></i></a>



                                <form id="delete-form-{{ $user->userID }}"
                                    action="{{ route('user.delete', $user->userID) }}" method="POST"
                                    style="display:none;">
                                    @csrf
                                    @method('DELETE')
                                </form>

                                <i class="bi bi-trash" style="cursor: pointer"
                                    onclick="confirmDelete('{{ $user->firstName }}', '{{ $user->lastName }}' ,'{{ $user->userID }}')"></i>
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="d-flex justify-content-center">
            {{ $users->links() }}
        </div>
        <div>
            <a href="{{ route('user.add') }}">
                <button type="button"
                    class="btn bg-gradient-blue text-white position-fixed bottom-0 mb-4 p-1 end-0 me-4 rounded-circle shadow-lg"
                    data-bs-toggle="tooltip" data-bs-placement="left" title="Add Commuter">
                    <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" fill="currentColor"
                        class="bi bi-plus-lg " viewBox="0 0 16 16">
                        <path fill-rule="evenodd"
                            d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2" />
                    </svg>
                </button></a>
        </div>
    </div>
    </div>
    <script>
        function confirmDelete(firstName, lastName, userID) {
            Swal.fire({
                title: 'Delete user named <br><strong>' + firstName + ' ' + lastName + '?',
                text: "This action cannot be undone.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + userID).submit();
                }
            });
        }
    </script>
@endsection
