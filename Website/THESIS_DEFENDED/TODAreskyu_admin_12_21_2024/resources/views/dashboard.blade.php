<!-- base.html -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TODAreskyu Admin</title>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('color.css') }}">
    <link rel="stylesheet" href="{{ asset('newstyle.css') }}">
    {{-- <link rel="stylesheet" href="{{ asset('style.css') }}"> --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="icon" href="{{ asset('images/todareskyu_logo.png') }}" type="image/x-icon">
    <style>
        #chart-container {
            width: 100%;
            /* Adjust width to desired percentage */
            height: 50vh;
            /* Half of the viewport height */
            position: relative;
            margin: auto;
            /* Center container */
        }

        th {
            font-size: 14px;
            width: 200px;
        }

        tbody {
            font-size: 0.8rem;
        }

        .content,
        th {
            text-align: center;
        }

        #violationsChart,
        #statusChart {
            width: 100% !important;
            /* Ensure canvas width matches the container */
            height: 100% !important;
            /* Ensure canvas height matches the container */
        }
    </style>
</head>

<body class="container-fluid bg-white-50 bg-light-gray">
    @auth()
        <!-- Floating Navigation Bar Start -->
        <div class="bg-dark my-3 p-3 position-fixed rounded-3 bg-gradient-dark shadow-lg" style="width: 20%; height: 94%;">
            <ul class="nav flex-column">
                <svg class="align-self-center" width="120" height="100" xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 16 16">
                    <image href="images/todareskyu_logo.png" x="0" y="0" width="16px" height="16px" />
                </svg>

                <li class="nav-item mb-2 active rounded">
                    <a href="{{ route('index') }}"
                        class="d-flex flex-row ps-3 align-items-center text-decoration-none text-white w-100 rounded">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#ffffff"
                            class="bi bi-graph-up-arrow" viewBox="0 0 16 16">
                            <path fill-rule="evenodd"
                                d="M0 0h1v15h15v1H0zm10 3.5a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 .5.5v4a.5.5 0 0 1-1 0V4.9l-3.613 4.417a.5.5 0 0 1-.74.037L7.06 6.767l-3.656 5.027a.5.5 0 0 1-.808-.588l4-5.5a.5.5 0 0 1 .758-.06l2.609 2.61L13.445 4H10.5a.5.5 0 0 1-.5-.5" />
                        </svg>
                        <span class="fs-6 ms-2 p-2">Dashboard</span>
                    </a>
                </li>

                <li class="nav-item mb-2">
                    <a href="#complaintsMenu"
                        class="mb-1 d-flex flex-row align-items-center ps-3 text-decoration-none text-white w-100 rounded"
                        data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="complaintsMenu">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#ffffff"
                            class="bi bi-flag-fill" viewBox="0 0 16 16">
                            <path
                                d="M14.778.085A.5.5 0 0 1 15 .5V8a.5.5 0 0 1-.314.464L14.5 8l.186.464-.003.001-.006.003-.023.009a12 12 0 0 1-.397.15c-.264.095-.631.223-1.047.35-.816.252-1.879.523-2.71.523-.847 0-1.548-.28-2.158-.525l-.028-.01C7.68 8.71 7.14 8.5 6.5 8.5c-.7 0-1.638.23-2.437.477A20 20 0 0 0 3 9.342V15.5a.5.5 0 0 1-1 0V.5a.5.5 0 0 1 1 0v.282c.226-.079.496-.17.79-.26C4.606.272 5.67 0 6.5 0c.84 0 1.524.277 2.121.519l.043.018C9.286.788 9.828 1 10.5 1c.7 0 1.638-.23 2.437-.477a20 20 0 0 0 1.349-.476l.019-.007.004-.002h.001" />
                        </svg>
                        <span class="fs-6 ms-2 p-2">Complaints</span>
                    </a>

                    <div class="collapse" id="complaintsMenu">
                        <ul class="nav flex-column ps-4">
                            <li class="nav-item mb-1 submenu-item">
                                <a href="{{ route('complaints.reg-inqueue') }}"
                                    class="d-flex flex-row align-items-center text-decoration-none text-light w-100 rounded">
                                    <span class="fs-6 ms-2 p-2">Registered Complaints</span>
                                </a>
                            </li>
                            <li class="nav-item mb-1 submenu-item">
                                <a href="{{ route('complaints.unreg-inqueue') }}"
                                    class="d-flex flex-row align-items-center text-decoration-none text-light w-100 rounded">
                                    <span class="fs-6 ms-2 p-2">Unregistered Complaints</span>
                                </a>
                            </li>

                        </ul>
                    </div>

                </li>

                <li class="nav-item mb-2 rounded ">
                    <a href="{{ route('violations') }}"
                        class="d-flex flex-row align-items-center ps-3 text-decoration-none text-white w-100 rounded">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#ffffff"
                            class="bi bi-exclamation-triangle-fill" viewBox="0 0 16 16">
                            <path
                                d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5m.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2" />
                        </svg>
                        <span class="fs-6 ms-2 p-2">Violations</span>
                    </a>
                </li>

                <li class="nav-item mb-2 rounded">
                    <a href="{{ route('drivers') }}"
                        class="d-flex flex-row align-items-center ps-3 text-decoration-none text-white w-100 rounded">
                        <svg class="align-self-center" width="20" height="35" xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 16 16">
                            <image href="images/trike-removebg-preview.png" x="0" y="0" width="16px" height="16px"
                                style="filter: brightness(0) invert(1);" />
                        </svg>
                        <span class="fs-6 ms-2 p-2">Tricycle</span>
                    </a>
                </li>

                <li class="nav-item mb-2 rounded">
                    <a href="{{ route('userinfo') }}"
                        class="d-flex flex-row align-items-center ps-3 text-decoration-none text-white w-100 rounded">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#ffffff"
                            class="bi bi-person-walking" viewBox="0 0 16 16">
                            <path
                                d="M9.5 1.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0M6.44 3.752A.75.75 0 0 1 7 3.5h1.445c.742 0 1.32.643 1.243 1.38l-.43 4.083a1.8 1.8 0 0 1-.088.395l-.318.906.213.242a.8.8 0 0 1 .114.175l2 4.25a.75.75 0 1 1-1.357.638l-1.956-4.154-1.68-1.921A.75.75 0 0 1 6 8.96l.138-2.613-.435.489-.464 2.786a.75.75 0 1 1-1.48-.246l.5-3a.75.75 0 0 1 .18-.375l2-2.25Z" />
                            <path
                                d="M6.25 11.745v-1.418l1.204 1.375.261.524a.8.8 0 0 1-.12.231l-2.5 3.25a.75.75 0 1 1-1.19-.914zm4.22-4.215-.494-.494.205-1.843.006-.067 1.124 1.124h1.44a.75.75 0 0 1 0 1.5H11a.75.75 0 0 1-.531-.22Z" />
                        </svg>
                        <span class="fs-6 ms-2 p-2">Commuters</span>
                    </a>
                </li>

                <li class="nav-item mb-2 rounded">
                    <a href="{{ route('announcement') }}"
                        class="d-flex flex-row align-items-center ps-3 text-decoration-none text-white w-100 rounded">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#ffffff"
                            class="bi bi-people-fill" viewBox="0 0 16 16">
                            <path
                                d="M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1H7zm-4-6a2 2 0 1 1 4 0 2 2 0 0 1-4 0zm9 0a2 2 0 1 1 4 0 2 2 0 0 1-4 0zM1 13s-1 0-1-1 1-4 5-4c.345 0 .681.03 1 .087A5.99 5.99 0 0 0 6 12c0 .35-.06.687-.169 1H1z" />
                        </svg>
                        <span class="fs-6 ms-2 p-2">Announcements</span>
                    </a>
                </li>
            </ul>
       
            <a href="{{route('register')}}" class="position-absolute bottom-0 mb-3 fs-6 add-new-admin-hovered" style="left: 30%;">Add new admin</a>

  
       
        </div>
        <!-- Floating Navigation Bar End -->




        <!-- Main Content  -->
        <div class="p-3" style="margin-left: 21%;">
            <div class="d-flex flex-row align-items-center justify-content-between mb-4">

                <div>
                    <p class="m-0 fs-2 fw-bold">Dashboard</p>
                </div>



                <div class="d-flex">
                    {{-- NOTIFICATION --}}
                    {{-- <button type="button" class="position-relative me-5" data-bs-toggle="dropdown"
                        aria-expanded="false" style="border: none; background-color: transparent;">
                        <svg class="align-self-center" xmlns="http://www.w3.org/2000/svg" width="30" height="30"
                            fill="currentColor" class="bi bi-bell" viewBox="0 0 16 16">
                            <path
                                d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2M8 1.918l-.797.161A4 4 0 0 0 4 6c0 .628-.134 2.197-.459 3.742-.16.767-.376 1.566-.663 2.258h10.244c-.287-.692-.502-1.49-.663-2.258C12.134 8.197 12 6.628 12 6a4 4 0 0 0-3.203-3.92zM14.22 12c.223.447.481.801.78 1H1c.299-.199.557-.553.78-1C2.68 10.2 3 6.88 3 6c0-2.42 1.72-4.44 4.005-4.901a1 1 0 1 1 1.99 0A5 5 0 0 1 13 6c0 .88.32 4.2 1.22 6" />
                        </svg>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            99
                            <span class="visually-hidden">unread messages</span>
                        </span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-lg-end">
                        <li><button class="dropdown-item" type="button">Action</button></li>
                        <li><button class="dropdown-item" type="button">Another action</button></li>
                        <li><button class="dropdown-item" type="button">Something else here</button></li>
                    </ul> --}}

                    <svg class="me-3 border border border-danger border-1 rounded-circle"
                        xmlns="http://www.w3.org/2000/svg" width="35" height="35" fill="currentColor"
                        class="bi bi-person-circle" viewBox="0 0 16 16">
                        <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0" />
                        <path fill-rule="evenodd"
                            d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1" />
                    </svg>

                    <div class="d-flex justify-content-center align-items-center fs-6 me-3">
                        {{ Auth::user()->name }}
                    </div>


                    <form id="logout-form" action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="button" class="btn text-black-50" onclick="confirmLogout()">Logout</button>
                    </form>
                </div>

            </div>

            <!-- --------------------------------------------------------------------------------------------- -->

            <div class="d-flex">
                <div class="d-flex align-items-center gap-2 p-1 rounded ms-auto">
                    <form method="GET" action="{{ route('filter') }}" class="d-flex align-items-center gap-2 mb-0">
                        {{-- <label for="start_date" class="form-label mb-0 me-1 small">Start Date:</label> --}}
                        <input type="date" name="start_date" id="start_date" class="form-control form-control-sm w-auto"
                            value="{{ request('start_date') }}" required>
                        <div>-</div>
                        {{-- <label for="end_date" class="form-label mb-0 me-1 small">End Date:</label> --}}
                        <input type="date" name="end_date" id="end_date" class="form-control form-control-sm w-auto"
                            value="{{ request('end_date') }}" required>
            
                        <button type="submit" class="btn btn-sm btn-dark">Filter</button>
                    </form>
                    <a href="{{ route('index') }}" class="ms-0">
                        <button class="btn btn-sm btn-link text-decoration-none text-danger fw-bold">Clear</button>
                    </a>
                </div>
            </div>





            <div class="complaints d-grid gap-3">
                <section class="section-1">
                    {{-- REGISTERED COMPLAINTS --}}
                    <div class="">
                        <h5 class="mb-3"><span class="text-success">Registered</span> Tricycle Complaint</h5>
                        <div class="d-flex justify-content-evenly mt-3">
                            <div class="card border-top-0 border-bottom-0 border-end-0 border-5 shadow-sm"
                                style="border-color: rgba(34, 207, 207, 1)">
                                <div class="card-body gap-3 d-flex align-items-center">
                                    {{-- <div class="d-flex align-items-center"> --}}
                                    <div>
                                        <h6 class="card-title mb-0" style="color: rgba(34, 207, 207, 1)">In Queue</h6>
                                        {{-- </div> --}}
                                        <a href="{{ route('complaints.reg-inqueue') }}"
                                            class="text-decoration-none text-reset">
                                            <div class="">
                                                <h1 class="card-text p-0">{{ $regPendingCount }}</h1>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="">
                                        <div class="p-1 me-1 rounded-3 d-flex align-items-center justify-content-center"
                                            style="background-color: rgba(34, 207, 207, 1);">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32"
                                                fill="#ffffff" class="bi bi-clock-fill" viewBox="0 0 16 16">
                                                <path
                                                    d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8 3.5a.5.5 0 0 0-1 0V9a.5.5 0 0 0 .252.434l3.5 2a.5.5 0 0 0 .496-.868L8 8.71z" />
                                            </svg>
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <!-- In Process Card -->
                            <div class="card border-top-0 border-bottom-0 border-end-0 border-5 shadow-sm"
                                style="border-color: rgba(54,162,235,255)">
                                <div class="card-body gap-3 d-flex align-items-center">
                                    {{-- <div class="d-flex align-items-center"> --}}
                                    <div>
                                        <h6 class="card-title mb-0" style="color: rgba(54,162,235,255)">In Process</h6>
                                        {{-- </div> --}}
                                        <a href="{{ route('complaints.reg-inprocess') }}"
                                            class="text-decoration-none text-reset">
                                            <div class="">
                                                <h1 class="card-text p-0">{{ $regInProcessCount }}</h1>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="">
                                        <div class="p-1 me-1 rounded-3 d-flex align-items-center justify-content-center"
                                            style="background-color: rgba(54,162,235,255);">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32"
                                                fill="#ffffff" class="bi bi-gear-fill" viewBox="0 0 16 16">
                                                <path
                                                    d="M9.405 1.05c-.413-1.4-2.397-1.4-2.81 0l-.1.34a1.464 1.464 0 0 1-2.105.872l-.31-.17c-1.283-.698-2.686.705-1.987 1.987l.169.311c.446.82.023 1.841-.872 2.105l-.34.1c-1.4.413-1.4 2.397 0 2.81l.34.1a1.464 1.464 0 0 1 .872 2.105l-.17.31c-.698 1.283.705 2.686 1.987 1.987l.311-.169a1.464 1.464 0 0 1 2.105.872l.1.34c.413 1.4 2.397 1.4 2.81 0l.1-.34a1.464 1.464 0 0 1 2.105-.872l.31.17c1.283.698 2.686-.705 1.987-1.987l-.169-.311a1.464 1.464 0 0 1 .872-2.105l.34-.1c1.4-.413 1.4-2.397 0-2.81l-.34-.1a1.464 1.464 0 0 1-.872-2.105l.17-.31c.698-1.283-.705-2.686-1.987-1.987l-.311.169a1.464 1.464 0 0 1-2.105-.872zM8 10.93a2.929 2.929 0 1 1 0-5.86 2.929 2.929 0 0 1 0 5.858z" />
                                            </svg>
                                        </div>
                                    </div>

                                </div>
                            </div>


                            <!-- Resolved Card -->
                            <div class="card border-top-0 border-bottom-0 border-end-0 border-5 shadow-sm"
                                style="border-color: rgba(40, 167, 69, 1)">
                                <div class="card-body gap-3 d-flex align-items-center">
                                    {{-- <div class="d-flex align-items-center"> --}}
                                    <div>
                                        <h6 class="card-title mb-0" style="color: rgba(40, 167, 69, 1)">Resolved</h6>
                                        {{-- </div> --}}
                                        <a href="{{ route('complaints.reg-settled') }}"
                                            class="text-decoration-none text-reset">
                                            <div class="">
                                                <h1 class="card-text p-0">{{ $regSuccessCount }}</h1>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="">
                                        <div class="p-1 me-1 rounded-3 d-flex align-items-center justify-content-center"
                                            style="background-color: rgba(40, 167, 69, 1)">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32"
                                                fill="#ffffff" class="bi bi-patch-check-fill" viewBox="0 0 16 16">
                                                <path
                                                    d="M10.067.87a2.89 2.89 0 0 0-4.134 0l-.622.638-.89-.011a2.89 2.89 0 0 0-2.924 2.924l.01.89-.636.622a2.89 2.89 0 0 0 0 4.134l.637.622-.011.89a2.89 2.89 0 0 0 2.924 2.924l.89-.01.622.636a2.89 2.89 0 0 0 4.134 0l.622-.637.89.011a2.89 2.89 0 0 0 2.924-2.924l-.01-.89.636-.622a2.89 2.89 0 0 0 0-4.134l-.637-.622.011-.89a2.89 2.89 0 0 0-2.924-2.924l-.89.01zm.287 5.984-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7 8.793l2.646-2.647a.5.5 0 0 1 .708.708" />
                                            </svg>
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <!-- Unresolved Card -->
                            <div class="card border-top-0 border-bottom-0 border-end-0 border-5 shadow-sm"
                                style="border-color: rgba(220, 53, 69, 1);">
                                <div class="card-body gap-3 d-flex align-items-center">
                                    {{-- <div class="d-flex align-items-center"> --}}
                                    <div>
                                        <h6 class="card-title mb-0" style="color: rgba(220, 53, 69, 1);">Unresolved</h6>
                                        {{-- </div> --}}
                                        <a href="{{ route('complaints.reg-unresolved') }}"
                                            class="text-decoration-none text-reset">
                                            <div class="">
                                                <h1 class="card-text p-0">{{ $regUnresolvedCount }}</h1>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="">
                                        <div class="p-1 me-1 rounded-3 d-flex align-items-center justify-content-center"
                                            style="background-color: rgba(220, 53, 69, 1);">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32"
                                                fill="#ffffff" class="bi bi-exclamation-circle-fill" viewBox="0 0 16 16">
                                                <path
                                                    d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8 4a.905.905 0 0 0-.9.995l.35 3.507a.552.552 0 0 0 1.1 0l.35-3.507A.905.905 0 0 0 8 4m.002 6a1 1 0 1 0 0 2 1 1 0 0 0 0-2" />
                                            </svg>
                                        </div>
                                    </div>

                                </div>
                            </div>


                            <!-- Denied Card -->
                            <div class="card border-top-0 border-bottom-0 border-end-0 border-5 shadow-sm"
                                style="border-color: rgba(108, 117, 125, 1);">
                                <div class="card-body gap-3 d-flex align-items-center">
                                    {{-- <div class="d-flex align-items-center"> --}}
                                    <div>
                                        <h6 class="card-title mb-0" style="color: rgba(108, 117, 125, 1);">Denied</h6>
                                        {{-- </div> --}}
                                        <a href="{{ route('complaints.reg-denied') }}"
                                            class="text-decoration-none text-reset">
                                            <div class="">
                                                <h1 class="card-text p-0">{{ $regDeniedCount }}</h1>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="">
                                        <div class="p-1 me-1 rounded-3 d-flex align-items-center justify-content-center"
                                            style="background-color: rgba(108, 117, 125, 1);">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32"
                                                fill="#ffffff" class="bi bi-x-octagon-fill" viewBox="0 0 16 16">
                                                <path
                                                    d="M11.46.146A.5.5 0 0 0 11.107 0H4.893a.5.5 0 0 0-.353.146L.146 4.54A.5.5 0 0 0 0 4.893v6.214a.5.5 0 0 0 .146.353l4.394 4.394a.5.5 0 0 0 .353.146h6.214a.5.5 0 0 0 .353-.146l4.394-4.394a.5.5 0 0 0 .146-.353V4.893a.5.5 0 0 0-.146-.353zm-6.106 4.5L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 1 1 .708-.708" />
                                            </svg>
                                        </div>
                                    </div>

                                </div>
                            </div>

                        </div>

                    </div>




                    {{-- UNREGISTERED COMPLAINTS --}}
                    <div class=" mt-3">
                        <h5 class=""><span class="text-danger">Unregistered</span> Tricycle Complaint</h5>
                        <div class="d-flex gap-1 justify-content-evenly mt-3">
                            <div class="card border-top-0 border-bottom-0 border-end-0 border-5 shadow-sm"
                                style="border-color: rgba(34, 207, 207, 1)">
                                <div class="card-body gap-3 d-flex align-items-center">
                                    {{-- <div class="d-flex align-items-center"> --}}
                                    <div>
                                        <h6 class="card-title mb-0" style="color: rgba(34, 207, 207, 1)">In Queue</h6>
                                        {{-- </div> --}}
                                        <a href="{{ route('complaints.unreg-inqueue') }}"
                                            class="text-decoration-none text-reset">
                                            <div class="">
                                                <h1 class="card-text p-0">{{ $unregPendingCount }}</h1>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="">
                                        <div class="p-1 me-1 rounded-3 d-flex align-items-center justify-content-center"
                                            style="background-color: rgba(34, 207, 207, 1);">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32"
                                                fill="#ffffff" class="bi bi-clock-fill" viewBox="0 0 16 16">
                                                <path
                                                    d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8 3.5a.5.5 0 0 0-1 0V9a.5.5 0 0 0 .252.434l3.5 2a.5.5 0 0 0 .496-.868L8 8.71z" />
                                            </svg>
                                        </div>
                                    </div>

                                </div>
                            </div>


                            <!-- In Process Card -->
                            <div class="card border-top-0 border-bottom-0 border-end-0 border-5 shadow-sm"
                                style="border-color: rgba(54,162,235,255)">
                                <div class="card-body gap-3 d-flex align-items-center">
                                    {{-- <div class="d-flex align-items-center"> --}}
                                    <div>
                                        <h6 class="card-title mb-0" style="color: rgba(54,162,235,255)">In Process</h6>
                                        {{-- </div> --}}
                                        <a href="{{ route('complaints.unreg-inprocess') }}"
                                            class="text-decoration-none text-reset">
                                            <div class="">
                                                <h1 class="card-text p-0">{{ $unregInProcessCount }}</h1>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="">
                                        <div class="p-1 me-1 rounded-3 d-flex align-items-center justify-content-center"
                                            style="background-color: rgba(54,162,235,255);">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32"
                                                fill="#ffffff" class="bi bi-gear-fill" viewBox="0 0 16 16">
                                                <path
                                                    d="M9.405 1.05c-.413-1.4-2.397-1.4-2.81 0l-.1.34a1.464 1.464 0 0 1-2.105.872l-.31-.17c-1.283-.698-2.686.705-1.987 1.987l.169.311c.446.82.023 1.841-.872 2.105l-.34.1c-1.4.413-1.4 2.397 0 2.81l.34.1a1.464 1.464 0 0 1 .872 2.105l-.17.31c-.698 1.283.705 2.686 1.987 1.987l.311-.169a1.464 1.464 0 0 1 2.105.872l.1.34c.413 1.4 2.397 1.4 2.81 0l.1-.34a1.464 1.464 0 0 1 2.105-.872l.31.17c1.283.698 2.686-.705 1.987-1.987l-.169-.311a1.464 1.464 0 0 1 .872-2.105l.34-.1c1.4-.413 1.4-2.397 0-2.81l-.34-.1a1.464 1.464 0 0 1-.872-2.105l.17-.31c.698-1.283-.705-2.686-1.987-1.987l-.311.169a1.464 1.464 0 0 1-2.105-.872zM8 10.93a2.929 2.929 0 1 1 0-5.86 2.929 2.929 0 0 1 0 5.858z" />
                                            </svg>
                                        </div>
                                    </div>

                                </div>
                            </div>


                            <!-- Resolved Card -->
                            <div class="card border-top-0 border-bottom-0 border-end-0 border-5 shadow-sm"
                                style="border-color: rgba(40, 167, 69, 1)">
                                <div class="card-body gap-3 d-flex align-items-center">
                                    {{-- <div class="d-flex align-items-center"> --}}
                                    <div>
                                        <h6 class="card-title mb-0" style="color: rgba(40, 167, 69, 1)">Resolved</h6>
                                        {{-- </div> --}}
                                        <a href="{{ route('complaints.unreg-settled') }}"
                                            class="text-decoration-none text-reset">
                                            <div class="">
                                                <h1 class="card-text p-0">{{ $unregSuccessCount }}</h1>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="">
                                        <div class="p-1 me-1 rounded-3 d-flex align-items-center justify-content-center"
                                            style="background-color: rgba(40, 167, 69, 1)">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32"
                                                fill="#ffffff" class="bi bi-patch-check-fill" viewBox="0 0 16 16">
                                                <path
                                                    d="M10.067.87a2.89 2.89 0 0 0-4.134 0l-.622.638-.89-.011a2.89 2.89 0 0 0-2.924 2.924l.01.89-.636.622a2.89 2.89 0 0 0 0 4.134l.637.622-.011.89a2.89 2.89 0 0 0 2.924 2.924l.89-.01.622.636a2.89 2.89 0 0 0 4.134 0l.622-.637.89.011a2.89 2.89 0 0 0 2.924-2.924l-.01-.89.636-.622a2.89 2.89 0 0 0 0-4.134l-.637-.622.011-.89a2.89 2.89 0 0 0-2.924-2.924l-.89.01zm.287 5.984-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7 8.793l2.646-2.647a.5.5 0 0 1 .708.708" />
                                            </svg>
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <!-- Unresolved Card -->
                            <div class="card border-top-0 border-bottom-0 border-end-0 border-5 shadow-sm"
                                style="border-color: rgba(220, 53, 69, 1);">
                                <div class="card-body gap-3 d-flex align-items-center">
                                    {{-- <div class="d-flex align-items-center"> --}}
                                    <div>
                                        <h6 class="card-title mb-0" style="color: rgba(220, 53, 69, 1);">Unresolved</h6>
                                        {{-- </div> --}}
                                        <a href="{{ route('complaints.unreg-unresolved') }}"
                                            class="text-decoration-none text-reset">
                                            <div class="">
                                                <h1 class="card-text p-0">{{ $unregUnresolvedCount }}</h1>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="">
                                        <div class="p-1 me-1 rounded-3 d-flex align-items-center justify-content-center"
                                            style="background-color: rgba(220, 53, 69, 1);">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32"
                                                fill="#ffffff" class="bi bi-exclamation-circle-fill" viewBox="0 0 16 16">
                                                <path
                                                    d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8 4a.905.905 0 0 0-.9.995l.35 3.507a.552.552 0 0 0 1.1 0l.35-3.507A.905.905 0 0 0 8 4m.002 6a1 1 0 1 0 0 2 1 1 0 0 0 0-2" />
                                            </svg>
                                        </div>
                                    </div>

                                </div>
                            </div>


                            <!-- Denied Card -->
                            <div class="card border-top-0 border-bottom-0 border-end-0 border-5 shadow-sm"
                                style="border-color: rgba(108, 117, 125, 1);">
                                <div class="card-body gap-3 d-flex align-items-center">
                                    {{-- <div class="d-flex align-items-center"> --}}
                                    <div>
                                        <h6 class="card-title mb-0" style="color: rgba(108, 117, 125, 1);">Denied</h6>
                                        {{-- </div> --}}
                                        <a href="{{ route('complaints.unreg-denied') }}"
                                            class="text-decoration-none text-reset">
                                            <div class="">
                                                <h1 class="card-text p-0">{{ $unregDeniedCount }}</h1>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="">
                                        <div class="p-1 me-1 rounded-3 d-flex align-items-center justify-content-center"
                                            style="background-color: rgba(108, 117, 125, 1);">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32"
                                                fill="#ffffff" class="bi bi-x-octagon-fill" viewBox="0 0 16 16">
                                                <path
                                                    d="M11.46.146A.5.5 0 0 0 11.107 0H4.893a.5.5 0 0 0-.353.146L.146 4.54A.5.5 0 0 0 0 4.893v6.214a.5.5 0 0 0 .146.353l4.394 4.394a.5.5 0 0 0 .353.146h6.214a.5.5 0 0 0 .353-.146l4.394-4.394a.5.5 0 0 0 .146-.353V4.893a.5.5 0 0 0-.146-.353zm-6.106 4.5L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 1 1 .708-.708" />
                                            </svg>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                    </div>

                </section>




                <div class="row">
                    <div class="col-6 ps-0 pe-2">
                        <div class="p-3 bg-white shadow-sm rounded-3">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="p-3 bg-white d-flex justify-content-between align-items-center">
                                        <h5>Violations Statistics</h5>
                                        <div class="d-flex">
                                            <label for="time-filter" class="form-label mb-0 me-2">Filter by:</label>

                                            <select id="time-filter" onchange="handleFilterChange()"
                                                class="form-select form-select-sm" style="width: auto;">
                                                <option value="day" selected>Day</option>
                                                <option value="month">Month</option>
                                                <option value="year">Year</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div id="chart-container">
                                <canvas id="violationsChart"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 ps-0 pe-2">
                        <div class="p-3 bg-white shadow-sm rounded-3">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="p-3 bg-white d-flex justify-content-start align-items-center">
                                        <h5>Complaint By Type Overview</h5>
                                    </div>
                                </div>
                            </div>
                            <div id="chart-settled-complaints-container" class="pt-5" style="height: 311px">
                                <canvas id="settledComplaintsChart"></canvas>
                            </div>


                        </div>
                    </div>

                </div>
                <div class="row">
                    <div class="col-6 ps-0 pe-2">
                        <div class="p-3 shadow bg-white shadow-sm rounded-3">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="p-3 bg-white d-flex justify-content-start align-items-center">
                                        <h5>Registered Complaints Summary</h5>
                                    </div>
                                </div>
                            </div>
                            <div id="chart-container">
                                <canvas id="registeredChart"></canvas>
                            </div>
                        </div>
                    </div>

                    <div class="col-6 ps-0 pe-2">
                        <div class="p-3 shadow bg-white shadow-sm rounded-3">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="p-3 bg-white d-flex justify-content-start align-items-center">
                                        <h5>Unregistered Complaints Summary</h5>
                                    </div>
                                </div>
                            </div>
                            <div id="chart-container">
                                <canvas id="unregisteredChart"></canvas>
                            </div>

                        </div>
                    </div>
                </div>


                <div class="row">
                    <div class="col-6 ps-0 pe-2">
                        <div class="p-3 shadow bg-white shadow-sm rounded-3">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="p-3 bg-white d-flex justify-content-start align-items-center">
                                        <h5>Registered Complaints (Pending, In Process, Denied)</h5>
                                    </div>
                                </div>
                            </div>
                            <div id="chart-container">
                                <canvas id="registeredStatusChart-PID"></canvas>
                            </div>
                        </div>
                    </div>

                    <div class="col-6 ps-0 pe-2">
                        <div class="p-3 shadow bg-white shadow-sm rounded-3">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="p-3 bg-white d-flex justify-content-start align-items-center">
                                        <h5>Unregistered Complaints (Pending, In Process, Denied)</h5>
                                    </div>
                                </div>
                            </div>
                            <div id="chart-container">
                                <canvas id="unregisteredStatusChart-PID"></canvas>
                            </div>
                        </div>
                    </div>
                </div>



                <div class="row">
                    <div class=" ps-0 pe-0">
                        <div class="p-3 shadow bg-white shadow-sm rounded-3">
                            <div class="row">
                                <div class="col-md-12">
                                    <h5>Violation Frequency</h5>
                                    <div class=" bg-white d-flex  align-items-center">
                                        <table class="table table-bordered mt-3">
                                            <thead>

                                                <tr>
                                                    <th>Violation</th>
                                                    <th>Count</th>
                                                    <th>Percentage (%)</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($violationPercentages as $violation)
                                                    <tr
                                                        @if ($loop->first) style="font-weight: bold; font-size: 16px" @endif>
                                                        <td>{{ $violation['violation'] }}</td>
                                                        <td class="content">{{ $violation['count'] }}</td>
                                                        <td class="content">{{ $violation['percentage'] }}%</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <script>
            window.location = "{{ route('login') }}";
        </script>
    @endauth

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Fetch the data from the Blade variable
        let violationsChart; // Global variable to hold the chart instance

        // Function to render or update the chart
        const renderChart = (violationData) => {
            const maxValue = Math.max(...violationData.datasets[0].data);
            const buffer = 2; // Add extra space above the maximum value
            const suggestedMax = maxValue + buffer;

            const ctx = document.getElementById('violationsChart').getContext('2d');

            // If the chart already exists, update it
            if (violationsChart) {
                violationsChart.data = violationData;
                violationsChart.options.scales.y.suggestedMax = suggestedMax;
                violationsChart.update(); // Update the chart
            } else {
                // Create a new chart if it doesn't exist
                violationsChart = new Chart(ctx, {
                    type: 'line',
                    data: violationData,
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            x: {
                                type: 'category',
                                title: {
                                    display: true,
                                    text: 'Time Period',
                                    font: {
                                        weight: 'bold',
                                        size: 13,
                                        family: 'Arial',
                                        color: '#000000'
                                    }
                                },
                            },
                            y: {
                                beginAtZero: true,
                                suggestedMax: suggestedMax,
                                title: {
                                    display: true,
                                    text: 'Number of Violations',
                                    font: {
                                        weight: 'bold',
                                        size: 13,
                                        family: 'Arial',
                                        color: '#000000'
                                    }
                                },
                            }
                        }
                    }
                });
            }
        };


        window.handleFilterChange = function() {
            const filter = document.getElementById('time-filter').value;

            // Make an AJAX request to fetch the filtered data from the server
            fetch(`/getViolationsData?filter=${filter}`)
                .then(response => response.json())
                .then(data => {
                    // Call renderChart with the new data
                    renderChart(data);
                })
                .catch(error => console.error('Error fetching data:', error));
        };
        handleFilterChange();

        // Status
        // const statusData = {! $statusData !!};
        // console.log(statusData);
        // const statusMaxValue = Math.max(...statusData.datasets[0].data);
        // const statusBuffer = 2; // Add extra space above the maximum value
        // const statusSuggestedMax = statusMaxValue + statusBuffer;

        // const ctxStatus = document.getElementById('statusChart').getContext('2d');
        // new Chart(ctxStatus, {
        //     type: 'bar',
        //     data: statusData,
        //     options: {
        //         responsive: true,
        //         maintainAspectRatio: false,
        //         plugins: {
        //             legend: {
        //                 display: false
        //             }
        //         },
        //         scales: {
        //             x: {
        //                 title: {
        //                     display: true,
        //                     text: 'Status',
        //                     font: {
        //                         weight: 'bold',
        //                         size: 13,
        //                         family: 'Arial',
        //                         color: '#000000'
        //                     }
        //                 }
        //             },
        //             y: {
        //                 beginAtZero: true,
        //                 suggestedMax: statusSuggestedMax,
        //                 beginAtZero: true,
        //                 title: {
        //                     display: true,
        //                     text: 'Number of Complaints',
        //                     font: {
        //                         weight: 'bold',
        //                         size: 13,
        //                         family: 'Arial',
        //                         color: '#000000'
        //                     }
        //                 }
        //             }
        //         }
        //     }
        // });

        const registeredStatusData = {!! $registeredStatusData !!};
        const unregisteredStatusData = {!! $unregisteredStatusData !!};

        // Create the Registered Complaints Chart
        const ctxRegistered = document.getElementById('registeredChart').getContext('2d');
        const registeredMaxValue = Math.max(...registeredStatusData.datasets[0].data);
        const registeredSuggestedMax = registeredMaxValue + 1;
        new Chart(ctxRegistered, {
            type: 'bar',
            data: registeredStatusData,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Status',
                            font: {
                                weight: 'bold',
                                size: 13,
                                family: 'Arial',
                                color: '#000000'
                            }
                        }
                    },
                    y: {
                        beginAtZero: true,
                        suggestedMax: registeredSuggestedMax,
                        title: {
                            display: true,
                            text: 'Number of Complaints',
                            font: {
                                weight: 'bold',
                                size: 13,
                                family: 'Arial',
                                color: '#000000'
                            }
                        },
                        ticks: {
                            stepSize: 1,
                        }
                    }
                }
            }
        });

        // Create the Unregistered Complaints Chart
        const ctxUnregistered = document.getElementById('unregisteredChart').getContext('2d');
        const unregisteredMaxValue = Math.max(...unregisteredStatusData.datasets[0].data);
        const unregisteredSuggestedMax = unregisteredMaxValue + 1;
        new Chart(ctxUnregistered, {
            type: 'bar',
            data: unregisteredStatusData,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Status',
                            font: {
                                weight: 'bold',
                                size: 13,
                                family: 'Arial',
                                color: '#000000'
                            }
                        }
                    },
                    y: {
                        beginAtZero: true,
                        suggestedMax: unregisteredSuggestedMax,
                        title: {
                            display: true,
                            text: 'Number of Complaints',
                            font: {
                                weight: 'bold',
                                size: 13,
                                family: 'Arial',
                                color: '#000000'
                            }
                        },
                        ticks: {
                            stepSize: 1,
                        }
                    }
                }
            }
        });
    });

    var settledComplaintsData = {
        labels: ['Registered Complaints', 'Unregistered Complaints'],
        datasets: [{

            data: [{{ $registeredComplaintCount }}, {{ $unregisteredComplaintCount }}],
            backgroundColor: ['#4CAF50', '#FF9800'],
            borderColor: ['#4CAF50', '#FF9800'],
            borderWidth: 1
        }]
    };

    // Chart configuration
    var ctx = document.getElementById('settledComplaintsChart').getContext('2d');
    var settledComplaintsChart = new Chart(ctx, {
        type: 'bar', // You can change this to 'pie', 'line', etc.
        data: settledComplaintsData,
        options: {
            plugins: {
                legend: {
                    display: false,

                }
            },
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Complaint Type',
                        font: {
                            weight: 'bold',
                            size: 13,
                            family: 'Arial',
                            color: '#000000'
                        }
                    }
                },

                y: {
                    title: {
                        display: true,
                        text: 'Number of Complaints',
                        font: {
                            weight: 'bold',
                            size: 13,
                            family: 'Arial',
                            color: '#000000'
                        }
                    },
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1,
                    },
                    suggestedMax: Math.max({{ $regSuccessCount }}, {{ $unregSuccessCount }}) +
                        2 // adds two extra numbers
                }
            }
        }
    });






    // GRAPH FOR REGISTERED AND UNREGISTERE PID
    // Registered Complaints - Pending, In Process, Denied

    var registeredStatusDataForNewGraph = {!! $registeredStatusDataForNewGraph !!};

    const registeredMaxValue = Math.max(...registeredStatusDataForNewGraph.datasets[0].data);
    const registeredSuggestedMax = registeredMaxValue + 1;
    var ctxRegistered = document.getElementById('registeredStatusChart-PID').getContext('2d');
    var registeredStatusChart = new Chart(ctxRegistered, {
        type: 'bar',
        data: registeredStatusDataForNewGraph,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Status',
                        font: {
                            weight: 'bold',
                            size: 13,
                            family: 'Arial',
                            color: '#000000'
                        }
                    }
                },
                y: {
                    beginAtZero: true,
                    suggestedMax: registeredSuggestedMax,
                    title: {
                        display: true,
                        text: 'Number of Complaints',
                        font: {
                            weight: 'bold',
                            size: 13,
                            family: 'Arial',
                            color: '#000000'
                        }
                    },
                    ticks: {
                        stepSize: 1,
                    }
                }
            }
        }
    });

    var unregisteredStatusDataForNewGraph = {!! $unregisteredStatusDataForNewGraph !!};

    // Unregistered Complaints - Pending, In Process, Denied
    const unregisteredMaxValue = Math.max(...unregisteredStatusDataForNewGraph.datasets[0].data);
    const unregisteredSuggestedMax = unregisteredMaxValue + 1;
    var ctxUnregistered = document.getElementById('unregisteredStatusChart-PID').getContext('2d');
    var unregisteredStatusChart = new Chart(ctxUnregistered, {
        type: 'bar',
        data: unregisteredStatusDataForNewGraph,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Status',
                        font: {
                            weight: 'bold',
                            size: 13,
                            family: 'Arial',
                            color: '#000000'
                        }
                    }
                },
                y: {
                    beginAtZero: true,
                    suggestedMax: unregisteredSuggestedMax,
                    title: {
                        display: true,
                        text: 'Number of Complaints',
                        font: {
                            weight: 'bold',
                            size: 13,
                            family: 'Arial',
                            color: '#000000'
                        }
                    },
                    ticks: {
                        stepSize: 1,
                    }
                }
            }
        }
    });
</script>
<script src="{{ asset('logout.js') }}"></script>

</html>
