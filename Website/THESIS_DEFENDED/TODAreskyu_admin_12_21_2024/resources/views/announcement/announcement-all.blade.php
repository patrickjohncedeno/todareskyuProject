@extends('layout.shared')

@section('announcement', 'active')

@section('title_head', 'CTMO Announcements')

@section('content')

    @if (session('success'))
        <div class=" mt-2 alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <div class="shadow rounded-4 overflow-hidden mb-4">

<table class="table m-0 table-hover rounded-3 align-middle">
    <thead class="border-dark">
                <tr>
                    <th class="">ID</th>
                    <th class="">Title</th>
                    <th class="">Content</th>
                    <th class="">Author</th>
                    <th class="">Date Posted</th>

                    <th class="">Status</th>
                    <th class="" colspan="3">Operations</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($announcements as $announcement)
                    <tr>
                        <td class="">{{ $announcement->announcementID }}</td>
                        <td class="">{{ $announcement->title }}</td>
                        <td class="">{{ $announcement->content }}</td>
                        <td class="">{{ $announcement->author }}</td>
                        <td class="">
                            {{ \Carbon\Carbon::parse($announcement->datePosted)->format('F j, Y ') }}</td>

                        <td class="">
                            @if ($announcement->status === 'Inactive')
                                <span class="badge rounded-pill bg-secondary">{{ $announcement->status }}</span>
                            @else
                                <span class="badge rounded-pill bg-success">{{ $announcement->status }}</span>
                            @endif

                        </td>
                        <td class="">
                            <a href="{{ route('announcement.edit', $announcement->announcementID) }}"><button
                                    class="btn btn-primary btn-sm">Update</button></a>
                        </td>

                        <td class="">
                            <form id="delete-form-{{ $announcement->announcementID }}"
                                action="{{ route('announcement.delete', $announcement->announcementID) }}" method="POST"
                                style="display:none;">
                                @csrf
                                @method('DELETE')
                            </form>
                            <button class="btn btn-danger btn-sm"
                                onclick="confirmDelete('{{ $announcement->title }}','{{ $announcement->announcementID }}')">Delete</button>
                        </td>
                        <td class="">
                            @if ($announcement->status === 'Inactive')
                                <form action="{{ route('announcement.active', $announcement->announcementID) }}"
                                    method="POST" style="display: inline;">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-link">Set as Active</button>
                                </form>
                            @else
                                <form action="{{ route('announcement.inactive', $announcement->announcementID) }}"
                                    method="POST" style="display: inline;">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-link">Set as Inactive</button>
                                </form>
                            @endif

                        </td>

                    </tr>
                @endforeach
            </tbody>
        </table>
        <a href="{{ route('announcement.create') }}">
            <button type="button" class="btn bg-gradient-blue text-white position-fixed bottom-0 mb-4 p-1 end-0 me-4 rounded-circle shadow-lg" 
                data-bs-toggle="tooltip" data-bs-placement="left" title="Add Announcement">
                    <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" fill="currentColor" class="bi bi-plus-lg " viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2"/>
                      </svg>    
                </button>
        </a>
    </div>
    </div>
    <script>
        function confirmDelete(title, announcementID) {
            Swal.fire({
                title: 'Delete Announcement <br><strong>' + title + '?',
                text: "This action cannot be undone.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + announcementID).submit();
                }
            });
        }
    </script>


@endsection
