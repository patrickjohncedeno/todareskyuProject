@extends('layout.reg-layout.reg-tab')

@section('content_title', 'Registered Complaints')

@section('pending', $pendingCount)
@section('inProcess', $inProcessCount)
@section('settled', $settledCount)
@section('denied', $deniedCount)
@section('unresolved', $unresolvedCount)

@section('content')


    <!-- Main Content  -->


    <!-- Tab panes -->
    @if (session('success'))
        <div class=" mt-2 alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif



    <div class="rounded-4 shadow mb-5 overflow-hidden">
    <table class="table table-hover m-0">


            @foreach ($regComplaints as $complaint)
            <tbody class="">
                <tr class="" style="cursor: pointer" onclick="window.location.href='{{ route('complaints.reg-inqueue-show', $complaint->complaint_registered_ID) }}'">
                    <td class="ps-3 text-start">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                            fill="currentColor" class="bi bi-envelope-exclamation me-1" viewBox="0 0 16 16">
                            <path
                                d="M2 2a2 2 0 0 0-2 2v8.01A2 2 0 0 0 2 14h5.5a.5.5 0 0 0 0-1H2a1 1 0 0 1-.966-.741l5.64-3.471L8 9.583l7-4.2V8.5a.5.5 0 0 0 1 0V4a2 2 0 0 0-2-2zm3.708 6.208L1 11.105V5.383zM1 4.217V4a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v.217l-7 4.2z" />
                            <path
                                d="M12.5 16a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7m.5-5v1.5a.5.5 0 0 1-1 0V11a.5.5 0 0 1 1 0m0 3a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0" />
                        </svg>
                        <span class="fw-bold">Control No.
                            {{ str_pad($complaint->complaint_registered_ID, 3, '0', STR_PAD_LEFT) }}</span>
                    </td>
                    <td class="text-center">{{ $complaint->user->firstName }}
                                    {{ $complaint->user->lastName }}
                    </td>
                    <td class="text-end text-muted pe-3">{{ \Carbon\Carbon::parse($complaint->dateSubmitted)->format('F j, Y g:ia') }}
                    </td>
                </tr>
                
              </tbody>
            {{--  --}}
                
            @endforeach
            </table>

            </div>
        <a href="{{ route('complaints.reg-add') }}">
            <button type="button" class="btn bg-gradient-blue text-white position-fixed bottom-0 mb-4 p-1 end-0 me-4 rounded-circle shadow-lg" 
                data-bs-toggle="tooltip" data-bs-placement="left" title="Add Registered Complaint">
                    <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" fill="currentColor" class="bi bi-plus-lg " viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2"/>
                      </svg>    
                </button>
        </a>

@endsection
