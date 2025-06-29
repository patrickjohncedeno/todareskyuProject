@extends('layout.shared')

@section('tricycle', 'active')

@section('title_head', 'TODA')

@section('content')
   
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.5.0/font/bootstrap-icons.min.css">

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
</script>


    <ul class="nav mb-3 d-flex align-items-center rounded-4 shadow-sm py-1 bg-white overflow-hidden border-bottom">
        <li class="nav-item ms-1">
          <a class="nav-link " aria-current="page" href="{{ route('drivers') }}">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-person" viewBox="0 0 16 16">
  <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0m4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4m-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10s-3.516.68-4.168 1.332c-.678.678-.83 1.418-.832 1.664z"/>
</svg>
            <span>Driver</span>
        </a>
        </li>
        <li class="nav-item ">
          <a class="nav-link active" href="{{ route('toda') }}">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="rgba(237,92,89,255)" class="bi bi-bookmarks-fill" viewBox="0 0 16 16">
                <path d="M2 4a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v11.5a.5.5 0 0 1-.777.416L7 13.101l-4.223 2.815A.5.5 0 0 1 2 15.5z"/>
                <path d="M4.268 1A2 2 0 0 1 6 0h6a2 2 0 0 1 2 2v11.5a.5.5 0 0 1-.777.416L13 13.768V2a1 1 0 0 0-1-1z"/>
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

    <div class="shadow rounded-4 mb-5 overflow-hidden">
        <table class="table table-hovered m-0">
            <thead>
                <tr style="vertical-align: middle;">
                    <th class="">ID</th>
                    <th class="">TODA Name</th>
                    <th class="">Location</th>
                    <th class="">President</th>
                    <th class="">Contact Number</th>
                    <th class=""></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($todas as $toda)
                    <tr>
                        <td class="">{{ $toda->todaID }}</td>
                        <td class="">{{ $toda->todaName }}</td>
                        <td class="">{{ $toda->location }}</td>
                        <td class="">{{ $toda->presidentName }}</td>
                        <td class="">{{ $toda->contactNumber }}</td>
                     
                        <td class="" style=" width: 50px">
                      <span class="position-absolute translate-middle-y action-icons" style="margin-top: 13px;">
                        <a href="{{ route('toda.edit', $toda->todaID) }}"><i class="bi bi-pencil-square" style="color: black;"></i></a>
                      
                            

                            <form id="delete-form-{{ $toda->todaID }}"
                                action="{{ route('toda.delete', $toda->todaID) }}" method="POST"
                                style="display:none;">
                                @csrf
                                @method('DELETE')
                            </form>
                   
                               <i class="bi bi-trash"
                               onclick="confirmDelete('{{ addslashes($toda->todaName) }}', '{{ $toda->todaID }}')"></i>
                     </span>
                        </td> 
                    </tr>
                @endforeach
            </tbody>
        </table>
        </div>
        <div class="d-flex justify-content-center">
            {{ $todas->links() }}
        </div>
        <a href="{{ route('toda.create') }}">
            <button type="button" class="btn bg-gradient-blue text-white position-fixed bottom-0 mb-4 p-1 end-0 me-4 rounded-circle shadow-lg" 
                data-bs-toggle="tooltip" data-bs-placement="left" title="Add TODA">
                    <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" fill="currentColor" class="bi bi-plus-lg " viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2"/>
                      </svg>    
                </button>
        </a>
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

    function confirmDelete(todaName, todaID) {
            Swal.fire({
                title: 'Delete toda named <br><strong>' + todName + '?',
                text: "This action cannot be undone.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + todaID).submit();
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
